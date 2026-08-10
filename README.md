# DIRTcar Racing — Homepage Prototype & WordPress Theme

A homepage redesign prototype for a World Racing Group motorsports brand
site (World of Outlaws / DIRTcar Racing), built in two stages: a static
HTML/CSS/JS design prototype, then a converted WordPress theme.

**[View the static prototype live](https://wrg-site-demo.vercel.app)** · *(or open `static-prototype/index.html` locally)*

## Why two folders

| Folder | What it shows |
|---|---|
| [`static-prototype/`](./static-prototype) | The original design/interaction work — vanilla HTML, CSS, and JS. No build step, no dependencies. Open `index.html` in a browser. |
| [`wp-theme/dirtcar-prototype/`](./wp-theme/dirtcar-prototype) | The same page rebuilt as an installable WordPress theme — PHP template parts, WP nav menus, a Customizer-driven brand-skin setting, and a swappable data layer designed to be pointed at a real API. See its own README for setup. |

They're kept side by side on purpose: the static folder is the fastest way
to see the design and interaction work in isolation (open one file, no
WordPress needed); the theme folder is the version meant to actually run on
a WordPress site.

## The design

Three-layer depth model:

- **Layer 1 — fixed frames.** A left-hand data rail (next race countdown,
  upcoming schedule, points leaders) and a top nav pill that collapses to a
  compact logo/hamburger cluster on scroll.
- **Layer 2 — a fixed, graded background image** that never scrolls, visible
  through the gaps around floating content.
- **Layer 3 — the scrolling content channel**: winner carousel hero,
  curated "Featured" story blocks + a track-context card, a podium-style
  standings section, a bento-grid season-stats mosaic with scroll-triggered
  count-ups, and a horizontal-scroll news feed.

A `data-brand` token system re-skins the whole page (colors, wordmark) per
World Racing Group series — World of Outlaws Sprint, World of Outlaws Late
Model, Super DIRTcar — from one shared codebase, which is what the
WordPress theme's Customizer brand-skin setting builds on.

## Stack

Static prototype: HTML5, vanilla CSS (custom properties for the brand
tokens), vanilla JS (`IntersectionObserver` for scroll effects, no
dependencies).

WordPress theme: PHP template parts following the standard WP template
hierarchy, `wp_nav_menu()` for navigation, the Customizer API for the
brand-skin setting, and the same CSS/JS ported over unchanged.

<!-- Deployment test -->
