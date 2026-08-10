<?php
/**
 * LAYER 3.5: News — horizontal card scroll.
 * @var array $args ['data' => full data array from dirtcar_get_data()]
 */
$news = $args['data']['news'];
?>
<section class="news" id="media" aria-label="Latest news">
	<div class="clay-divider" aria-hidden="true"></div>

	<div class="news__inner">
		<div class="news__header">
			<h2 class="news__heading">LATEST NEWS</h2>
			<div class="news__arrows">
				<button class="news__arrow news__arrow--prev" aria-label="Previous news" disabled>&#8592;</button>
				<button class="news__arrow news__arrow--next" aria-label="Next news">&#8594;</button>
			</div>
		</div>

		<div class="news__track" id="news-track" tabindex="0">
			<?php foreach ( $news as $card ) : ?>
				<article class="news__card">
					<a href="#" class="news__card-link">
						<img class="news__card-image"
						     src="<?php echo esc_url( $card['photo'] ); ?>"
						     alt=""
						     width="320" height="180"
						     loading="lazy" decoding="async">
						<div class="news__card-body">
							<p class="news__card-eyebrow u-label"><?php echo esc_html( $card['eyebrow'] ); ?></p>
							<h3 class="news__card-headline"><?php echo esc_html( $card['headline'] ); ?></h3>
						</div>
					</a>
				</article>
			<?php endforeach; ?>
		</div>
	</div>

	<div class="clay-divider" aria-hidden="true"></div>
</section>
