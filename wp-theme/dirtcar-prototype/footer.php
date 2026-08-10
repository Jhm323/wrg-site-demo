</main><!-- .content-channel -->

<footer class="site-footer" aria-label="Site footer">

	<div class="footer-cols">

		<div class="footer-col footer-col--brand">
			<p class="footer-wordmark"><?php bloginfo( 'name' ); ?></p>
			<p class="footer-brand-desc"><?php bloginfo( 'description' ); ?></p>
		</div>

		<nav class="footer-col" aria-label="Series">
			<h3 class="footer-col__heading u-label">Series</h3>
			<?php if ( has_nav_menu( 'footer-series' ) ) : ?>
				<?php wp_nav_menu( array( 'theme_location' => 'footer-series', 'container' => false, 'menu_class' => 'footer-links', 'depth' => 1 ) ); ?>
			<?php else : ?>
				<ul class="footer-links">
					<li><a href="#" class="footer-link">World of Outlaws Sprint</a></li>
					<li><a href="#" class="footer-link">World of Outlaws Late Model</a></li>
					<li><a href="#" class="footer-link">Xtreme Outlaw Series</a></li>
					<li><a href="#" class="footer-link">DIRTcar Summer Nationals</a></li>
					<li><a href="#" class="footer-link">Summit Modified Nationals</a></li>
				</ul>
			<?php endif; ?>
		</nav>

		<nav class="footer-col" aria-label="Explore">
			<h3 class="footer-col__heading u-label">Explore</h3>
			<?php if ( has_nav_menu( 'footer-explore' ) ) : ?>
				<?php wp_nav_menu( array( 'theme_location' => 'footer-explore', 'container' => false, 'menu_class' => 'footer-links', 'depth' => 1 ) ); ?>
			<?php else : ?>
				<ul class="footer-links">
					<li><a href="#" class="footer-link">Schedule</a></li>
					<li><a href="#" class="footer-link">Standings</a></li>
					<li><a href="#" class="footer-link">Drivers</a></li>
					<li><a href="#" class="footer-link">News</a></li>
				</ul>
			<?php endif; ?>
		</nav>

		<nav class="footer-col" aria-label="Connect and company">
			<h3 class="footer-col__heading u-label">Connect</h3>
			<?php if ( has_nav_menu( 'footer-connect' ) ) : ?>
				<?php wp_nav_menu( array( 'theme_location' => 'footer-connect', 'container' => false, 'menu_class' => 'footer-links', 'depth' => 1 ) ); ?>
			<?php else : ?>
				<ul class="footer-links">
					<li><a href="#" class="footer-link">Media Credentials</a></li>
					<li><a href="#" class="footer-link">Jobs</a></li>
					<li><a href="#" class="footer-link">Contact Us</a></li>
					<li><a href="#" class="footer-link">Donation Requests</a></li>
				</ul>
			<?php endif; ?>
		</nav>

	</div>

	<ul class="footer-social" aria-label="Social media">
		<li>
			<a href="#" class="footer-social__link" aria-label="Facebook">
				<svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor" aria-hidden="true" focusable="false">
					<path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/>
				</svg>
			</a>
		</li>
		<li>
			<a href="#" class="footer-social__link" aria-label="X (formerly Twitter)">
				<svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor" aria-hidden="true" focusable="false">
					<path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
				</svg>
			</a>
		</li>
		<li>
			<a href="#" class="footer-social__link" aria-label="Instagram">
				<svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor" aria-hidden="true" focusable="false">
					<path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/>
				</svg>
			</a>
		</li>
		<li>
			<a href="#" class="footer-social__link" aria-label="YouTube">
				<svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor" aria-hidden="true" focusable="false">
					<path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
				</svg>
			</a>
		</li>
	</ul>

	<div class="footer-bottom">
		<p class="footer-bottom__copy u-label">&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?></p>
		<p class="footer-bottom__corp u-label">World Racing Group &middot; Concord, NC &middot; (704) 455-4111</p>
	</div>

</footer>

<?php get_template_part( 'template-parts/dev-panel' ); ?>

<?php wp_footer(); ?>
</body>
</html>
