# DESIGN PLAN — WRG Re-Skinnable Demo Homepage

_Planning document only. No code. Last updated: 2026-06-22._

---

## 1. Color System

### Neutral / Structural Palette (Constant Across All Skins)

These six values live on `:root` and never change between brands. They are not "dark mode defaults" — they are lifted directly from the physical environment of a Saturday-night dirt track: the pit road asphalt, the raked clay surface, the steel bleacher rail, the haze of moisture and tire smoke under sodium floodlights.

| Token | Value | Role |
|---|---|---|
| `--dc-black` | `#0D0D0D` | Page background, card backgrounds |
| `--dc-clay` | `#3A2418` | Secondary surface (inset panels, footer background, ticker alternate) |
| `--dc-bone` | `#EDE8DD` | Primary body text, card headlines |
| `--dc-track-gray` | `#8C8579` | Muted text, dividers, metadata (dates, bylines, lap counts) |
| `--dc-yellow` | `#F4B400` | Focus rings and structural highlights — shared across all skins regardless of brand |
| `--dc-red` | `#C8102E` | Live-race indicator dot, alert states — structural, not brand-dependent |

The warm undertone of `--dc-clay` and `--dc-track-gray` (both sit in the NCS Y50R family — red/tan bias, not blue-gray) is what separates this palette from a generic dark UI. A cool structural gray would read as fintech. These grays have the color of track-prep dust.

### How Per-Brand Tokens Layer On Top

`--brand-primary` drives:
- Section headings at ≥ 32px Anton (qualifies as WCAG large text)
- Active nav link state
- CTA button background
- Next-race ticker bar background
- Left-edge accent border on standing cards
- Active hero index dot

