# Prism — design system

## Atmosphere

A cool, grained light page. Prism is an off-white canvas with a fine film
grain, white cards with a hairline, near-black ink and one teal for whatever
is live: the routed model, links, status dots, the focus ring. The primary
button is ink, never a colour. Headlines are set in Source Serif at 400 —
large, tight, unhurried — over Inter for everything a visitor reads or
presses, and Geist Mono for the data: model ids, prices, latencies, code.
Four pastel beams — periwinkle, pink, cyan, amber — stream diagonally behind
the hero and the closing call, the way a prism throws a spectrum across a
wall, and nowhere else. One dark band leaves the canvas: the endpoint
section. The hero is the signature: a prompt composer whose chips re-rank a
live routing console under it.

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
| Beams | `--color-beam-1…4` | `#c7d2fe` `#fbcfe8` `#a5f3fc` `#fde68a` | The hero and closing-call backdrop only. Never text. |

Accent budget: at most three teal things in any viewport. Never a teal
button. Never a beam colour as text or a fill.

## Typography

- **Display:** Source Serif 4 at 400 with optical sizing. `.display-hero`
  clamp(2.75→4.75rem) / 1.05 / −0.02em; `.display-page` clamp(2.5→4rem);
  `.display-section` clamp(1.875→2.625rem) / 1.1; `.display-quote`
  clamp(1.5→2.375rem) / 1.3; `.display-stat` clamp(2→2.75rem), tabular.
- **Text and UI:** Inter 400/500/600. `.title-card` 1.125rem/500; `.lede`
  1.125→1.25rem / 1.55; body 1rem / 1.65; small 0.875rem.
- **Data:** Geist Mono via `.data` — 13px, tabular — for model ids, prices,
  latencies and code. Mono is for measurement, never for mood.
- No eyebrows. Headlines carry themselves, `text-balance`; paragraphs
  `text-pretty`; measure ≤ 68ch.

## Spacing & radius

- Container 1152px (`max-w-page`), page padding `px-6`.
- Sections `py-24` (96px) on desktop, `py-16` (64px) on phones; the dark band
  `py-28`; the closing call `py-32`. Column gap `gap-x-12` on every split.
- Radii: `rounded-lg` (8px) on buttons and fields, `rounded-xl` (12px) on
  every frame and card, `rounded-[1.25rem]` (20px) on the composer and the
  mega-menu panel, `rounded-full` on chips and pills.

## Components

- **Buttons:** `.btn-solid` (ink, canvas text — one per section), `.btn-ghost`
  (white with a hairline), `.btn-band` (canvas-coloured, on the dark band).
  Sizes `.btn-sm` / `.btn-lg`. Arrows nudge on hover; buttons lift one pixel.
- **Frame:** `.frame` — white, hairline, 12px, `--shadow-card` — for every
  drawn mock and card. `.lift` on frames that link.
- **The composer:** `.composer` — 20px white card, `--shadow-float`, a
  content-sized textarea, the chip row and the round ink send button.
- **Chips:** `.chip` pills; `aria-pressed="true"` is the raised fill with an
  ink ring. Used by the composer and the models filter.
- **The console:** `[data-console]` — hairline rows of model · provider ·
  latency · price · `.score` bar; `data-routed` tints the row and shows the
  teal pill; `.console-reason` is the line under it. Rows FLIP when re-ranked.
- **Mocks:** `x-mocks.policy` (a rules file), `x-mocks.usage` (a bar chart),
  `x-mocks.promo` (the tiny console in the mega-menu card). Drawn on white
  with `.data` figures; zero images.
- **Mega-menu:** `[data-dropdown]` — two link columns with 16px marks and a
  one-line description each, a dark promo card on the right, 150ms scale-in.
- **Tables:** on the canvas, hairline rows only, `whitespace-nowrap`
  headings, sideways scroll on phones.
- **Beams and grain:** `.beams` + `.beams-fade` inside a `.grain` section —
  the hero, the closing call, inner-page headers.

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
- No beams outside the hero, the closing call and page headers.
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
    --color-beam-1: #c7d2fe;
    --color-beam-2: #fbcfe8;
    --color-beam-3: #a5f3fc;
    --color-beam-4: #fde68a;
    --font-display: "Source Serif 4", ui-serif, Georgia, serif;
    --font-sans: "Inter", ui-sans-serif, system-ui, sans-serif;
    --font-mono: "Geist Mono", ui-monospace, SFMono-Regular, Menlo, monospace;
    --container-page: 72rem;
    --shadow-card: 0 1px 2px rgb(17 17 20 / 0.04), 0 8px 24px -12px rgb(17 17 20 / 0.12);
    --shadow-float: 0 1px 2px rgb(17 17 20 / 0.05), 0 12px 40px -12px rgb(17 17 20 / 0.18), 0 32px 80px -32px rgb(17 17 20 / 0.16);
}
```
