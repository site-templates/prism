# Prism — design system

## Atmosphere

A cool, grained light page. Prism is an off-white canvas with a fine film
grain, white cards with a hairline, near-black ink and one teal for whatever
is live: the routed model, links, status dots, the focus ring. The primary
button is ink, never a colour. Headlines are set in Source Serif at 400 —
large, tight, unhurried — over Inter for everything a visitor reads or
presses, and Geist Mono for the data: model ids, prices, latencies, code.
The hero is a sheet of white light behind fluted glass. A WebGL canvas draws
the sheet — white paper crossed by two broad bands of light, over a mesh of
six colour pools, one per spectrum hue, drifting at the edges of the frame and
never more than a twelfth of the way from white — and the glass in front of it: wide
forward-leaning ridges, each a shallow lens that shades the sheet across its
whole width, catches a light along its seam, and refracts every colour
channel by a different amount, so each seam draws a faint rainbow out of the
mesh behind it. Nothing here answers the pointer; it simply drifts. The same
picture is drawn in CSS where WebGL is missing. Softer echoes sit behind
inner-page headers and the closing call, and nowhere else. One dark band
leaves the canvas: the endpoint section. Under the headline, a strip of tasks
tours the router's own screen — a console drawn entirely in markup, whose
sidebar highlight, breadcrumb, request table and assistant panel change with
the tab.

Adjectives: exact, calm, light, technical, quietly confident.
Anti-adjectives: hype, glow, neon.

## Colors

| Role | Token | Value | Use |
|---|---|---|---|
| Canvas | `--color-canvas` | `#f4f4f6` | The page. Cool off-white, grained where the beams are. |
| Surface | `--color-surface` | `#ffffff` | Every card, frame, the composer, chips. |
| Raised | `--color-raised` | `#ebebee` | Pressed chips, code chips, score tracks. |
| Well | `--color-well` | `#f8f8fa` | Recessed strips, hover fills, the console footer. |
| Ink | `--color-ink` | `#111114` | Headings, primary text, the primary button. 17:1 on the canvas. |
| Muted | `--color-muted` | `#5b5b66` | Body copy. 6.1:1. |
| Faint | `--color-faint` | `#68687a` | Captions, ids, dates. 4.9:1. |
| Line | `--color-line` | `rgb(17 17 20 / 0.08)` | Every hairline; `--color-line-strong` at 0.16 for edges that must be seen. |
| Accent | `--color-accent` | `#0f766e` | Teal: links, the Routed pill, the pressed chip ring, live dots, score bars, the focus ring. Never a button fill. |
| Band | `--color-band` | `#111114` | The endpoint section and the mega-menu promo card. Ink `#f4f4f6`, muted `#a1a1aa`. |
| Mesh | `--color-mesh-violet` `-blue` `-green` `-yellow` `-orange` `-rose` | `#a78bfa` `#7dd3fc` `#86efac` `#fde68a` `#fdba74` `#fda4af` | The spectrum behind the glass. Six pools, none above a twelfth strength, so the light is never grey, never one hue, and never reads as a rainbow. Never text, never a fill. |
| Window lights | `--color-light-red` `-amber` `-green` | `#ff5f57` `#febc2e` `#28c840` | The three lights on a drawn window's title bar, and nowhere else. |
| Diverted | `--color-diverted` | `#7c3aed` | A pinned or fallen-back leg in the console. 5.7:1 on white. |
| Alert | `--color-alert` | `#c2410c` | A failed or timed-out leg in the console, and nothing else. |

Accent budget: at most three teal things in any viewport. Never a teal
button. Never a beam colour as text or a fill.

## Typography

- **Display:** Source Serif 4 at 400 with optical sizing. `.display-hero`
  clamp(2.75→4.25rem) / 1.08 / −0.02em; `.display-page` clamp(2.75→4.5rem);
  `.display-section` clamp(2→3rem) / 1.08; `.display-quote`
  clamp(1.5→2.375rem) / 1.3; `.display-stat` clamp(2.5→3.5rem), tabular.
- **Text and UI:** Inter 400/500/600. `.title-card` 1.125rem/500; `.lede`
  1.0625→1.1875rem / 1.6; body 1rem / 1.65; small 0.875rem.
- **Data:** Geist Mono via `.data` — 13px, tabular — for model ids, prices,
  latencies and code. Mono is for measurement, never for mood.
- No eyebrows. Headlines carry themselves, `text-balance`; paragraphs
  `text-pretty`; measure ≤ 68ch.

## Spacing & radius

- Container 1152px (`max-w-page`), page padding `px-6`.
- Sections `py-32` (128px) on desktop, `py-24` (96px) on phones; the dark band
  `py-28`; the closing call `py-32`. Column gap `gap-x-12` on every split.
- Radii: `rounded-lg` (8px) on buttons and fields, `rounded-xl` (12px) on
  every frame and card, 20px on the composer card and the mega-menu panel,
  28px on the composer shell, `rounded-full` on chips and pills.

## Components

- **Buttons:** `.btn-solid` (ink, canvas text — one per section), `.btn-ghost`
  (white with a hairline), `.btn-band` (canvas-coloured, on the dark band).
  Sizes `.btn-sm` / `.btn-lg`. Arrows nudge on hover; buttons lift one pixel.
