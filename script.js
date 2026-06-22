/* ============================================================
   DIRTcar Racing Demo — Shell JS
   Scope: countdown tick, pill collapse, nav drawer.
   No content-section logic yet.
   ============================================================ */

'use strict';

// DEMO: flip to false for the dormant state.
// In production this comes from the WRG-PublicApi / DIRTVision live feed.
const isLive = true;


// ─── Countdown timer ─────────────────────────────────────────
// Placeholder target: Knoxville Nationals, July 9 2026, 7:00 PM CDT
// Production: target date comes from WRG-PublicApi next-event endpoint.

const RACE_DATE = new Date('2026-07-09T19:00:00-05:00');

const cdDays  = document.getElementById('cd-days');
const cdHours = document.getElementById('cd-hours');
const cdMins  = document.getElementById('cd-mins');
const cdSecs  = document.getElementById('cd-secs');

function pad(n) {
  return String(n).padStart(2, '0');
}

function tickCountdown() {
  const diff = RACE_DATE - Date.now();

  if (diff <= 0) {
    cdDays.textContent  = '00';
    cdHours.textContent = '00';
    cdMins.textContent  = '00';
    cdSecs.textContent  = '00';
    return;
  }

  cdDays.textContent  = pad(Math.floor(diff / 86400000));
  cdHours.textContent = pad(Math.floor((diff % 86400000) / 3600000));
  cdMins.textContent  = pad(Math.floor((diff % 3600000) / 60000));
  cdSecs.textContent  = pad(Math.floor((diff % 60000) / 1000));
}

tickCountdown();
setInterval(tickCountdown, 1000);


// ─── Pill header collapse ─────────────────────────────────────
// IntersectionObserver watches #scroll-sentinel (immediately after the hero
// section). When sentinel exits the viewport (user scrolled past hero), pill
// collapses to compact logo + hamburger + CTA cluster.
//
// Pill collapse is a functional layout state, not a decorative animation.
// It fires regardless of prefers-reduced-motion. Only the 250ms transition
// is suppressed under reduced-motion (handled in CSS, not JS).

const pillHeader   = document.getElementById('pill-header');
const pillNav      = pillHeader.querySelector('.pill-nav');
const pillNavLinks = pillNav.querySelectorAll('.pill-nav__link');
const hamburgerBtn = pillHeader.querySelector('.pill-header__hamburger');
const watchLiveBtn = document.getElementById('pill-watch-live');
const sentinel     = document.getElementById('scroll-sentinel');

// Apply live class once at init — CSS does the rest
if (isLive) pillHeader.classList.add('pill-header--has-live');

// WATCH LIVE persists across both pill states — tabindex governed by isLive, not scroll state
if (watchLiveBtn && isLive) watchLiveBtn.removeAttribute('tabindex');

function setPillCollapsed(collapsed) {
  pillHeader.classList.toggle('pill-header--collapsed', collapsed);

  if (collapsed) {
    // Remove nav from accessibility tree — nav links collapse into the drawer
    pillNav.setAttribute('aria-hidden', 'true');
    pillNavLinks.forEach(link => link.setAttribute('tabindex', '-1'));
    // Restore hamburger to tab order
    hamburgerBtn.removeAttribute('tabindex');
    hamburgerBtn.setAttribute('aria-expanded', 'false');
  } else {
    // Restore nav to accessibility tree
    pillNav.removeAttribute('aria-hidden');
    pillNavLinks.forEach(link => link.removeAttribute('tabindex'));
    // Remove hamburger from tab order (visually hidden)
    hamburgerBtn.setAttribute('tabindex', '-1');
  }
  // WATCH LIVE tabindex is not managed here — set once at init based on isLive
}

const sentinelObserver = new IntersectionObserver(
  ([entry]) => {
    // On mobile the pill is always in flat-bar mode — skip the collapse toggle
    if (window.innerWidth < 768) return;
    setPillCollapsed(!entry.isIntersecting);
  },
  { threshold: 0 }
);

sentinelObserver.observe(sentinel);

