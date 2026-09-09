/*
    Prism — the interaction layer.

    Everything here is a progressive enhancement. Without JavaScript nothing is
    hidden, every link works, the console shows its first ranking, the models
    table shows every row and the FAQ still opens. With it the page gains the
    mega-menu, the phone menu, the dismissible announcement, scroll reveals,
    the composer whose chips re-rank the console, the models filter and the
    one-open FAQ.

    setUp(root) owns everything inside <main>. It runs once on load and again
    after every instant navigation for the swapped-in <main>. The header binds
    once — it persists across pages.
*/

document.documentElement.classList.add('js');

var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

var revealObserver = null;
var revealWatched = [];
var skies = [];
var strips = [];

header();
setUp(document);

document.addEventListener('instant:navigated', function (event) {
    closeMobileMenu();
    closeDropdowns();
    setUp(event.detail.main);
});

function setUp(root) {
    tearDown();

    reveals(root);
    iridescence(root);
    tabStrips(root);
    filters(root);
    singleOpenFaq(root);
}

/* Reveal observers, render loops and tab timers outlive a page, so they are what gets released. */
function tearDown() {
    skies = skies.filter(function (sky) {
        if (sky.canvas.isConnected) {
            return true;
        }
        sky.stop();
        return false;
    });

    strips = strips.filter(function (entry) {
        if (entry.strip.isConnected) {
            return true;
        }
        entry.stop();
        return false;
    });

    if (!revealObserver) {
        return;
    }

    revealWatched = revealWatched.filter(function (el) {
        if (el.isConnected && !el.classList.contains('is-visible')) {
            return true;
        }
        revealObserver.unobserve(el);
        return false;
    });
}

/* ── Header ────────────────────────────────────────────────────────────────── */

function header() {
    var el = document.getElementById('header');

    if (!el) {
        return;
    }

    announcement(el);
    mobileMenu(el);
    dropdowns(el);
}

/* The announcement line: the × hides it for the rest of the session. */
function announcement(header) {
    var bar = header.querySelector('#announce');
    var button = header.querySelector('[data-announce-dismiss]');

    if (!bar || !button) {
        return;
    }

    try {
        if (sessionStorage.getItem('prism.announce') === 'dismissed') {
            bar.setAttribute('data-dismissed', '');
        }
    } catch (error) {
        // Storage may be unavailable; the bar simply shows.
    }

    button.addEventListener('click', function () {
        bar.setAttribute('data-dismissed', '');
        try {
            sessionStorage.setItem('prism.announce', 'dismissed');
        } catch (error) {
            // Nothing to do — the bar is hidden for this page at least.
        }
    });
}

function mobileMenu(header) {
    var toggle = header.querySelector('[data-menu-toggle]');

    if (!toggle) {
        return;
    }

    toggle.addEventListener('click', function () {
        var open = header.hasAttribute('data-menu-open');
        header.style.setProperty('--header-bottom', header.getBoundingClientRect().bottom + 'px');
        header.toggleAttribute('data-menu-open', !open);
        toggle.setAttribute('aria-expanded', String(!open));
        document.documentElement.style.overflow = open ? '' : 'hidden';
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            closeMobileMenu();
        }
    });
}

function closeMobileMenu() {
    var header = document.getElementById('header');
    var toggle = header && header.querySelector('[data-menu-toggle]');

    if (!header || !header.hasAttribute('data-menu-open')) {
        return;
    }

    header.removeAttribute('data-menu-open');
    toggle && toggle.setAttribute('aria-expanded', 'false');
    document.documentElement.style.overflow = '';
}

/*
    The mega-menus. Hover opens with a short intent delay so diagonal pointer
    travel toward the panel never slams it shut; click toggles for touch and
    keyboards; Escape and a click outside close. Only one is open at a time.
*/
function dropdowns(header) {
    var items = Array.prototype.slice.call(header.querySelectorAll('[data-dropdown]'));

    if (!items.length) {
        return;
    }

    var closeTimer = null;

    items.forEach(function (item) {
        var trigger = item.querySelector('[data-dropdown-trigger]');

        if (!trigger) {
            return;
        }

        item.addEventListener('pointerenter', function (event) {
            if (event.pointerType === 'touch') {
                return;
            }
            clearTimeout(closeTimer);
            openDropdown(item, items);
        });

        item.addEventListener('pointerleave', function (event) {
            if (event.pointerType === 'touch') {
                return;
            }
            closeTimer = setTimeout(function () {
                closeDropdown(item);
            }, 140);
        });

        trigger.addEventListener('click', function () {
            if (item.hasAttribute('data-open')) {
                closeDropdown(item);
            } else {
                openDropdown(item, items);
            }
        });
    });

    document.addEventListener('click', function (event) {
        if (!header.contains(event.target)) {
            closeDropdowns();
        }
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            closeDropdowns();
        }
    });
}

