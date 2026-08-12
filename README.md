# DIRTcar Racing — Homepage Prototype & WordPress Theme

A homepage redesign prototype for a World Racing Group motorsports brand
site (World of Outlaws / DIRTcar Racing), built in two stages: a static
HTML/CSS/JS design prototype, then a converted WordPress theme.

**[View the static prototype live](https://wrg-site-demo.vercel.app)** · *(or open `static-prototype/index.html` locally)*

## Two folders

| Folder | What it shows |
|---|---|
| [`static-prototype/`](./static-prototype) | The original design/interaction work — vanilla HTML, CSS, and JS. No build step, no dependencies. Open `index.html` in a browser. Also home to `season-map.html`, a standalone season-route map (see below). |
| [`wp-theme/dirtcar-prototype/`](./wp-theme/dirtcar-prototype) | The same page rebuilt as an installable WordPress theme — PHP template parts, WP nav menus, a Customizer-driven brand-skin setting, and a swappable data layer designed to be pointed at a real API. See its own README for setup. |

They're kept side by side: the static folder is the fastest way
to see the design and interaction work in isolation (open one file, no
WordPress needed); the theme folder is the version meant to run on a WordPress site.

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

## Season route map

[`static-prototype/season-map.html`](./static-prototype/season-map.html) is a
standalone page (linked from the homepage nav as "Season Map") showing the
2026 World of Outlaws season as an animated route on a
[MapLibre GL JS](https://maplibre.org/) map — no build step, no npm
dependencies, MapLibre and map tiles loaded from CDNs at runtime.

- **Two series tabs** (Sprint Car / Late Model) — each with its own pins,
  route line, and stop counter, split from a single dataset by a `series`
  property on each stop.
- **Route line in three parts**: a solid brand-colored line traces the
  season from its first stop through today's most recent race, a short
  dashed grey segment previews the next race, and anything further out is
  pins only.
- **Data loads at runtime** via `fetch('data/woo-season.geojson')`, with a
  loading spinner and an error panel if the fetch fails.

### Regenerating the data

`data/woo-season.geojson` is generated, not hand-written:

```
wrg-series.csv               # raw sample export the query was modeled on (reference only)
build_season_geojson.py      # pulls the live season from MariaDB, writes the GeoJSON
static-prototype/data/woo-season.geojson   # output consumed by season-map.html
```

`build_season_geojson.py` requires `pymysql` and `DB_HOST` / `DB_USER` /
`DB_PASSWORD` / `DB_NAME` (and optionally `DB_PORT`, `SEASON_START`,
`SEASON_END`, `OUTPUT_PATH`) set in the environment:

```
python3 build_season_geojson.py
```

It classifies each stop as `sprint_car` or `late_model` server-side (by
promotion name) and prints the resulting counts on completion.

## Stack

Static prototype: HTML5, vanilla CSS (custom properties for the brand
tokens), vanilla JS (`IntersectionObserver` for scroll effects, no
dependencies). The season map adds MapLibre GL JS (CDN) and a small Python
(`pymysql`) script for the data pipeline above.

WordPress theme: PHP template parts following the standard WP template
hierarchy, `wp_nav_menu()` for navigation, the Customizer API for the
brand-skin setting, and the same CSS/JS ported over unchanged.
