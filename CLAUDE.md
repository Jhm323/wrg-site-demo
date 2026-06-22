# DIRTcar Racing Demo Homepage

Design-direction prototype for dirtcar.com (DIRTcar Racing / World Racing Group). Not production code — purpose is to establish visual direction and component patterns.

## Tech Constraints

- Plain HTML/CSS/JS only. No build step, no frameworks, no bundler.
- Three files: `index.html`, `style.css`, `script.js`. Additional assets (images, fonts via Google Fonts CDN) are fine.
- Must open by double-clicking `index.html` — no local server required, no `file://` workarounds.
- Single page (SPA-style scroll sections, not multiple HTML files).

## Brand Tokens

Define these as CSS custom properties on `:root` in `style.css`. Treat as source of truth — never hardcode hex values in component rules.

```css
:root {
  --dc-black:      #0D0D0D;
  --dc-clay:       #3A2418;
  --dc-yellow:     #F4B400;
  --dc-red:        #C8102E;
  --dc-bone:       #EDE8DD;
  --dc-track-gray: #8C8579;
}
```

## Typography

Load all four families from Google Fonts in `index.html`. Never embed `@import` in CSS.

| Family | Role |
|---|---|
| Anton | Display / headlines |
| Oswald | Labels, nav items, eyebrows |
| Inter | Body copy |
| JetBrains Mono | Stats, timing, countdown digits |

## Reference Patterns

Each section should borrow the named interaction/layout from the cited source.

| Pattern | Inspired by |
|---|---|
| Floating pill-shaped header that transitions to a solid bar on scroll | IndyCar |
| Podium-style top-3 standings cards with rank-tinted color treatment | Formula 1 |
| High-contrast black background with editorial cards layered on top | NHL |
| Hero carousel with index-dot navigation cycling automatically | USA Shooting |
| Scroll-triggered staggered card reveal animations | Legends Lounge, Juventus |

## Accessibility Floor

These are non-negotiable, not stretch goals:

- Visible `:focus-visible` states on all interactive elements (do not rely on browser defaults alone — style them explicitly with `--dc-yellow` outline).
- All animations and transitions must check `@media (prefers-reduced-motion: reduce)` and either remove or reduce motion accordingly.
- Semantic HTML landmarks: `<header>`, `<nav>`, `<main>`, `<section>` (with `aria-label` where multiple sections exist), `<footer>`.
- Images need descriptive `alt` text; decorative images use `alt=""`.

## Code Style

- **No inline styles in HTML.** `style="..."` attributes are forbidden. Use classes.
- **All CSS lives in `style.css`.** No `<style>` blocks in `index.html`.
- **All JS lives in `script.js`.** No `<script>` blocks in `index.html` except the single `<script src="script.js" defer>` tag.
- **CSS custom properties for every color.** Component rules reference `var(--dc-*)` tokens only — no raw hex, rgb(), or named colors in component declarations.
- CSS is organized top-to-bottom: custom properties → resets → typography → layout → components → utilities → media queries.

## Brand Tokens (from racing_entity table)

This demo is re-skinnable: one structural template that swaps brand colors per WRG property via a `data-brand` attribute on the `<html>` element. The three verified skins below come from the live WRG database `racing_entity` table — treat hex values as source of truth, do not alter them.

```css
[data-brand="woo-sprint"] {   /* World of Outlaws Sprint Car Series — racing_entity_ID 91 */
  --brand-primary:   #0C7ABF;  /* blue, from primary_HTML_color */
  --brand-secondary: #F37937;  /* orange, from secondary_HTML_color */
  --brand-base:      #0D0D0D;
}

[data-brand="woo-latemodel"] { /* World of Outlaws Late Model Series — racing_entity_ID 90 */
  --brand-primary:   #FFDE00;  /* yellow, from primary_HTML_color */
  --brand-secondary: #000000;  /* black, from secondary_HTML_color */
  --brand-base:      #0D0D0D;
}

[data-brand="super-dirtcar"] { /* Super DIRTcar Series — racing_entity_ID 8 */
  --brand-primary:   #EB1C25;  /* red, from primary_HTML_color */
  --brand-secondary: #F4B400;  /* DERIVED — no secondary_HTML_color in DB; flag as placeholder */
  --brand-base:      #0D0D0D;
}
```

## Data Notes

- Brand colors live in `racing_entity.primary_HTML_color` / `secondary_HTML_color` (hex without the `#` prefix in the DB).
- Most series rows have NULL colors and fall back to a generic black/white DIRTcar logo. Only a handful carry their own palette. The three above are the cleanest with usable color.
- Logo URLs also live in `racing_entity.logo_URL`. For the demo, reference the real logo URLs:
  - WoO Sprint: `https://worldofoutlaws.com/wp-content/uploads/2022/11/NOS_SCS_LOGO_FINAL_RGB-e1703214389728.png`
  - WoO Late Model: `https://dv51o5gtpfm3w.cloudfront.net/2026-WoO-LM.png`
  - Super DIRTcar: `https://cdn.dirtcar.com/wp-content/uploads/sites/2/2019/10/30120141/SDCS_Logo.png`
- In production this template would pull these tokens live from the WRG-PublicApi layer, not hardcode them. The hardcoded values here are a demo stand-in for that API.