- **Frame:** `.frame` — white, hairline, 12px, `--shadow-card` — for every
  drawn mock and card. `.lift` on frames that link.
- **The console window:** `.win` — Prism's signature, drawn in markup, never a
  screenshot. A title bar, a `.win-side` sidebar from the consoleGroups and
  consoleNav collections, and one `[data-tab-of]` panel per task from the
  workbench collection: breadcrumb, filter row, the request table (rows from
  workbenchRows, `.win-status` in one of three tones) and the `.win-panel`
  assistant column — the question, the two `.win-run` commands, the answer and
  the `.win-input` prompt field.
- **The tab strip:** `.tab` — one task per tab with a 16px mark, a violet
  underline on the pressed one, and the tour button that stops the
  auto-advance. It scrolls sideways rather than wrapping.
- **Chips:** `.chip` pills, 40px; `aria-pressed="true"` is the ink fill with
  canvas text. Used by the models filter.
- **Provider marks:** 32px circles in each provider's brand colour (a `color`
  field on the providers collection — data, not a token) with a white initial
  and ring, overlapping like the tables.so integration row.
- **The hero sky:** `.hero-sky` — absolute, masked to fade into the canvas over
  its last sixth, grain inside, the iridescence canvas under it.
- **Mocks:** `x-mocks.policy` (a rules file), `x-mocks.usage` (a bar chart),
  `x-mocks.promo` (the tiny console in the mega-menu card). Drawn on white
  with `.data` figures; zero images.
- **Mega-menu:** `[data-dropdown]` — two link columns with 16px marks and a
  one-line description each, a dark promo card on the right, 150ms scale-in.
- **Tables:** on the canvas, hairline rows only, `whitespace-nowrap`
  headings, sideways scroll on phones.
- **Window lights:** `x-mocks.lights` — the three title-bar lights every drawn
  window wears, in the console, the policy file and the endpoint band's code.
- **Light and grain:** `.beams` (with its `canvas[data-iridescence]`, whose
  `data-flutes` sets how many ripples it draws — 7 in the hero, 4 in the
  closing call and the page headers, where they run wider) inside
  `.hero-sky` on the home page; `.beams` + `.beams-fade` inside a `.grain`
  section on inner-page headers and the closing call. main.js paints at half
  resolution, only while on screen, and one still frame under reduced motion; `.has-mesh` switches the CSS fallback
  off. The fallback is the same white picture in three layers: the ground on
  the element, the two bands on `::before`, the flutes on `::after`.

## Photographs

None. Every visual is drawn in markup: the console, the policy file, the
usage chart, the code block. Avatars use the stock set
(`https://assets.ui.sh/avatars/N.webp?size=160`).

## Voice

Developer-facing, exact, unhurried. A claim comes with a number ("6× cheaper
at equal quality on summaries"). Sentences are short. Never "powerful",
"seamless", "unlock", "supercharge", "all-in-one".

## Anti-patterns

- No coloured button; no teal as a surface; no gradient text; no glow.
- No light outside the hero, the closing call and page headers.
- No hue dominant in the mesh; if the light reads as one colour, it is wrong.
- No screenshot where the interface can be drawn in markup.
- No three-card grid twice on a page; no icon in a coloured circle.
- No eyebrows or section numbers.
- No `text-white`, `bg-zinc-*` or a hex outside site.css.

## Tokens

```css
@theme {
    --color-canvas: #f4f4f6;
    --color-surface: #ffffff;
    --color-raised: #ebebee;
    --color-well: #f8f8fa;
    --color-ink: #111114;
    --color-ink-lift: #2a2a30;
    --color-muted: #5b5b66;
    --color-faint: #68687a;
    --color-line: rgb(17 17 20 / 0.08);
    --color-line-strong: rgb(17 17 20 / 0.16);
    --color-accent: #0f766e;
    --color-accent-lift: #0d9488;
    --color-accent-ink: #ffffff;
    --color-accent-wash: rgb(15 118 110 / 0.1);
    --color-band: #111114;
    --color-band-ink: #f4f4f6;
    --color-band-muted: #a1a1aa;
    --color-band-faint: #8a8a94;
    --color-band-line: rgb(255 255 255 / 0.12);
    --color-band-raised: rgb(255 255 255 / 0.06);
    --color-diverted: #7c3aed;
    --color-alert: #c2410c;
    --color-mesh-violet: #a78bfa;
    --color-mesh-blue: #7dd3fc;
    --color-mesh-green: #86efac;
    --color-mesh-yellow: #fde68a;
    --color-mesh-orange: #fdba74;
    --color-mesh-rose: #fda4af;
    --color-light-red: #ff5f57;
    --color-light-amber: #febc2e;
    --color-light-green: #28c840;
    --font-display: "Source Serif 4", ui-serif, Georgia, serif;
    --font-sans: "Inter", ui-sans-serif, system-ui, sans-serif;
    --font-mono: "Geist Mono", ui-monospace, SFMono-Regular, Menlo, monospace;
    --container-page: 72rem;
    --shadow-card: 0 1px 2px rgb(17 17 20 / 0.04), 0 8px 24px -12px rgb(17 17 20 / 0.12);
    --shadow-float: 0 1px 2px rgb(17 17 20 / 0.05), 0 12px 40px -12px rgb(17 17 20 / 0.18), 0 32px 80px -32px rgb(17 17 20 / 0.16);
}
```
