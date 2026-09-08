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
var meshes_ = [];

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
    meshGradients(root);
    composers(root);
    filters(root);
    singleOpenFaq(root);
}

/* Reveal observers and mesh render loops outlive a page, so they are what gets released. */
function tearDown() {
    meshes_ = meshes_.filter(function (mesh) {
        if (mesh.canvas.isConnected) {
            return true;
        }
        mesh.stop();
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

/* ── The mesh gradient ─────────────────────────────────────────────────────── */

/*
    The four beam colours, drifting. Each <canvas data-mesh> inside a .beams
    block gets a small WebGL program: four soft colour fields whose centres
    wander slowly, over a domain warped by two sine waves and a little noise
    so the whole thing rolls like silk. Colours come from the CSS tokens at
    runtime — retune --color-beam-* and the mesh follows. It renders at half
    resolution, only while on screen and the tab is visible, and paints one
    still frame under reduced motion. Without WebGL the canvas is removed and
    the CSS beams show.
*/
function meshGradients(root) {
    Array.prototype.forEach.call(root.querySelectorAll('canvas[data-mesh]'), function (canvas) {
        var mesh = createMesh(canvas);
        if (mesh) {
            meshes_.push(mesh);
        }
    });
}

function createMesh(canvas) {
    var host = canvas.parentElement;
    var gl = canvas.getContext('webgl', { antialias: false, alpha: false, depth: false, stencil: false, powerPreference: 'low-power' });

    if (!gl) {
        canvas.remove();
        return null;
    }

    var vertex = 'attribute vec2 p;void main(){gl_Position=vec4(p,0.0,1.0);}';
    var fragment = [
        'precision mediump float;',
        'uniform vec2 u_res;uniform float u_t;uniform vec3 u_base;uniform vec3 u_c0;uniform vec3 u_c1;uniform vec3 u_c2;uniform vec3 u_c3;',
        'float hash(vec2 p){return fract(sin(dot(p,vec2(127.1,311.7)))*43758.5453);}',
        'float noise(vec2 p){vec2 i=floor(p);vec2 f=fract(p);f=f*f*(3.0-2.0*f);',
        'return mix(mix(hash(i),hash(i+vec2(1.0,0.0)),f.x),mix(hash(i+vec2(0.0,1.0)),hash(i+vec2(1.0,1.0)),f.x),f.y);}',
        'float blob(vec2 p,vec2 c,float s){vec2 d=p-c;return exp(-dot(d,d)*s);}',
        'vec3 lift(vec3 c){return c;}',
        'void main(){',
        'vec2 uv=gl_FragCoord.xy/u_res;float a=u_res.x/u_res.y;vec2 p=vec2(uv.x*a,uv.y);float t=u_t;',
        'vec2 w=p;w.x+=0.22*sin(p.y*2.6+t*0.9)+0.08*sin(p.y*7.0-t*1.3);w.y+=0.18*sin(p.x*1.9-t*0.7)+0.06*sin(p.x*6.0+t*1.1);w+=0.14*(noise(w*2.2+t*0.35)-0.5);',
        'vec2 c0=vec2(0.16*a+0.24*sin(t*0.50),0.70+0.22*cos(t*0.42));',
        'vec2 c1=vec2(0.46*a+0.30*cos(t*0.37),0.30+0.24*sin(t*0.61));',
        'vec2 c2=vec2(0.84*a+0.24*sin(t*0.44+2.0),0.72+0.22*cos(t*0.53));',
        'vec2 c3=vec2(0.64*a+0.28*cos(t*0.29+1.0),0.16+0.22*sin(t*0.47));',
        'float ripple=0.62+0.38*sin(dot(w,vec2(1.2,1.6))*4.2-t*0.8);',
        'float ripple2=0.72+0.28*sin(dot(w,vec2(-0.9,1.3))*3.1+t*0.6);',
        'float s=1.9/a;',
        'float w0=blob(w,c0,s)*ripple;float w1=blob(w,c1,s*0.9)*ripple2;float w2=blob(w,c2,s)*(1.24-ripple);float w3=blob(w,c3,s*0.95)*(1.44-ripple2);',
        'float sum=w0+w1+w2+w3+1e-4;',
        'vec3 mixc=lift((u_c0*w0+u_c1*w1+u_c2*w2+u_c3*w3)/sum);',
        'float cover=clamp(sum*1.5,0.0,1.0);',
        'vec3 col=mix(u_base,mix(vec3(1.0),mixc,0.62),cover);',
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

    gl.uniform3fv(gl.getUniformLocation(program, 'u_base'), colour('--color-canvas', '#f4f4f6'));
    gl.uniform3fv(gl.getUniformLocation(program, 'u_c0'), colour('--color-beam-1', '#c7d2fe'));
    gl.uniform3fv(gl.getUniformLocation(program, 'u_c1'), colour('--color-beam-3', '#a5f3fc'));
    gl.uniform3fv(gl.getUniformLocation(program, 'u_c2'), colour('--color-beam-2', '#fbcfe8'));
    gl.uniform3fv(gl.getUniformLocation(program, 'u_c3'), colour('--color-beam-4', '#fde68a'));

    var frame = null;
    var visible = true;
    var start = performance.now() + Math.random() * 60000;

    var resize = function () {
        var scale = 0.5;
        var width = Math.max(1, Math.round(host.clientWidth * scale));
        var height = Math.max(1, Math.round(host.clientHeight * scale));
        if (canvas.width !== width || canvas.height !== height) {
            canvas.width = width;
            canvas.height = height;
            gl.viewport(0, 0, width, height);
            gl.uniform2f(uRes, width, height);
        }
    };

    var draw = function (now) {
        gl.uniform1f(uTime, (now - start) / 1000 * 0.32);
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

/* ── The composer and the console ──────────────────────────────────────────── */

/*
    Prism's signature. Each chip carries a route key, an example prompt and a
    reason. Pressing one fills the prompt, shows the route-strip line for its
    key and swaps the reason. A page that also carries a routing console
    ([data-console] beside the form) gets its rows re-ordered by
    data-rank-{key} with a FLIP transition.
*/
function composers(root) {
    Array.prototype.forEach.call(root.querySelectorAll('[data-composer]'), function (form) {
        var input = form.querySelector('[data-composer-input]');
        var chips = Array.prototype.slice.call(form.querySelectorAll('.chip[data-route]'));
        var results = Array.prototype.slice.call(form.querySelectorAll('[data-route-result]'));
        var console_ = form.parentElement.querySelector('[data-console]');
        var reason = form.querySelector('[data-console-reason]') || (console_ && console_.querySelector('[data-console-reason]'));

        if (!input || !chips.length) {
            return;
        }

        chips.forEach(function (chip) {
            chip.addEventListener('click', function () {
                chips.forEach(function (other) {
                    other.setAttribute('aria-pressed', String(other === chip));
                });

                input.value = chip.getAttribute('data-prompt') || '';
                input.dispatchEvent(new Event('input', { bubbles: true }));

                var key = chip.getAttribute('data-route');
                results.forEach(function (line) {
                    line.hidden = line.getAttribute('data-route-result') !== key;
                });

                if (console_) {
                    rerank(console_, key);
                }

                if (reason) {
                    swapText(reason, chip.getAttribute('data-reason') || '');
                }
            });
        });

        /* Typing your own prompt releases the chips; the console keeps its last ranking. */
        input.addEventListener('input', function () {
            var matches = chips.some(function (chip) {
                return chip.getAttribute('data-prompt') === input.value;
            });
            if (!matches) {
                chips.forEach(function (chip) { chip.setAttribute('aria-pressed', 'false'); });
            }
        });

        form.addEventListener('submit', function (event) {
            if (!input.value.trim()) {
                event.preventDefault();
                input.focus();
            }
        });
    });
}

function rerank(console_, key) {
    var list = console_.querySelector('ol');

    if (!list || !key) {
        return;
    }

    var rows = Array.prototype.slice.call(list.children);
    var attr = 'data-rank-' + key;

    var before = new Map();
    rows.forEach(function (row) {
        before.set(row, row.getBoundingClientRect().top);
    });

    rows.sort(function (a, b) {
        return Number(a.getAttribute(attr) || 99) - Number(b.getAttribute(attr) || 99);
    });

    rows.forEach(function (row, index) {
        list.appendChild(row);
        row.toggleAttribute('data-routed', index === 0);
    });

    if (reduceMotion) {
        return;
    }

    rows.forEach(function (row) {
        var delta = before.get(row) - row.getBoundingClientRect().top;
        if (!delta) {
            return;
        }
        row.removeAttribute('data-flip');
        row.style.translate = '0 ' + delta + 'px';
        requestAnimationFrame(function () {
            row.setAttribute('data-flip', '');
            row.style.translate = '0 0';
        });
    });
}

function swapText(el, text) {
    if (reduceMotion) {
        el.textContent = text;
        return;
    }

    el.setAttribute('data-updating', '');
    setTimeout(function () {
        el.textContent = text;
        el.removeAttribute('data-updating');
    }, 200);
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