function openDropdown(item, all) {
    all.forEach(function (other) {
        if (other !== item) {
            closeDropdown(other);
        }
    });

    if (item.hasAttribute('data-open')) {
        return;
    }

    item.setAttribute('data-open', '');
    item.querySelector('[data-dropdown-trigger]').setAttribute('aria-expanded', 'true');

    /* Two frames: display flips first, then the transition to visible runs. */
    requestAnimationFrame(function () {
        requestAnimationFrame(function () {
            item.setAttribute('data-shown', '');
        });
    });
}

function closeDropdown(item) {
    if (!item.hasAttribute('data-open')) {
        return;
    }

    item.removeAttribute('data-shown');
    item.removeAttribute('data-open');
    item.querySelector('[data-dropdown-trigger]').setAttribute('aria-expanded', 'false');
}

function closeDropdowns() {
    var header = document.getElementById('header');

    if (!header) {
        return;
    }

    Array.prototype.forEach.call(header.querySelectorAll('[data-dropdown][data-open]'), closeDropdown);
}

/* ── Scroll reveals ────────────────────────────────────────────────────────── */

/*
    Blocks marked data-reveal fade and rise once as they enter the viewport.
    Reduced motion shows everything at once (the CSS already leaves it visible).
*/
function reveals(root) {
    var blocks = Array.prototype.slice.call(root.querySelectorAll('[data-reveal]'));

    if (!blocks.length) {
        return;
    }

    if (reduceMotion || !('IntersectionObserver' in window)) {
        blocks.forEach(function (el) { el.classList.add('is-visible'); });
        return;
    }

    if (!revealObserver) {
        revealObserver = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    revealObserver.unobserve(entry.target);
                }
            });
        }, { rootMargin: '0px 0px -8% 0px', threshold: 0.1 });
    }

    blocks.forEach(function (el) {
        if (el.classList.contains('is-visible')) {
            return;
        }
        revealObserver.observe(el);
        revealWatched.push(el);
    });
}

/* ── The iridescence ───────────────────────────────────────────────────────── */

/*
    The light behind the hero. Each <canvas data-iridescence> inside a .beams
    block gets a small WebGL program in two parts: a white sheet of drifting
    light, and fluted glass in front of it — forward-leaning ridges that bend
    the sheet and refract each colour channel by a different amount, which is
    what draws the prismatic edges. The page is white until a pointer touches
    it; then the glass swells under the pointer, splits harder, and blooms
    from --color-glass-a at the core to --color-glass-b at the rim. Both come
    from the CSS tokens at runtime.

    It renders at half resolution, only while on screen and the tab is visible,
    and paints one still frame under reduced motion. Without WebGL the canvas
    removes itself and the CSS beams underneath show instead.
*/
function iridescence(root) {
    Array.prototype.forEach.call(root.querySelectorAll('canvas[data-iridescence]'), function (canvas) {
        var sky = createIridescence(canvas);
        if (sky) {
            skies.push(sky);
        }
    });
}

