<?php
/**
 * LAYER 3.2: Floating Feature Blocks + track-context card.
 * Featured = curated depth showcase (2–3 blocks), distinct from the
 * high-volume Latest News scroller further down the page.
 *
 * @var array $args ['data' => full data array from dirtcar_get_data()]
 */
$blocks = $args['data']['floating_blocks'];
$track  = $args['data']['track_context'];
?>
<section class="floating-blocks" id="schedule" aria-label="Featured stories">
	<div class="floating-blocks__header">
		<p class="floating-blocks__label u-label">Featured</p>
	</div>
	<div class="floating-blocks__inner">

		<?php foreach ( $blocks as $i => $block ) : ?>
			<div class="floating-block floating-block--<?php echo 0 === $i % 2 ? 'left' : 'right'; ?>">
				<article class="floating-block__card">
					<figure class="floating-block__photo">
						<img src="<?php echo esc_url( $block['photo'] ); ?>"
						     alt=""
						     width="640" height="220"
						     loading="lazy" decoding="async">
					</figure>
					<p class="floating-block__eyebrow u-label"><?php echo esc_html( $block['eyebrow'] ); ?></p>
					<h2 class="floating-block__headline"><?php echo esc_html( $block['headline'] ); ?></h2>
					<p class="floating-block__blurb"><?php echo esc_html( $block['blurb'] ); ?></p>
					<a href="#" class="floating-block__link">Read more &#8594;</a>
				</article>
			</div>
		<?php endforeach; ?>

		<!-- Track context card — 4th block, alternates after the last editorial block -->
		<div class="floating-block floating-block--right">
			<article class="floating-block__card floating-block__card--track-context"
			         aria-label="Track context: <?php echo esc_attr( $track['name'] ); ?>, past winners">

				<p class="floating-block__eyebrow u-label">Past winners at this venue</p>

				<div class="track-ctx__header">
					<span class="track-ctx__flag" role="img" aria-label="US venue flag placeholder"></span>
					<h2 class="track-ctx__name"><?php echo esc_html( $track['name'] ); ?></h2>
				</div>
				<p class="track-ctx__location u-label"><?php echo esc_html( $track['location'] ); ?></p>

				<figure class="track-ctx__aerial-wrap" aria-hidden="true">
					<img class="track-ctx__aerial"
					     src="<?php echo esc_url( $track['aerial'] ); ?>"
					     alt=""
					     width="220" height="130"
					     loading="lazy" decoding="async">
					<p class="track-ctx__zone-label u-label">AERIAL VIEW</p>
				</figure>

				<ol class="track-ctx__winners" aria-label="Past winners at <?php echo esc_attr( $track['name'] ); ?>">
					<?php foreach ( $track['winners'] as $w ) : ?>
						<li class="track-ctx__winner">
							<span class="track-ctx__winner-name"><?php echo esc_html( $w['name'] ); ?></span>
							<span class="track-ctx__winner-year u-mono-num"><?php echo esc_html( $w['year'] ); ?></span>
						</li>
					<?php endforeach; ?>
				</ol>

			</article>
		</div>

	</div>
</section>
