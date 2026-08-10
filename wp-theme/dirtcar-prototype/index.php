<?php
/**
 * Fallback template (used for any request WordPress can't match to a more
 * specific template — front-page.php handles the homepage itself). This
 * theme is a homepage-only prototype conversion; see README.md "What's
 * intentionally not done yet" for the pages this doesn't cover.
 */
get_header();
?>

<div class="content-channel__fallback">
	<?php if ( have_posts() ) : ?>
		<?php while ( have_posts() ) : the_post(); ?>
			<article <?php post_class(); ?>>
				<h1><?php the_title(); ?></h1>
				<?php the_content(); ?>
			</article>
		<?php endwhile; ?>
	<?php else : ?>
		<p><?php esc_html_e( 'Nothing found.', 'dirtcar-prototype' ); ?></p>
	<?php endif; ?>
</div>

<?php get_footer(); ?>