function createIridescence(canvas) {
    var host = canvas.parentElement;
    var gl = canvas.getContext('webgl', { antialias: false, alpha: false, depth: false, stencil: false, powerPreference: 'low-power' });

    if (!gl) {
        canvas.remove();
        return null;
    }

    var vertex = 'attribute vec2 p;void main(){gl_Position=vec4(p,0.0,1.0);}';
    var fragment = [
        'precision mediump float;',
        'uniform vec2 u_res;uniform float u_t;uniform vec3 u_ptr;',
        'uniform vec3 u_base;uniform vec3 u_glass_a;uniform vec3 u_glass_b;',
        'float hash(vec2 p){return fract(sin(dot(p,vec2(127.1,311.7)))*43758.5453);}',
        'float noise(vec2 p){vec2 i=floor(p);vec2 f=fract(p);f=f*f*(3.0-2.0*f);',
        'return mix(mix(hash(i),hash(i+vec2(1.0,0.0)),f.x),mix(hash(i+vec2(0.0,1.0)),hash(i+vec2(1.0,1.0)),f.x),f.y);}',
        'float fbm(vec2 p){return 0.65*noise(p)+0.35*noise(p*2.1+3.7);}',
        /*
            The sheet of light behind the glass: white paper crossed by two
            broad soft bands that drift. No colour lives here — what a visitor
            sees at rest is the glass dispersing this white, and the only
            colour on the page is the bloom their pointer brings.
        */
        'vec3 shade(vec2 q,float t){',
        'float band=sin(dot(q,vec2(0.9,1.5))*2.6-t*0.35)*0.5+0.5;',
        'float band2=sin(dot(q,vec2(1.7,-0.6))*1.9+t*0.22)*0.5+0.5;',
        'vec3 c=u_base*(0.930+0.095*band*band+0.055*band2);',
        /* Under the pointer: electric blue at the core, orchid at the rim. */
        'vec2 dp=q-u_ptr.xy;float d=length(dp);',
        'vec3 tint=mix(u_glass_a,u_glass_b,smoothstep(0.0,0.42,d));',
        'tint=mix(tint,vec3(1.0),0.40);',
        'c=mix(c,tint,exp(-d*d*9.0)*0.66*u_ptr.z);',
        'return c;}',
        'void main(){',
        'vec2 uv=gl_FragCoord.xy/u_res;float a=u_res.x/u_res.y;vec2 p=vec2(uv.x*a,uv.y);float t=u_t;',
        /* One organic warp for the whole pixel, so the three channel samples stay cheap. */
        'vec2 q=p+0.26*vec2(fbm(p*1.4+t*0.10),fbm(p*1.4+vec2(4.3,1.7)-t*0.08));',
        'float detail=fbm(q*3.4-t*0.12);',
        /*
            The fluted glass. The ridges lean forward, and each one is a little
            lens: it bends the sheet behind it, and it bends each colour
            channel by a different amount, which is the whole trick — white
            light in, a rainbow along every edge out.
        */
        'vec2 axis=vec2(cos(-0.593),sin(-0.593));',
        'float s=dot(p,axis)*6.5+t*0.15;',
        'float f=fract(s)*2.0-1.0;',
        'float lens=f*sqrt(max(0.0,1.0-f*f*0.82));',
        /* The glass swells under the pointer, so the ridges bow around it and split harder. */
        'vec2 dq=q-u_ptr.xy;float bulge=exp(-dot(dq,dq)*7.0)*u_ptr.z;',
        'vec2 off=axis*lens*0.026*(1.0+2.4*bulge);',
        'float spread=0.30+1.40*bulge;',
        'vec3 col;',
        'col.r=shade(q+off*(1.0+spread),t).r;',
        'col.g=shade(q+off,t).g;',
        'col.b=shade(q+off*(1.0-spread),t).b;',
        'col*=0.985+0.03*detail;',
        /* The glint riding the crest of each ridge, and the grain over everything. */
        'col+=pow(max(0.0,1.0-abs(f)),9.0)*0.05;',
        'col+=(hash(gl_FragCoord.xy*0.7+t)-0.5)*0.04;',
        'gl_FragColor=vec4(col,1.0);}'
    ].join('\n');

    var program = gl.createProgram();
    var vs = compileShader(gl, gl.VERTEX_SHADER, vertex);
    var fs = compileShader(gl, gl.FRAGMENT_SHADER, fragment);

    if (!vs || !fs) {
        canvas.remove();
        return null;
    }

    gl.attachShader(program, vs);
    gl.attachShader(program, fs);
    gl.linkProgram(program);

    if (!gl.getProgramParameter(program, gl.LINK_STATUS)) {
        canvas.remove();
        return null;
    }

    gl.useProgram(program);

    var buffer = gl.createBuffer();
    gl.bindBuffer(gl.ARRAY_BUFFER, buffer);
    gl.bufferData(gl.ARRAY_BUFFER, new Float32Array([-1, -1, 3, -1, -1, 3]), gl.STATIC_DRAW);
    var position = gl.getAttribLocation(program, 'p');
    gl.enableVertexAttribArray(position);
    gl.vertexAttribPointer(position, 2, gl.FLOAT, false, 0, 0);

    var uRes = gl.getUniformLocation(program, 'u_res');
    var uTime = gl.getUniformLocation(program, 'u_t');
    var uPtr = gl.getUniformLocation(program, 'u_ptr');
    var styles = getComputedStyle(document.documentElement);
    var colour = function (name, fallback) {
        return parseColour(styles.getPropertyValue(name).trim() || fallback);
    };

    gl.uniform3fv(gl.getUniformLocation(program, 'u_base'), colour('--color-canvas', '#f4f4f6'));
    gl.uniform3fv(gl.getUniformLocation(program, 'u_glass_a'), colour('--color-glass-a', '#1e00ff'));
    gl.uniform3fv(gl.getUniformLocation(program, 'u_glass_b'), colour('--color-glass-b', '#d765e6'));

    var frame = null;
    var visible = true;
    var start = performance.now() + Math.random() * 60000;
    /* Where the bloom is, where it is heading, and how strong it is. */
    var ptr = { x: 0.5, y: 0.5, tx: 0.5, ty: 0.5, on: 0, want: 0 };

    var resize = function () {
        var width = Math.max(1, Math.round(host.clientWidth * 0.5));
        var height = Math.max(1, Math.round(host.clientHeight * 0.5));
        if (canvas.width !== width || canvas.height !== height) {
            canvas.width = width;
            canvas.height = height;
            gl.viewport(0, 0, width, height);
            gl.uniform2f(uRes, width, height);
        }
    };

    var draw = function (now) {
        var ease = 0.06;
        ptr.x += (ptr.tx - ptr.x) * ease;
        ptr.y += (ptr.ty - ptr.y) * ease;
        ptr.on += (ptr.want - ptr.on) * 0.04;
        gl.uniform3f(uPtr, ptr.x, ptr.y, ptr.on);
        gl.uniform1f(uTime, (now - start) / 1000 * 0.26);
        gl.drawArrays(gl.TRIANGLES, 0, 3);
    };

    var loop = function (now) {
        frame = null;
        if (!visible || document.hidden) {
            return;
        }
        draw(now);
        frame = requestAnimationFrame(loop);
    };

    var play = function () {
        if (frame === null && visible && !document.hidden && !reduceMotion) {
            frame = requestAnimationFrame(loop);
        }
    };

    resize();
    draw(performance.now());
    host.classList.add('has-mesh');

    var observer = new IntersectionObserver(function (entries) {
        visible = entries.some(function (entry) { return entry.isIntersecting; });
        if (visible) {
            play();
        }
    }, { rootMargin: '10% 0px' });
    observer.observe(host);

    var onVisibility = function () { play(); };
    var onResize = function () { resize(); if (reduceMotion) { draw(performance.now()); } };
    document.addEventListener('visibilitychange', onVisibility);
    window.addEventListener('resize', onResize, { passive: true });

    /* The bloom follows a real pointer only — a fingertip would leave it stranded. */
    var surface = host.closest('section') || host;
    var fine = window.matchMedia('(pointer: fine)').matches;
    var onMove = function (event) {
        var rect = host.getBoundingClientRect();
        if (!rect.height) {
            return;
        }
        ptr.tx = (event.clientX - rect.left) / rect.height;
        ptr.ty = 1 - (event.clientY - rect.top) / rect.height;
        ptr.want = 1;
    };
    var onLeave = function () { ptr.want = 0; };

    if (fine && !reduceMotion) {
        surface.addEventListener('pointermove', onMove, { passive: true });
        surface.addEventListener('pointerleave', onLeave, { passive: true });
    }

    play();

    return {
        canvas: canvas,
        stop: function () {
            if (frame !== null) {
                cancelAnimationFrame(frame);
            }
            observer.disconnect();
            document.removeEventListener('visibilitychange', onVisibility);
            window.removeEventListener('resize', onResize);
            surface.removeEventListener('pointermove', onMove);
            surface.removeEventListener('pointerleave', onLeave);
            var lose = gl.getExtension('WEBGL_lose_context');
            if (lose) {
                lose.loseContext();
            }
        }
    };
}

