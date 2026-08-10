<?php
/**
 * LAYER 1B: Top pill header — NAVIGATION frame. Spans right column,
 * collapses on scroll (behavior lives in js/main.js, unchanged from prototype).
 *
 * @var array $args ['data' => full data array from dirtcar_get_data()]
 */
$is_live = ! empty( $args['data']['is_live'] );
?>
<header class="pill-header<?php echo $is_live ? ' pill-header--has-live' : ''; ?>" id="pill-header">
	<div class="pill-header__inner">

		<a href="https://dirtvision.com"
		   target="_blank"
		   rel="noopener noreferrer"
		   class="pill-watch-live"
		   id="pill-watch-live"
		   aria-label="Watch live racing on DIRTVision, opens in new tab"
		   tabindex="-1">
			<span class="pill-watch-live__dot" aria-hidden="true"></span>
			WATCH LIVE
		</a>

		<span class="pill-header__logomark" aria-hidden="true"><?php bloginfo( 'name' ); ?></span>

		<nav class="pill-nav" aria-label="Primary navigation">
			<?php
			if ( has_nav_menu( 'primary' ) ) {
				wp_nav_menu( array(
					'theme_location' => 'primary',
					'container'      => false,
					'items_wrap'     => '%3$s',
					'link_before'    => '<span class="pill-nav__link">',
					'link_after'     => '</span>',
				) );
			} else {
				// Fallback links until a menu is assigned in Appearance → Menus.
				?>
				<a href="#schedule" class="pill-nav__link">SCHEDULE</a>
				<a href="#standings" class="pill-nav__link">STANDINGS</a>
				<a href="#drivers" class="pill-nav__link">DRIVERS</a>
				<a href="#media" class="pill-nav__link">MEDIA</a>
				<?php
			}
			?>
		</nav>

		<a href="#" class="btn pill-header__cta-btn">TICKETS</a>

		<button class="pill-header__hamburger"
		        aria-label="Open navigation menu"
		        aria-expanded="false"
		        aria-controls="nav-drawer"
		        tabindex="-1">
			<span aria-hidden="true"></span>
			<span aria-hidden="true"></span>
			<span aria-hidden="true"></span>
		</button>

	</div>
</header>
