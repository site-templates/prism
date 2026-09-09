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
    closeMegaMenu();
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
    megaMenu(el);
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
    The mega-menu. Every dropdown's contents live in one card below the bar, so
    moving from one menu to the next is a single object gliding sideways and
    changing shape rather than one panel vanishing and another appearing. Each
    panel is laid out at its natural size inside the card, which is what gets
    measured; the card takes that width, that height and the pressed trigger's
    offset, and the panels cross-fade.

    Hover opens with a short intent delay so a diagonal pointer never opens a
    menu on the way past; once one is open the next opens instantly, which is
    what makes the morph feel continuous. Click toggles for touch and
    keyboards, Escape and a click outside close.
*/
function megaMenu(header) {
    var stage = header.querySelector('[data-menu-stage]');
    var card = stage && stage.querySelector('[data-menu-card]');
    var triggers = Array.prototype.slice.call(header.querySelectorAll('[data-menu-trigger]'));

    if (!stage || !card || !triggers.length) {
        return;
    }

    var panels = Array.prototype.slice.call(stage.querySelectorAll('[data-menu-panel]'));
    var openKey = null;
    var openTimer = null;
    var closeTimer = null;

    var find = function (list, attribute, key) {
        for (var i = 0; i < list.length; i++) {
            if (list[i].getAttribute(attribute) === key) {
                return list[i];
            }
        }
        return null;
    };

    var show = function (key) {
        var panel = find(panels, 'data-menu-panel', key);
        var trigger = find(triggers, 'data-menu-trigger', key);

        if (!panel || !trigger) {
            return;
        }

        /* Opening from closed places the card without animating, so it never slides in from the last menu. */
        var settle = openKey === null;

        if (settle) {
            card.setAttribute('data-instant', '');
        }

        var stageBox = stage.getBoundingClientRect();
        var triggerBox = trigger.getBoundingClientRect();
        var width = panel.offsetWidth;
        var height = panel.offsetHeight;
        var x = triggerBox.left - stageBox.left - 12;

        x = Math.max(0, Math.min(x, Math.max(0, stageBox.width - width)));

        card.style.setProperty('--menu-w', width + 'px');
        card.style.setProperty('--menu-h', height + 'px');
        card.style.setProperty('--menu-x', x + 'px');

        panels.forEach(function (item) { item.toggleAttribute('data-active', item === panel); });
        triggers.forEach(function (item) { item.setAttribute('aria-expanded', item === trigger ? 'true' : 'false'); });
        stage.setAttribute('data-open', '');
        openKey = key;

        if (settle) {
            void card.offsetWidth;
            card.removeAttribute('data-instant');
        }
    };

    var hide = function () {
        openKey = null;
        stage.removeAttribute('data-open');
        panels.forEach(function (item) { item.removeAttribute('data-active'); });
        triggers.forEach(function (item) { item.setAttribute('aria-expanded', 'false'); });
    };

    var scheduleClose = function () {
        clearTimeout(closeTimer);
        closeTimer = setTimeout(hide, 160);
    };

    var hold = function () {
        clearTimeout(closeTimer);
    };

    triggers.forEach(function (trigger) {
        var key = trigger.getAttribute('data-menu-trigger');

        trigger.addEventListener('pointerenter', function (event) {
            if (event.pointerType === 'touch') {
                return;
            }
            hold();
            clearTimeout(openTimer);
            openTimer = setTimeout(function () { show(key); }, openKey === null ? 90 : 0);
        });

        trigger.addEventListener('click', function () {
            clearTimeout(openTimer);
            if (openKey === key) {
                hide();
            } else {
                show(key);
            }
        });
    });

    /*
        The close is scheduled when the pointer leaves the header altogether —
        the card is a descendant of it, so travelling from a trigger down into
        the menu, or across to the next trigger, never schedules one. That is
        what lets the card morph instead of blinking out between menus.
    */
    header.addEventListener('pointerenter', hold);
    header.addEventListener('pointerleave', function (event) {
        if (event.pointerType === 'touch') {
            return;
        }
        clearTimeout(openTimer);
        scheduleClose();
    });

    header.addEventListener('focusout', function (event) {
        if (!header.contains(event.relatedTarget)) {
            hide();
        }
    });

    document.addEventListener('click', function (event) {
        if (!header.contains(event.target)) {
            hide();
        }
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            hide();
        }
    });

    /* A resize moves the triggers, so an open card is measured and placed again. */
    window.addEventListener('resize', function () {
        if (openKey !== null) {
            show(openKey);
        }
    }, { passive: true });

    /* The instant-navigation handler closes the menu through this. */
    header.closeMegaMenu = hide;
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
    light over a pale mesh of six spectrum colours, and fluted glass in front
    of it — wide forward-leaning ridges that bend the sheet and refract each
    colour channel by a different amount, which is what draws the prismatic
    seam on every ripple. A canvas can ask for fewer, wider ripples with
    data-flutes. Nothing here reacts to the pointer; it simply drifts. Every
    colour comes from the CSS tokens at runtime.

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
        'uniform vec2 u_res;uniform float u_t;uniform float u_freq;',
        'uniform vec3 u_base;',
        'uniform vec3 u_c1;uniform vec3 u_c2;uniform vec3 u_c3;uniform vec3 u_c4;uniform vec3 u_c5;uniform vec3 u_c6;',
        'float hash(vec2 p){return fract(sin(dot(p,vec2(127.1,311.7)))*43758.5453);}',
        'float noise(vec2 p){vec2 i=floor(p);vec2 f=fract(p);f=f*f*(3.0-2.0*f);',
        'return mix(mix(hash(i),hash(i+vec2(1.0,0.0)),f.x),mix(hash(i+vec2(0.0,1.0)),hash(i+vec2(1.0,1.0)),f.x),f.y);}',
        'float fbm(vec2 p){return 0.65*noise(p)+0.35*noise(p*2.1+3.7);}',
        'float pool(vec2 q,vec2 c,float r){vec2 d=q-c;return exp(-dot(d,d)*r);}',
        /*
            The sheet behind the glass: white paper crossed by two broad bands
            of light, and under them a mesh of six colour pools — violet, blue,
            green, yellow, orange, rose — drifting at the edges of the frame on
            their own slow clocks. Each is a whisper, a twelfth of the way
            from white at most, so the page never reads grey, never reads
            purple, and never announces itself as a rainbow.
        */
        'vec3 shade(vec2 q,float t){',
        'float band=sin(dot(q,vec2(0.9,1.5))*2.6-t*0.35)*0.5+0.5;',
        'float band2=sin(dot(q,vec2(1.7,-0.6))*1.9+t*0.22)*0.5+0.5;',
        'vec3 c=u_base*(0.965+0.050*band*band+0.030*band2);',
        'c=mix(c,u_c1,pool(q,vec2(0.02+0.09*sin(t*0.21),1.00+0.07*cos(t*0.17)),3.1)*0.088);',
        'c=mix(c,u_c2,pool(q,vec2(0.40+0.10*cos(t*0.16),-0.06+0.08*sin(t*0.19)),3.3)*0.078);',
        'c=mix(c,u_c3,pool(q,vec2(0.98+0.09*sin(t*0.13+1.1),1.06+0.07*cos(t*0.15)),3.4)*0.070);',
        'c=mix(c,u_c4,pool(q,vec2(1.52+0.10*cos(t*0.18),0.74+0.09*sin(t*0.14)),3.2)*0.076);',
        'c=mix(c,u_c5,pool(q,vec2(1.30+0.09*sin(t*0.12+2.2),-0.04+0.08*cos(t*0.20)),3.3)*0.066);',
        'c=mix(c,u_c6,pool(q,vec2(-0.04+0.08*cos(t*0.15+0.7),0.40+0.09*sin(t*0.18)),3.2)*0.074);',
        'return c;}',
        'void main(){',
        'vec2 uv=gl_FragCoord.xy/u_res;float a=u_res.x/u_res.y;vec2 p=vec2(uv.x*a,uv.y);float t=u_t;',
        /* One organic warp for the whole pixel, so the three channel samples stay cheap. */
        'vec2 q=p+0.26*vec2(fbm(p*1.4+t*0.10),fbm(p*1.4+vec2(4.3,1.7)-t*0.08));',
        'float detail=fbm(q*3.4-t*0.12);',
        /*
            The fluted glass. The ridges lean forward and each one is a wide,
            shallow lens: it shades the sheet across its whole width, catches a
            light along its seam, and refracts each colour channel by a
            different amount — so every seam draws a faint rainbow out of the
            mesh behind it. How many ridges a section gets is its own choice.
        */
        'vec2 axis=vec2(cos(-0.593),sin(-0.593));',
        'float s=dot(p,axis)*u_freq+t*0.30;',
        'float f=fract(s)*2.0-1.0;',
        'float flute=0.5+0.5*cos(s*6.2831853);',
        'float lens=f*sqrt(max(0.0,1.0-f*f*0.82));',
        'vec2 off=axis*lens*0.024;',
        'vec3 col;',
        'col.r=shade(q+off*1.55,t).r;',
        'col.g=shade(q+off,t).g;',
        'col.b=shade(q+off*0.45,t).b;',
        /* The body of each ripple, shaded across its full width, and the seam light. */
        'col*=0.965+0.075*flute;',
        'col+=pow(max(0.0,1.0-abs(f)),3.0)*0.030+pow(max(0.0,1.0-abs(f)),14.0)*0.040;',
        'col*=0.992+0.016*detail;',
        'col+=(hash(gl_FragCoord.xy+t)-0.5)*0.008;',
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
    var styles = getComputedStyle(document.documentElement);
    var colour = function (name, fallback) {
        return parseColour(styles.getPropertyValue(name).trim() || fallback);
    };

    gl.uniform3fv(gl.getUniformLocation(program, 'u_base'), colour('--color-surface', '#ffffff'));
    gl.uniform3fv(gl.getUniformLocation(program, 'u_c1'), colour('--color-mesh-violet', '#a78bfa'));
    gl.uniform3fv(gl.getUniformLocation(program, 'u_c2'), colour('--color-mesh-blue', '#7dd3fc'));
    gl.uniform3fv(gl.getUniformLocation(program, 'u_c3'), colour('--color-mesh-green', '#86efac'));
    gl.uniform3fv(gl.getUniformLocation(program, 'u_c4'), colour('--color-mesh-yellow', '#fde68a'));
    gl.uniform3fv(gl.getUniformLocation(program, 'u_c5'), colour('--color-mesh-orange', '#fdba74'));
    gl.uniform3fv(gl.getUniformLocation(program, 'u_c6'), colour('--color-mesh-rose', '#fda4af'));

    /*
        How many ripples this canvas draws across its width: data-flutes on the
        element, 8 by default. The shader counts them along the ridge axis, so
        the figure is divided by the element's own aspect in resize() — without
        that a short wide band would draw three times as many as a tall one.
    */
    var uFreq = gl.getUniformLocation(program, 'u_freq');
    var flutes = parseFloat(canvas.getAttribute('data-flutes')) || 8;

    var frame = null;
    var visible = true;
    var start = performance.now() + Math.random() * 60000;


    var resize = function () {
        var width = Math.max(1, Math.round(host.clientWidth * 0.5));
        var height = Math.max(1, Math.round(host.clientHeight * 0.5));
        if (canvas.width !== width || canvas.height !== height) {
            canvas.width = width;
            canvas.height = height;
            gl.viewport(0, 0, width, height);
            gl.uniform2f(uRes, width, height);
            gl.uniform1f(uFreq, flutes / (width / height));
        }
    };

    var draw = function (now) {
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