function compileShader(gl, type, source) {
    var shader = gl.createShader(type);
    gl.shaderSource(shader, source);
    gl.compileShader(shader);
    return gl.getShaderParameter(shader, gl.COMPILE_STATUS) ? shader : null;
}

/* "#rrggbb", "#rgb" or "rgb(r g b / a)" → [r, g, b] in 0–1. */
function parseColour(value) {
    var m = value.match(/^#([0-9a-f]{3,8})$/i);
    if (m) {
        var hex = m[1];
        if (hex.length < 6) {
            hex = hex.split('').map(function (c) { return c + c; }).join('');
        }
        return [0, 2, 4].map(function (i) { return parseInt(hex.substr(i, 2), 16) / 255; });
    }
    m = value.match(/rgba?\(([^)]+)\)/i);
    if (m) {
        var parts = m[1].split(/[\s,\/]+/).filter(Boolean).slice(0, 3);
        return parts.map(function (n) { return parseFloat(n) / 255; });
    }
    return [0.96, 0.96, 0.97];
}

/* ── The tab strip ─────────────────────────────────────────────────────────── */

/*
    The tasks above the console window. Pressing a tab shows its screen and
    stops the tour; the pause button stops and restarts it. The tour only runs
    while the strip is on screen, and never under reduced motion — there the
    tabs are a plain, manual control.
*/
function tabStrips(root) {
    Array.prototype.forEach.call(root.querySelectorAll('[data-tabs]'), function (strip) {
        var tabs = Array.prototype.slice.call(strip.querySelectorAll('[data-tab-key]'));
        var panels = Array.prototype.slice.call(strip.querySelectorAll('[data-tab-of]'));
        var pause = strip.querySelector('[data-tab-pause]');

        if (tabs.length < 2) {
            return;
        }

        var index = 0;
        var timer = null;
        var onScreen = true;
        var wanted = !reduceMotion;

        var show = function (next, scroll) {
            index = (next + tabs.length) % tabs.length;
            var key = tabs[index].getAttribute('data-tab-key');

            tabs.forEach(function (tab, n) {
                tab.setAttribute('aria-selected', n === index ? 'true' : 'false');
            });
            panels.forEach(function (panel) {
                panel.hidden = panel.getAttribute('data-tab-of') !== key;
            });

            if (scroll) {
                tabs[index].scrollIntoView({ block: 'nearest', inline: 'nearest' });
            }
        };

        var stopTimer = function () {
            if (timer !== null) {
                clearInterval(timer);
                timer = null;
            }
        };

        var startTimer = function () {
            stopTimer();
            if (wanted && onScreen && !reduceMotion) {
                timer = setInterval(function () { show(index + 1, true); }, 6000);
            }
        };

        tabs.forEach(function (tab, n) {
            tab.addEventListener('click', function () {
                wanted = false;
                stopTimer();
                if (pause) {
                    pause.setAttribute('data-paused', '');
                }
                show(n, false);
            });
        });

        if (pause) {
            pause.addEventListener('click', function () {
                wanted = !wanted;
                pause.toggleAttribute('data-paused', !wanted);
                startTimer();
            });
            pause.toggleAttribute('data-paused', !wanted);
        }

        var observer = new IntersectionObserver(function (entries) {
            onScreen = entries.some(function (entry) { return entry.isIntersecting; });
            startTimer();
        }, { threshold: 0.2 });
        observer.observe(strip);

        strips.push({ strip: strip, stop: function () { stopTimer(); observer.disconnect(); } });
        startTimer();
    });
}