`--brand-secondary` drives:
- Hover state on CTA buttons (background swap from primary)
- Standings rank numerals (#1, #2, #3) in Anton
- Hero eyebrow text (track name, series label)
- The clay-groove section dividers (see § 5)

`--brand-base` (`#0D0D0D` for all three skins) matches `--dc-black`. It is defined per-brand for template completeness, but since it is identical across all three, no page surface or card background changes when the skin swaps. The skin change is felt in accent, not in structure.

### WCAG AA Contrast Check

Body text is always `--dc-bone` (`#EDE8DD`, relative luminance 0.824) on `--dc-black` (`#0D0D0D`, luminance 0.006).
**Contrast ratio: 15.5:1. Passes AA at all text sizes (threshold: 4.5:1).**

Brand primaries appear only as heading and accent colors. At ≥ 32px Anton these qualify as WCAG "large text," requiring a lower threshold of 3:1.

| Skin | `--brand-primary` | Contrast on `--dc-black` | Large text AA (≥ 3:1) |
|---|---|---|---|
| woo-sprint | `#0C7ABF` blue | 4.4:1 | ✓ Pass |
| woo-latemodel | `#FFDE00` yellow | 15.2:1 | ✓ Pass |
| super-dirtcar | `#EB1C25` red | 4.35:1 | ✓ Pass |

**Constraint to document in CSS:** `#EB1C25` at 4.35:1 passes large-text AA (3:1) but falls just below normal-text AA (4.5:1). The super-dirtcar `--brand-primary` must never be used for body-weight text. Body copy is always `--dc-bone`. Enforce with a CSS comment on the `[data-brand="super-dirtcar"]` block.

---

## 2. Typography

### Faces and Rationale

**Display / Headlines: Anton**
Anton is a single-weight condensed ultra-bold grotesque designed for poster-scale impact. Its character is the vinyl block lettering applied to a sprint car's quarter panel at the print shop — immediate, industrial, zero editorial nuance. It is not Bebas Neue (too streetwear-smooth) and not Impact (too internet-meme). Anton has a slightly irregular stroke weight and a flat-topped geometry that reads as screen-printed signage. Use exclusively in uppercase — mixed-case Anton softens the industrial quality and starts to read as a European lifestyle brand. Never set at sizes below 24px.

**Labels / Nav / Eyebrows: Oswald**
Oswald is a compressed variable-weight sans. Its proportions mean a full race name ("Knoxville Nationals") fits a 160px nav column without wrapping. It has the feel of a printed bracket sheet or a scoreboard header: utilitarian, precise. Its tight tracking at small sizes distinguishes it cleanly from Anton (which only operates at headline scale), so the type hierarchy never collapses. Constrained to label contexts — 14px and below, uppercase, generous letter-spacing. Not used at body-copy size where its compression becomes a legibility liability on dark backgrounds.

**Body Copy: Inter**
Body copy must not compete with the display faces or with photography. Inter's hyper-legibility at 16px/26px line-height on dark backgrounds is a structural requirement. Its generous x-height and open apertures maintain readability at the lower contrast levels that occur when a brand-tinted overlay is present. The popularity of Inter is an irrelevant objection: the real question is whether it is the right tool, and for low-ambient-contrast dark-background reading, it is.

**Stats / Timing / Countdown: JetBrains Mono**
Monospace is mandatory for any column of numbers that must align vertically — standings points, lap counts, race-day countdown segments. JetBrains Mono has distinct numeral forms (unambiguous 0 vs. O, 1 vs. l) that matter at timing-board sizes, and its slightly wide character cells prevent digits from crowding at 56px countdown scale. It reads as an instrumentation panel, not a code editor. Not Roboto Mono (too neutral) and not Space Mono (too retro-tech for a live-data feel).

### Type Scale

| Role | Family | Size | Line-height | Weight | Tracking | Transform |
|---|---|---|---|---|---|---|
| Hero headline | Anton | 80px | 88px | 400 | −0.5px | uppercase |
| Section heading | Anton | 48px | 52px | 400 | −0.25px | uppercase |
| Card heading | Anton | 28px | 32px | 400 | 0 | uppercase |
| Eyebrow / label | Oswald | 12px | 16px | 600 | 2px | uppercase |
| Nav item | Oswald | 14px | 1 | 500 | 1px | uppercase |
| Body copy | Inter | 16px | 26px | 400 | 0 | none |
| Body small / meta | Inter | 14px | 22px | 400 | 0 | none |
| Stat (hero countdown) | JetBrains Mono | 56px | 56px | 700 | 0 | none |
| Stat (standings) | JetBrains Mono | 24px | 28px | 400 | 0 | none |
| Ticker / timing data | JetBrains Mono | 13px | 1 | 400 | 0.5px | uppercase |

---

## 3. Layout — Three-Layer Depth System

### The Model

The entire page is built around a three-layer depth stack. Each layer has a specific function, and the stack only makes sense if all three are present and doing different jobs.

```
Z-AXIS DEPTH STACK (viewer's perspective — front to back)

  ┌─────────────────────────────────────────────────────────────────────┐
  │  LAYER 1 — STATIC FRAME             position: fixed, z-index: 30   │
  │  Two components: (A) left rail — permanent DATA (schedule,          │
  │  standings, never collapses); (B) top pill — adaptive NAVIGATION    │
  │  (full at rest, collapses to hamburger on scroll). Different edges, │
  │  different jobs. Both z-index 30; pill starts at left: 320px.       │
  └─────────────────────────────────────────────────────────────────────┘
          ↕  Layer 3 content scrolls through this space  ↕
  ┌─────────────────────────────────────────────────────────────────────┐
  │  LAYER 3 — SCROLLING CONTENT        normal flow, z-index: 10       │
  │  All page content. Moves between Layer 1 and Layer 2.               │
  │  Hero, floating blocks, podium, stats, news.                        │
  └─────────────────────────────────────────────────────────────────────┘
          ↕  fixed background visible in gaps between content  ↕
  ┌─────────────────────────────────────────────────────────────────────┐
  │  LAYER 2 — FIXED GRADED BACKGROUND  position: fixed, z-index: 0   │
  │  The permanent backdrop. Race-action photo, color-grade wash.       │
  │  Never moves. Provides atmospheric depth and is the subject of      │
  │  the floating-blocks section where Layer 3 intentionally thins.     │
  └─────────────────────────────────────────────────────────────────────┘
```

### Layer 1 — Static Frame _(IndyCar)_

Layer 1 has two distinct components that coexist on desktop, occupying different edges of the viewport and holding different types of content.

#### Division of labor

| Component | Position | Job | State |
|---|---|---|---|
| **(A) Left rail** | `left: 0`, `width: 320px`, full height | DATA — schedule, standings | Permanent. Never collapses. |
| **(B) Top pill header** | `left: 320px` to `right: 0`, `top: 16px` | NAVIGATION — site nav + CTA | Full at scroll 0. Collapses on scroll. Always pinned. |

These are not redundant. The rail answers "what is happening this season." The pill answers "where do I go on this site." They hold different things (data vs. nav links), occupy different edges (left vs. top), and respond to different triggers (season changes vs. user scroll). The one shared element — a "Buy Tickets" CTA present in both — is intentional commercial design: the purchase action should always be visible, not a navigation mistake.

#### (A) Left Rail — Permanent Data Frame

`position: fixed`, `left: 0`, `width: 320px`, `height: 100vh`, z-index 30. Background `--dc-black`, fully opaque. Contents, top to bottom:

- Series logo (top)
- Upcoming races list: next 5 races, date and track name. Next race carries a `--brand-primary` dot indicator.
- Points leaders snapshot: compact P1–P3 list, driver name + JetBrains Mono points
- "Buy Tickets" CTA, pinned to rail bottom

Primary nav links are **not** in the rail — they live in the top pill. The rail holds season data that belongs in a permanent location because it changes on a weekly cadence (new results, updated standings), not in response to user action. It never changes state.

#### (B) Top Pill Header — Adaptive Navigation Frame _(IndyCar)_

`position: fixed`, `left: 320px`, `right: 0`, `top: 16px`, z-index 30. Does not overlap the left rail. Two states:

**Expanded (scroll position 0):** A pill-shaped bar spanning the right content column. Content when live: `[WATCH LIVE ●]  SCHEDULE · STANDINGS · DRIVERS · MEDIA  [TICKETS]`. Content when dormant: `SCHEDULE · STANDINGS · DRIVERS · MEDIA  [TICKETS]`. WATCH LIVE anchors the left edge; nav links fill the center (Oswald, 14px, 500 weight, uppercase, 1px tracking); TICKETS is a pill-shaped CTA at the right. Background `--dc-black` at ~90% opacity with `backdrop-filter: blur(8px)`.

**Collapsed (on scroll past hero):** The pill contracts. Only the four nav links collapse — they fade to `opacity: 0` and `max-width: 0`, then move into the hamburger drawer. WATCH LIVE and TICKETS persist. Content when live: `[WoO] [WATCH LIVE ●] [TICKETS] [☰]`. Content when dormant: `[WoO] [TICKETS] [☰]`. The compact cluster is pinned at `top: 16px`.

**What collapses vs. what persists:** The nav links (SCHEDULE / STANDINGS / DRIVERS / MEDIA) are the only element that collapses. WATCH LIVE and TICKETS are persistent across both pill states — they never disappear on scroll. The WoO logomark is a collapsed-only decorative element (aria-hidden).

**WATCH LIVE button:** Appears when `const isLive = true`. In production this flag is set from the WRG-PublicApi / DIRTVision live feed — the button only renders during active broadcasts. Visibility is governed solely by live status, not scroll state. Button styling uses `--brand-primary` as a tint (dark background with primary-colored border). The live dot is always `--dc-red` — red is the structural "live" signal across all skins and is never brand-adaptive. Under `prefers-reduced-motion: no-preference`, the dot pulses `opacity: 1 → 0.25 → 1` at 1.2s infinite. Under reduced-motion, the dot is visible and static (animation paused, not removed). `tabindex` is set once at page init based on `isLive` and is not managed by the scroll-state handler.

The transition back to collapsed-on-scroll restores the IndyCar-derived interaction that was removed in the previous draft. It was removed then because the pill header held only chrome; now that nav and data are separated across two Layer-1 components, the pill's collapse is meaningful — it signals that the user has moved from "landing" to "browsing."

**Mobile (< 768px):** The two-component Layer 1 is a **desktop-only concept**. On mobile, the left rail and the top pill merge into a single full-width `position: fixed` top bar: logo left, hamburger right, 56px tall. Nav, schedule, and standings all move into the same full-screen `<dialog>` drawer. There is one header on mobile, not two.

**iOS Safari note:** `background-attachment: fixed` does not work on iOS Safari; Layer 2 scrolls with the document. The depth model degrades gracefully to a two-layer layout on mobile. This is a desktop-primary concept.

### Layer 2 — Fixed Graded Background _(Thibaut Courtois + Salzburgring)_

`position: fixed, inset: 0`, z-index 0. A single full-viewport race-action photograph — a car at speed on a dirt oval throwing up clay roost, shot at night under floodlights. This image never moves.

**Load-failure fallback:** `.layer-bg` carries a token-based warm gradient (`--dc-clay` → `--dc-black`) as its `background` property. If the photo URL fails to load, the `::before` pseudo-element has no visible content and the gradient shows through. The page never goes blank.

**Three-part treatment (outermost layer last):**

1. **Photo layer** _(Salzburgring)_: `::before` pseudo-element with `background-image` (race photo) + `filter: sepia(0.75) saturate(0.55) brightness(0.60)`. `sepia()` and `saturate()` work together — sepia alone leaves residual chroma; the saturation pull kills it. The combined result: a vivid race photo pushed into warm, desaturated, moody brown-on-near-black. The filter applies only to `::before`; the vignette (`::after`) is a separate layer and is unaffected. This unifies any incoming photo into the dirt-track palette without requiring consistent source photography.

2. **Edge vignette**: `::after` pseudo-element with a radial gradient — edges darkened ~55–82% black opacity, center transparent. Provides contrast headroom for Layer 3 text and cards regardless of where they sit over Layer 2.

Layer 2 is most visible in the floating-blocks section of Layer 3, where content deliberately thins out and the background shows through between and around the blocks. In all other sections, Layer 2 is largely obscured by Layer 3 card backgrounds — its presence there is atmospheric depth, not primary visual content. The floating-blocks section is the proof-of-concept for the three-layer model; without it, Layer 2 would be decorative.

### Layer 3 — Scrolling Content

Normal document flow, z-index 10. On desktop: `margin-left: 320px`. Sections, top to bottom:

**1. Hero — Most Recent Winner Carousel** _(USA Shooting)_

Content rule: each slide presents the winner of one of the 3–4 most recently completed races. Slide content: full-bleed driver photo, Anton headline (driver name), Oswald eyebrow (race name · track name), CTA "VIEW RESULTS." The most recent win leads. Auto-advances every 5 seconds. Index dots below center.

Each hero slide carries its own two-part overlay, matching Layer 2's visual language:
- Local duotone color-grade (same technique as Layer 2, applied to the slide photo) — visual continuity across the depth stack
- `--brand-primary` directional gradient (~25°, lower-left origin, ~40% opacity fading to transparent upper-right) for headline legibility

The hero is the one Layer-3 section that intentionally echoes Layer 2. The viewer sits in front of a night-race photo (Layer 2) and scrolls through slides of night-race winners (Layer 3) treated the same way.

**2. Floating Feature Blocks** _(Thibaut Courtois)_

Two or three content blocks floating over visible Layer-2 background, with no section background color of their own. Layer 2 shows through in the gaps around and between the blocks. Blocks alternate: left-aligned (occupying ~55% of the content column width) then right-aligned, etc. Each block carries a `--dc-clay` background at 90% opacity for readability over the background image.

Content: race-week editorial — "This Week at [Track Name]" format with a short blurb and a link.

**Critical constraint:** the Layer-2 background photo must depict the same track or event as the floating blocks' copy. The background is the subject of what you are reading, not decorative atmosphere. If the copy says "Knoxville Nationals" and the background is an unrelated car-blur shot, the section collapses to a generic parallax gesture. This is the section that proves the depth model is deliberate rather than stylistic.

**3. Standings — Season Leaders** _(Formula 1)_

P1/P2/P3: full-bleed driver photography edge-to-edge within each card. Over each photo: a bottom-anchored `--brand-primary` gradient wash (~50% opacity, bottom two-thirds of the card) providing text legibility. Rank numeral in Anton + `--brand-secondary`, driver name, JetBrains Mono points value — positioned over the wash. No separate text-box background; the brand wash is the legibility layer. P1 card is tallest. P4–P5 shown as compact list rows below the three photo cards.

**4. Season Stats — Eclectic Cluster** _(SquadEasy)_

Section heading: "SEASON BY THE NUMBERS" (Anton 48px, Landmark Settle animation — see § 4). Six stat elements (Laps Led, Season Wins, Total Events, Miles of Racing, Heat Race Wins, Podiums) arranged off-grid: each element is `position: absolute` within a `position: relative` container sized to the cluster. Elements vary in visual weight — one large central figure (56px JetBrains Mono), surrounding elements at 32px and 24px. Some carry a slight rotation (–3° to +4° via `transform: rotate()`). The arrangement reads as stat stickers on a car's door panel, not a data table or a marketing grid.

Count-up animation fires when the cluster scrolls into view (see § 4).

**5. Latest News** _(NHL / Juventus)_

A horizontally-scrolling card row on `--dc-black`. Cards advance left-to-right through a fixed-height track driven by scroll position and explicit left/right arrow controls. Each card: full photo top half, Oswald eyebrow + Anton headline + Inter metadata bottom half. `--brand-primary` border on focus/hover.

Navigation requirements: arrow controls are focusable HTML elements (not CSS-only); keyboard arrow keys advance cards; pointer drag is supported; `scroll-snap-type` ensures cards always land edge-aligned, never mid-card. Under reduced-motion: horizontal scroll still operates (it is content navigation, not decoration) — only smooth-scroll easing is suppressed; scroll snaps immediately instead of gliding.

**6. Featured Drivers** _(new)_

A grid of top/featured drivers for the current series. Each card: driver photo (fills card top), driver name in Anton, car number in JetBrains Mono (large, `--brand-primary`), team or car name in Oswald (small). Photos are desaturated (grayscale) at rest and resolve to full color via the Settle-In Reveal as each card enters the viewport. This is the primary showcase of the Settle-In animation — the grayscale start directly echoes the Layer-2 duotone treatment, extending the site's monochrome-to-brand visual logic from background to content. `--brand-primary` accent on hover border.

Data source: in production, driver roster comes from the WRG-PublicApi (driver / racing_entity tables). Demo uses hardcoded placeholder data. Brand tokens apply as elsewhere.

**7. Footer** _(full-width, below both columns)_

Full-width `--dc-clay` background. Three-column nav (Races, Series, Media), series logo, social icon row, legal text in `--dc-track-gray`. No animation.

### ASCII Wireframe

```
DESKTOP — THREE-LAYER DEPTH STACK
══════════════════════════════════════════════════════════════════════════════════════

 LAYER 1A: Left Rail            LAYER 1B: Top Pill (pos:fixed, left:320px, top:16px, z:30)
 (pos:fixed, left:0, z:30)
                                ╭──────────────────────────────────────────────────────╮
                                │  SCHEDULE  STANDINGS  DRIVERS  MEDIA  [TICKETS ▶]   │ ← expanded
 ╔════════════════════════╗     ╰──────────────────────────────────────────────────────╯
 ║                        ║    ┌──────────────────────────────────────────────────┐
 ║  ██ SERIES LOGO ██     ║    │                                                  │   (LAYER 3)
 ║                        ║    │  ░░░░░░░░░░░░░░░░ HERO CAROUSEL ░░░░░░░░░░░░░  │
 ║                        ║    │  ░░  driver photo                             ░  │
 ║  ─── UPCOMING RACES ── ║    │  ░░  + duotone color-grade (matches Layer 2)  ░  │
 ║  ● JUL  9  Knoxville   ║    │  ░░  + brand-primary legibility gradient      ░  │
 ║    JUL 16  Eldora,OH   ║    │  ░░                                           ░  │
 ║    JUL 23  Lernerv.    ║    │  ░░  SERIES LABEL · TRACK, STATE              ░  │
 ║    JUL 30  Haubstadt   ║    │  ░░                                           ░  │
 ║    AUG  6  W.Grove     ║    │  ░░  WINNER: DRIVER NAME                     ░  │
 ║                        ║    │  ░░  RACE NAME                               ░  │
 ║  ─── POINTS LEADERS ── ║    │  ░░  [  VIEW RESULTS  ]        ○ ○ ● ○       ░  │
 ║  1  DRIVER   1,240 pts ║    │                                                  │
 ║  2  DRIVER   1,190 pts ║    │  [pill collapses as user scrolls past hero]      │
 ║  3  DRIVER   1,155 pts ║    │                                                  │
 ║                        ║    │    ╭────────────────────╮                        │
 ║                        ║    │    │  ○  [● WATCH LIVE]  [TICKETS]  ☰  │ collapsed │
 ║  [  BUY TICKETS  ]     ║    │    ╰────────────────────╯                        │
 ╚════════════════════════╝    │                                                  │
                                │  ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ CLAY GROOVE ▓▓▓▓▓▓▓▓▓▓▓▓▓  │
                                │                                                  │
                                │  ← Layer 2 visible here (bg shows through) →    │
                                │                                                  │
                                │   ┌───────────────────────────────┐             │
                                │   │  RACE WEEK FEATURE    (left)  │             │
                                │   └───────────────────────────────┘             │
                                │              ┌───────────────────────────────┐   │
                                │              │  FEATURE BLOCK       (right)  │   │
                                │              └───────────────────────────────┘   │
                                │                                                  │
                                │  ← Layer 2 still visible here →                 │
                                │                                                  │
                                │  ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ CLAY GROOVE ▓▓▓▓▓▓▓▓▓▓▓▓▓  │
                                │                                                  │
                                │  SEASON LEADERS                                  │
                                │         ┌──────────────────────────────┐         │
                                │         │  [FULL-BLEED DRIVER PHOTO]   │         │
                                │  ┌────┐ │  --brand-primary wash        │ ┌────┐  │
                                │  │[P] │ │  #1 DRIVER NAME  1,240 PTS   │ │[P] │  │
                                │  │ #2 │ └──────────────────────────────┘ │ #3 │  │
                                │  └────┘                                   └────┘  │
                                │  4. ── DRIVER ──────────────────── 987 pts        │
                                │  5. ── DRIVER ──────────────────── 943 pts        │
                                │                                                  │
                                │  ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ CLAY GROOVE ▓▓▓▓▓▓▓▓▓▓▓▓▓  │
                                │                                                  │
                                │  SEASON BY THE NUMBERS                           │
                                │                                                  │
                                │     347 LAPS LED    ╲   12 WINS                  │
                                │                      ╲                           │
                                │         2,481 MILES ──○── 89 EVENTS              │
                                │                      ╱                           │
                                │     PODIUMS  ───────╱   HEAT WINS                │
                                │                                                  │
                                │  ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ CLAY GROOVE ▓▓▓▓▓▓▓▓▓▓▓▓▓  │
                                │                                                  │
                                │  LATEST NEWS  [←] [→]                           │
                                │  ┌──────────┐ ┌──────────┐ ┌──────────┐ ┌──·   │
                                │  │ [PHOTO]  │ │ [PHOTO]  │ │ [PHOTO]  │ │      │  ← horizontal
                                │  │ EYEBROW  │ │ EYEBROW  │ │ EYEBROW  │ │      │    scroll row
                                │  │ HEADLINE │ │ HEADLINE │ │ HEADLINE │ │      │    (Juventus)
                                │  └──────────┘ └──────────┘ └──────────┘ └──·   │
                                │                                                  │
                                │  ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ CLAY GROOVE ▓▓▓▓▓▓▓▓▓▓▓▓▓  │
                                │                                                  │
                                │  FEATURED DRIVERS                                │
                                │  ┌────────┐ ┌────────┐ ┌────────┐ ┌────────┐   │
                                │  │ [gray] │ │ [gray] │ │ [gray] │ │ [gray] │   │  ← Settle-In
                                │  │→ color │ │→ color │ │→ color │ │→ color │   │    Reveal
                                │  │ DRIVER │ │ DRIVER │ │ DRIVER │ │ DRIVER │   │    (grayscale
                                │  │  #07   │ │  #17   │ │  #83   │ │   #1   │   │    → color)
                                │  └────────┘ └────────┘ └────────┘ └────────┘   │
                                └──────────────────────────────────────────────────┘

┌──────────────────────────────────────────────────────────────────────────────────────┐
│  ██ LOGO ██   RACES · SERIES · MEDIA   [FB][X][IG][YT]    © 2026 World Racing Group  │
└──────────────────────────────────────────────────────────────────────────────────────┘

LAYER 2 (pos:fixed z:0) — spans full viewport, behind all other content
══════════════════════════════════════════════════════════════════════════════════════
▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
▒▒  Race-action photo: car at speed on dirt oval, night, floodlights.              ▒▒
▒▒  Treatment: (1) ::before — photo + filter:sepia(0.75) saturate(0.55)          ▒▒
▒▒                  brightness(0.60) → warm desaturated moody brown grade         ▒▒
▒▒              (2) ::after  — edge vignette, radial gradient ~55-82% black       ▒▒
▒▒  Fallback: --dc-clay→--dc-black gradient if photo URL fails to load.           ▒▒
▒▒  Most visible: floating-blocks section (Layer 3 content thins intentionally).  ▒▒
▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒

MOBILE (< 768px)
══════════════════════════════════════════════════════════════════════════════════════
┌──────────────────────────────────────────────────────────────────────────────────────┐
│  [LOGO]                                                                  [☰]         │  ← Layer 1: 56px top bar
└──────────────────────────────────────────────────────────────────────────────────────┘
  Layer 1 rail → full-screen ☰ drawer (nav, schedule, standings).
  Layer 2 → background-attachment: fixed degrades on iOS Safari to scroll;
            treated as a static banner at page top. Depth model becomes two-layer.
  Layer 3 → full-width single column below the 56px bar.
```

---

## 4. Motion

**Motion applies to Layer 3 by default.** The single Layer-1 exception is the top pill header (Layer 1B), which transitions between expanded and collapsed states on scroll — a functional layout state change, not a decorative reveal. Layer 1A (the left rail) and Layer 2 (the fixed background) never animate; animating them would contradict their permanently-present quality.

**Default state: no motion.** All animations are wrapped in `@media (prefers-reduced-motion: no-preference)`. Motion is an enhancement for users who have not requested reduced motion, not a default that is removed for those who have. Elements that start hidden must be set immediately visible in the reduced-motion branch — an invisible element that never reveals is a content failure.

**Animation vocabulary — four named styles:**

- **Landmark Settle** _(Legends Lounge)_: Major section headings and hero text. Duration 700–900ms, `translateY(40px → 0)` + opacity, `cubic-bezier(0.16, 1, 0.3, 1)`. The spring-style curve decelerates emphatically — typographic elements arrive with gravitational weight. Standalone landmarks only; not used for cards.

- **Settle-In Reveal** _(Legends Lounge)_: Card-level reveal. As a card enters the viewport: `filter: grayscale(1) → grayscale(0)` (color resolves in) combined with `translateY(16px → 0)` + `opacity(0 → 1)`. Duration 700ms, same `cubic-bezier(0.16, 1, 0.3, 1)` curve as the Landmark Settle — both feel weighty, not snappy. Cards stagger ~90ms apart. Applied to: Featured Drivers grid (primary showcase) and Standings podium (existing P2→P1→P3 order retained). Under reduced-motion: cards appear immediately in full-color final state, no transition.

- **Horizontal Card Scroll** _(Juventus)_: News section. Cards advance horizontally through a fixed-height track via scroll, arrow controls, and drag. Snaps to card edges (`scroll-snap-type`). Keyboard-accessible via focusable arrow controls and arrow keys. Under reduced-motion: scroll still operates (it is navigation); smooth-scroll easing is suppressed — cards snap immediately.

- **Number Count-Up** _(SquadEasy)_: Season stats cluster. 0 → final value over 1200ms `ease-out` via `requestAnimationFrame`. 100ms stagger between elements.

Motion fires only where content density justifies it. Sparse sections (floating blocks, footer) stay still.

| Animation | Element | Style | Trigger | Behavior |
|---|---|---|---|---|
| Hero text reveal | Eyebrow, headline, CTA | Landmark Settle | `DOMContentLoaded` | Eyebrow 0ms delay, headline 80ms, CTA 180ms. 400ms each (faster variant — page load, not scroll). `translateY(16px → 0)` + opacity. |
| Hero carousel crossfade | Slide images | — | Auto, 5s interval | `opacity: 0 → 1`. 600ms `ease-in-out`. No translate — push-transitions on photography read as slideshow software. |
| Hero index dot expand | Dot indicators | — | Carousel advance | Active dot width 6px → 10px (pill shape). 200ms. |
| Section heading settle | Anton `<h2>` in Layer 3 | Landmark Settle | `IntersectionObserver` (80%) | `translateY(40px → 0)` + opacity. 800ms `cubic-bezier(0.16, 1, 0.3, 1)`. |
| Floating block slide-in | Feature blocks (left / right) | — | `IntersectionObserver` (60%) | Left block: `translateX(−32px → 0)`. Right block: `translateX(32px → 0)`. 500ms `ease-out`. Horizontal translate distinguishes these from card reveals. |
| Standings podium reveal | P1/P2/P3 photo cards | Settle-In Reveal | `IntersectionObserver` (80%) | P2 (0ms), P1 (90ms), P3 (180ms) — center arrives last. `grayscale(1→0)` + `translateY(16px→0)` + opacity. 700ms `cubic-bezier(0.16, 1, 0.3, 1)`. |
| Featured Drivers reveal | Driver grid cards | Settle-In Reveal | `IntersectionObserver` (70%) | Cards stagger 90ms apart, left-to-right. `grayscale(1→0)` + `translateY(16px→0)` + opacity. 700ms `cubic-bezier(0.16, 1, 0.3, 1)`. Primary showcase of the style. Under reduced-motion: full-color, immediate. |
| News horizontal scroll | News card row | Horizontal Card Scroll | User scroll / arrow controls | Cards advance left-to-right. Keyboard: focusable `[←][→]` controls + arrow keys. Drag: pointer-based. Snap: `scroll-snap-type: x mandatory`. Under reduced-motion: scroll operates normally; `scroll-behavior: smooth` suppressed — snaps immediately. |
| Top pill collapse | Pill header (Layer 1B) | — | `IntersectionObserver` on hero bottom edge | Nav links fade to `opacity: 0` + `max-width: 0`; nav links collapse into drawer. WATCH LIVE + TICKETS persist in both states. 250ms `ease`. Under reduced-motion: snaps instantly via `transition: none`. Tapping hamburger re-expands. |
| Schedule live dot pulse | Next-race indicator in Layer 1A | — | CSS `animation`, if < 24h to race | `opacity: 1 → 0.3 → 1`, 1.2s infinite. `animation-play-state: paused` under reduced-motion — dot stays visible, static. |
| Stat count-up | Numbers in eclectic cluster | Number Count-Up | `IntersectionObserver` | Each number animates 0 → final value over 1200ms `ease-out` via `requestAnimationFrame`. 100ms stagger between elements. |

---

## 5. Signature Element: Clay-Groove Section Dividers

Between each major section in Layer 3 (after the hero, after floating blocks, after standings, after stats, after news, after featured drivers), a decorative band ~28px tall acts as a visual chapter break. It is rendered with a CSS `repeating-linear-gradient` at 3° from horizontal, alternating between `--brand-primary` at 70% opacity and `--dc-black` at 100%, with a stripe period of approximately 8px (4px colored stripe, 4px black gap). The wider band and thicker stripes make the raked texture legible as surface detail rather than a hairline. The result reads as shallow parallel grooves — the raked clay surface that a track prep crew cuts into the start-finish straight before the night's racing begins.

This element is:
- **Physically specific to dirt-track racing.** No other motorsport rakes its racing surface. A World of Outlaws viewer knows this texture immediately; a Formula 1 viewer does not.
- **Brand-adaptive.** It uses `--brand-primary` — not `--brand-secondary` — because `woo-latemodel`'s secondary is `#000000`, which is invisible on `--dc-black`. All three brand primaries are bright-on-dark and reliably visible. Groove color by skin: blue (#0C7ABF) for WoO Sprint, yellow (#FFDE00) for WoO Late Model, red (#EB1C25) for Super DIRTcar.
- **Pure CSS.** No images, no SVG, no JavaScript. A `<div aria-hidden="true" class="clay-divider">` with a single background declaration.
- **Textural, not iconographic.** It does not say "dirt track" — it shows it. The difference between an illustration of clay and the visual texture of clay.

The divider does not animate and is not interactive. It is a static material marking, the same way the actual track surface grooves are static on race night.

---

## Self-Critique

### 1. Color System

**What was flagged as potentially generic:** `--dc-bone` (#EDE8DD) as body text against `--dc-black` has the surface appearance of "warm off-white on near-black = premium/artisan brand" — a pattern used by everything from whiskey campaigns to luxury hotels to half the AI-generated design concepts produced in 2024.

**Why it survives here / what was revised:** The warm undertone is a consequence of the physical palette, not an aesthetic choice. `--dc-bone` has the same color family as `--dc-clay` and `--dc-track-gray`. They all sit in the tan/red-ochre range because they are all derived from the same material: track dirt. If this were purely an aesthetic preference, the bone would swap for a cool white. It does not, because a cool white would visually disconnect the body text from the rest of the surface vocabulary. What was revised: an initial structural separator token (`--dc-cool-gray`, a blue-neutral gray) was replaced with `--dc-track-gray` throughout. The warm gray is the correct physical material.

### 2. Typography

**What was flagged as potentially generic:** Anton + Inter is the "heavy display + clean body" pair used by approximately every sports and gaming property since 2018. The initial draft also did not restrict Anton usage sufficiently.

**What was revised:** Two constraints were added that are not generic defaults. First, Anton is uppercase-only in all applications — mixed-case Anton softens its industrial quality and shifts it toward lifestyle-brand territory. Second, Oswald was removed from body-copy contexts (where it had appeared as card subheadings in the initial draft). Oswald at 16px on dark backgrounds compresses legibility. It is now strictly a label and nav face at 14px and below. These constraints are specific to this brief, not standard guidance.

### 3. Layout — Three-Layer Depth System

**What was flagged as potentially generic:** "Fixed background behind scrolling content" is the `background-attachment: fixed` technique from approximately 2012. Putting a persistent sidebar on the left is standard SaaS/documentation UI. Combining them and calling it an "architecture" could be a naming exercise rather than a design decision.

**Why each layer is load-bearing:**

Layer 1 is load-bearing because it holds live data (schedule, standings), not just navigation. A sidebar with only logo and nav links is chrome; one that answers "what race is next and who is leading" before a user scrolls is a data service. The persistent presence of that data is the design argument for Layer 1's existence.

Layer 2 is load-bearing because of the floating-blocks section. That section exists specifically so Layer 2 can be the subject of the content above it — the background photo depicts the same event as the editorial blocks floating in front of it. Without the floating-blocks section, Layer 2 is purely atmospheric and the depth model collapses to a background image that happens to be fixed. The floating-blocks section is the keystone; if it is reduced to a generic parallax gesture during implementation, the architecture loses its justification.

Layer 3 acknowledges the stack: hero slides use the same duotone treatment as Layer 2, creating visual continuity across layers. The viewer is inside the same tonal world from background to foreground.

**Two-component Layer 1 — are the rail and pill genuinely distinct?** The test: does either component do the other's job? The rail holds data (schedule, standings) that updates on a weekly cadence. The pill holds navigation (site sections) and responds to user scroll. The only shared element is the "Buy Tickets" CTA, present in both. This is not an overlap — it is commercial intent: the purchase path should always be visible regardless of which Layer-1 component the user's eye lands on. A developer mis-implementing this would put nav links in the rail or schedule data in the pill; the plan explicitly forbids both. The risk is in implementation discipline, not in the design.

**What was restored:** A pill-shaped header that transforms on scroll (IndyCar) was removed in the previous draft because it held only chrome (logo + nav). Now that schedule and standings data are separated into the permanent rail, the pill's collapse is meaningful — it signals scroll depth, not just "the user scrolled." The same IndyCar interaction pattern, justified by a different division of labor. The interaction was correct before; the content assignment was wrong.

**iOS Safari caveat documented:** The three-layer depth model is explicitly a desktop-primary concept. The Layer-2 fixed-background effect degrades gracefully on mobile. This is noted as a known limitation, not glossed over.

### 4. Motion

**Removal of Stagger Tier:** The Stagger Tier (400ms, `ease-out`, 60ms inter-element) was the most generic entry in the previous plan — the default scroll-reveal vocabulary of every marketing site built since 2019. Removing it and replacing card-level reveals with the Settle-In style achieves two things the Stagger Tier could not: (1) the grayscale-to-color transition carries meaning specific to this design, echoing the Layer-2 duotone treatment rather than just "moving things up"; (2) using the same `cubic-bezier(0.16, 1, 0.3, 1)` curve for both Landmark Settle and Settle-In Reveal creates a coherent motion vocabulary — everything important in this design, large or small, settles with the same deliberate weight. The 90ms card stagger (vs. the removed 60ms) is slower and more considered, not faster and more mechanical. Nothing that relied on the Stagger Tier is left without an animation: Standings podium and Featured Drivers move to Settle-In Reveal; News moves to Horizontal Card Scroll.

**News horizontal scroll — accessibility:** The generic risk is that horizontal scroll is a touch-native pattern that degrades for keyboard and mouse users. Three mitigations: (a) the `[←][→]` arrow controls are focusable `<button>` elements in the DOM, not pseudo-elements or cursor changes; (b) keyboard arrow key navigation is explicit behavior on the control, not a CSS trick; (c) `scroll-snap-type: x mandatory` ensures no card ever lands mid-frame. Under reduced-motion, the scroll mechanism continues to operate — this is correct because the horizontal scroll is content navigation (the user is browsing cards they cannot otherwise reach), not a decorative transition. Only `scroll-behavior: smooth` is suppressed. This applies the same principle as the pill collapse: functional state changes fire regardless of motion preference; easing and transition animations do not.

**Featured Drivers grayscale-to-color — is it load-bearing or decorative?** The test: does the animation make sense if Layer 2 is removed? Layer 2 is a desaturated race photo — the duotone wash strips it of vivid color and replaces it with the warm-gray tonal range of the dirt-track palette. The Settle-In Reveal on driver cards starts each card in that same grayscale state and resolves it to full color as it enters the viewport. The card is arriving from the monochrome world of the backdrop into the brand-color foreground. If Layer 2 were removed and replaced with a plain `--dc-black` background, the grayscale start would be a visual non-sequitur — there would be nothing in the design that motivated it. That dependency is the answer: the reveal is structurally connected to Layer 2's treatment, not a freestanding flourish. It is the one place where the motion vocabulary and the depth architecture intersect.

### 5. Signature Element

**What was flagged as generic:** The first draft proposed a diagonal brand-color geometric slash across the hero using `clip-path`. This is the defining visual cliché of sports and esports design from approximately 2016–2024. Every Nike campaign, every esports team reveal, every motorsport season launch uses this device.

**What was revised:** The diagonal slash as a graphic element was discarded. The hero still uses a directional gradient overlay (lower-left origin, ~25°) — this is structural, it ensures the headline reads against any photography — but it is a gradient fade, not a hard geometric cut. The signature element became the clay-groove section dividers, which are: (a) native to dirt-track racing and only dirt-track racing, (b) invisible to anyone looking for "design flourishes" because they read as material texture, not as a design decision, and (c) technically interesting to implement in pure CSS without images. The sign of a correct signature element is that removing it would make the design feel like it could be any sport. Removing the clay grooves does exactly that.
