# DIRTcar Prototype — WordPress Theme

A custom WordPress theme built from the [static prototype](../../static-prototype) in this
repo. Same layout, same brand-skin system, same CSS/JS — restructured into WP
template parts with a swappable data layer, so it's ready to sit on a real
WordPress install instead of a set of static files.

## What this is

The static prototype was a homepage design/interaction exercise: three-layer
depth model (fixed data rail, collapsing pill nav, scrolling content
channel), a multi-brand skin system for World Racing Group's different
series sites, and a bento-style season-stats section with scroll-triggered
count-ups. This theme is that same page, rebuilt to run inside WordPress.

## Structure

```
dirtcar-prototype/
├── style.css              theme header + all layout/visual CSS (ported as-is)
├── functions.php          theme setup, enqueues, brand-skin Customizer control,
│                           and dirtcar_get_data() — see below
├── header.php              <head>, fixed rail, pill nav, opens <main>
├── footer.php              site footer, dev panel, closes <main>/</body>
├── front-page.php          assembles the homepage from template-parts
├── index.php               fallback template for anything else
├── js/main.js              ported prototype JS (countdown, pill collapse,
│                           hero carousel, nav drawer, count-up animation)
├── assets/photos/          the prototype's demo photos
└── template-parts/
    ├── rail.php             left data rail (schedule + standings)
    ├── pill-header.php      collapsing nav pill
    ├── nav-drawer.php       mobile nav drawer
    ├── hero.php              winner carousel
    ├── floating-blocks.php  featured stories + track-context card
    ├── standings.php         podium + compact list
    ├── season-stats.php     bento stat mosaic
    ├── news.php              horizontal news scroller
    └── dev-panel.php        brand-skin swatcher (WP_DEBUG only)
```

## The data seam

Every dynamic block reads from one function: `dirtcar_get_data()` in
`functions.php`. Right now it returns hardcoded fallback data — the same
placeholder content the static prototype shipped with — so the theme
renders correctly the moment you activate it, no content required.

To wire it to something real, replace the body of that one function with
either:

- `WP_Query` calls against custom post types (races, drivers, news), or
- `wp_remote_get()` calls against the WRG public API, cached in a transient
  (`set_transient( 'dirtcar_data', $data, 15 * MINUTE_IN_SECONDS )`)

No template file needs to change either way — they only read the array
shape `dirtcar_get_data()` returns.

## Brand skin

The static prototype toggled brand via `<html data-brand="...">` and a
DEV MODE swatcher panel. In this theme, brand is a per-site setting exposed
through **Customizer → Site Identity → Brand Skin** — the right place for a
WordPress multisite network where each brand (ASCS, SDS, XOS, WoO Sprint,
WoO Late Model, Super DIRTcar, etc.) is its own sub-site. The DEV MODE panel
is still there for quick visual QA, but gated behind `WP_DEBUG` so it never
ships to real visitors.

## Setup

1. Zip this folder (`dirtcar-prototype/`) and upload via
   **Appearance → Themes → Add New → Upload Theme** (on staging, not live),
   or drop it directly into `wp-content/themes/`.
2. Activate it. The homepage renders immediately using the placeholder data
   — no additional setup required for that part.
3. Assign menus in **Appearance → Menus** (`primary`, `footer-series`,
   `footer-explore`, `footer-connect`) once real destination pages exist.
   Until then, the hardcoded fallback links from the prototype render
   automatically.
4. Set the brand skin in **Customizer → Site Identity** per site.
5. When ready, wire `dirtcar_get_data()` to real content per the section
   above.

## What's intentionally not done yet

- No custom post types / ACF fields registered — the data seam is a plain
  PHP function so it's trivial to swap for ACF fields or a REST call later
  without committing to one approach now.
- No `style-{brand}.css` overrides — brand skins are handled entirely by
  the `[data-brand]` CSS selectors already in `style.css`, unchanged from
  the prototype.
- Homepage only — this doesn't cover schedule, standings, driver, or
  results pages beyond what's shown here.
- Demo photos in `assets/photos/` are the prototype's placeholder WRG
  photos, not final production assets.