/* ── The models filter ─────────────────────────────────────────────────────── */

/* Chips with data-filter-key hide every table row whose data-family doesn't match. */
function filters(root) {
    Array.prototype.forEach.call(root.querySelectorAll('[data-filter]'), function (group) {
        var chips = Array.prototype.slice.call(group.querySelectorAll('[data-filter-key]'));
        var section = group.closest('section') || root;
        var rows = Array.prototype.slice.call(section.querySelectorAll('[data-family]'));

        chips.forEach(function (chip) {
            chip.addEventListener('click', function () {
                var key = chip.getAttribute('data-filter-key');
                chips.forEach(function (other) {
                    other.setAttribute('aria-pressed', String(other === chip));
                });
                rows.forEach(function (row) {
                    row.hidden = key !== 'all' && row.getAttribute('data-family') !== key;
                });
            });
        });
    });
}

/* ── FAQ ───────────────────────────────────────────────────────────────────── */

/* Opening one question closes the others in the same list. */
function singleOpenFaq(root) {
    Array.prototype.forEach.call(root.querySelectorAll('[data-faq]'), function (item) {
        item.addEventListener('toggle', function () {
            if (!item.open) {
                return;
            }
            var siblings = item.parentElement.querySelectorAll('[data-faq]');
            Array.prototype.forEach.call(siblings, function (other) {
                if (other !== item && other.open) {
                    other.open = false;
                }
            });
        });
    });
}
