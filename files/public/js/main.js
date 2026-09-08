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
    composers(root);
    filters(root);
    singleOpenFaq(root);
}

/* Reveal observers outlive a page, so they are what gets released. */
function tearDown() {
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

/* ── The composer and the console ──────────────────────────────────────────── */

/*
    Prism's signature. Each chip carries a route key, an example prompt and a
    reason. Pressing one fills the prompt, re-orders the console rows by their
    data-rank-{key} (moved with a FLIP transition so each row glides to its new
    place), hands the top row the Routed pill and swaps the reason line.
*/
function composers(root) {
    Array.prototype.forEach.call(root.querySelectorAll('[data-composer]'), function (form) {
        var input = form.querySelector('[data-composer-input]');
        var chips = Array.prototype.slice.call(form.querySelectorAll('.chip[data-route]'));
        var console_ = form.parentElement.querySelector('[data-console]');
        var reason = console_ && console_.querySelector('[data-console-reason]');

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

                if (console_) {
                    rerank(console_, chip.getAttribute('data-route'));
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
