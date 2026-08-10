<?php
/**
 * LAYER 3.1: Hero — Most Recent Winner Carousel.
 * @var array $args ['data' => full data array from dirtcar_get_data()]
 */
$slides = $args['data']['hero_slides'];
?>
<section class="hero" id="hero" aria-label="Recent race winners">
	<div class="hero__track">
		<?php foreach ( $slides as $i => $slide ) : ?>
			<article class="hero__slide<?php echo 0 === $i ? ' hero__slide--active' : ''; ?>"
			         id="hero-slide-<?php echo esc_attr( $i ); ?>"
			         aria-roledescription="slide"
			         aria-label="Slide <?php echo esc_attr( $i + 1 ); ?> of <?php echo esc_attr( count( $slides ) ); ?>: <?php echo esc_attr( $slide['name'] ); ?>"
			         <?php echo 0 !== $i ? 'aria-hidden="true"' : ''; ?>>
				<img class="hero__photo"
				     src="<?php echo esc_url( $slide['photo'] ); ?>"
				     alt="<?php echo esc_attr( $slide['alt'] ); ?>"
				     width="1920" height="1080"
				     loading="<?php echo 0 === $i ? 'eager' : 'lazy'; ?>"
				     <?php echo 0 === $i ? 'fetchpriority="high" decoding="sync"' : 'decoding="async"'; ?>>
				<div class="hero__grad" aria-hidden="true"></div>
				<div class="hero__content"<?php echo 0 === $i ? ' data-initial' : ''; ?>>
					<p class="hero__eyebrow"><?php echo esc_html( $slide['eyebrow'] ); ?></p>
					<h2 class="hero__name"><?php echo esc_html( $slide['name'] ); ?></h2>
					<a href="#" class="btn hero__cta">VIEW RESULTS</a>
				</div>
			</article>
		<?php endforeach; ?>
	</div>

	<div class="hero__dots" role="group" aria-label="Slide navigation">
		<?php foreach ( $slides as $i => $slide ) : ?>
			<button class="hero__dot<?php echo 0 === $i ? ' hero__dot--active' : ''; ?>"
			        aria-label="Slide <?php echo esc_attr( $i + 1 ); ?>: <?php echo esc_attr( $slide['name'] ); ?>"
			        aria-pressed="<?php echo 0 === $i ? 'true' : 'false'; ?>"
			        <?php echo 0 !== $i ? 'tabindex="-1"' : ''; ?>></button>
		<?php endforeach; ?>
	</div>
</section>

<div class="scroll-sentinel" id="scroll-sentinel" aria-hidden="true"></div>
<div class="clay-divider" aria-hidden="true"></div>
