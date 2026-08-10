<?php
/**
 * Mobile / collapsed nav drawer. Same menu as the pill header; falls back
 * to hardcoded links until a "primary" menu is assigned.
 */
?>
<dialog class="nav-drawer" id="nav-drawer" aria-label="Navigation menu">
	<button class="nav-drawer__close" aria-label="Close navigation menu">&#x2715;</button>
	<nav class="nav-drawer__nav" aria-label="Site navigation">
		<?php
		if ( has_nav_menu( 'primary' ) ) {
			wp_nav_menu( array(
				'theme_location' => 'primary',
				'container'      => false,
				'items_wrap'     => '%3$s',
				'link_before'    => '<span class="nav-drawer__link">',
				'link_after'     => '</span>',
			) );
		} else {
			?>
			<a href="#schedule" class="nav-drawer__link">SCHEDULE</a>
			<a href="#standings" class="nav-drawer__link">STANDINGS</a>
			<a href="#drivers" class="nav-drawer__link">DRIVERS</a>
			<a href="#media" class="nav-drawer__link">MEDIA</a>
			<?php
		}
		?>
	</nav>
	<div class="nav-drawer__data">
		<p class="nav-drawer__placeholder">[Upcoming races — placeholder]</p>
		<p class="nav-drawer__placeholder">[Points leaders — placeholder]</p>
	</div>
</dialog>