// On resize: reset pill state to match new layout context
let resizeTimer;
window.addEventListener('resize', () => {
  clearTimeout(resizeTimer);
  resizeTimer = setTimeout(() => {
    if (window.innerWidth < 768) {
      // Mobile: pill is always in flat-bar state
      pillHeader.classList.remove('pill-header--collapsed');
      pillNav.setAttribute('aria-hidden', 'true');
      hamburgerBtn.removeAttribute('tabindex');
    } else {
      // Desktop: re-evaluate sentinel position and restore nav
      const rect = sentinel.getBoundingClientRect();
      setPillCollapsed(rect.bottom <= 0);
    }
  }, 100);
});

// Mobile init: set correct tab-order state on load
(function initLayout() {
  if (window.innerWidth < 768) {
    pillNav.setAttribute('aria-hidden', 'true');
    hamburgerBtn.removeAttribute('tabindex');
  }
})();


// ─── Nav drawer ───────────────────────────────────────────────
// showModal() creates a top-layer modal with native focus trap and Escape key.
// Backdrop click detection uses getBoundingClientRect to check if the click
// landed outside the dialog content box.

const navDrawer   = document.getElementById('nav-drawer');
const drawerClose = navDrawer.querySelector('.nav-drawer__close');

hamburgerBtn.addEventListener('click', () => {
  navDrawer.showModal();
  hamburgerBtn.setAttribute('aria-expanded', 'true');
});

drawerClose.addEventListener('click', closeDrawer);

// Backdrop click: dialog fills the viewport but click on ::backdrop fires
// on the dialog element itself at coordinates outside the content box
navDrawer.addEventListener('click', e => {
  const rect = navDrawer.getBoundingClientRect();
  const outside =
    e.clientX < rect.left  ||
    e.clientX > rect.right ||
    e.clientY < rect.top   ||
    e.clientY > rect.bottom;
  if (outside) closeDrawer();
});

// Sync aria-expanded when dialog is dismissed by the Escape key
navDrawer.addEventListener('close', () => {
  hamburgerBtn.setAttribute('aria-expanded', 'false');
});

// Close drawer links: dismiss drawer on nav link click (for anchor nav)
navDrawer.querySelectorAll('.nav-drawer__link').forEach(link => {
  link.addEventListener('click', closeDrawer);
});

function closeDrawer() {
  navDrawer.close();
  hamburgerBtn.setAttribute('aria-expanded', 'false');
  hamburgerBtn.focus();
}


// ─── Hero carousel ──────────────────────────────────────────
// PRODUCTION: slide data from WRG-PublicApi race-results endpoint.
// Auto-advances every 5s. Crossfade via CSS opacity transition (decorative —
// suppressed under reduced-motion). Auto-advance is NOT suppressed: navigating
// slides is content interaction, not decoration.

const heroSlides = document.querySelectorAll('.hero__slide');
const heroDots   = document.querySelectorAll('.hero__dot');
const SLIDE_MS   = 5000;
let heroIndex    = 0;
let heroTimer;

function goToHeroSlide(next) {
  // Deactivate current
  heroSlides[heroIndex].classList.remove('hero__slide--active');
  heroSlides[heroIndex].setAttribute('aria-hidden', 'true');
  heroDots[heroIndex].classList.remove('hero__dot--active');
  heroDots[heroIndex].setAttribute('aria-pressed', 'false');
  heroDots[heroIndex].setAttribute('tabindex', '-1');

  heroIndex = next;

  // Activate next
  heroSlides[heroIndex].classList.add('hero__slide--active');
  heroSlides[heroIndex].removeAttribute('aria-hidden');
  heroDots[heroIndex].classList.add('hero__dot--active');
  heroDots[heroIndex].setAttribute('aria-pressed', 'true');
  heroDots[heroIndex].removeAttribute('tabindex');

  // Reset auto-advance timer (dot click resets the 5s window)
  clearInterval(heroTimer);
  heroTimer = setInterval(advanceHero, SLIDE_MS);
}

function advanceHero() {
  goToHeroSlide((heroIndex + 1) % heroSlides.length);
}

heroDots.forEach((dot, i) => {
  dot.addEventListener('click', () => goToHeroSlide(i));
});

// Start auto-advance
heroTimer = setInterval(advanceHero, SLIDE_MS);
