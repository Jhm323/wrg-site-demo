<?php
/**
 * LAYER 3.3: Standings — podium (P1–P3) + compact list (P4–P5).
 * @var array $args ['data' => full data array from dirtcar_get_data()]
 */
$podium = $args['data']['standings_podium'];
$list   = $args['data']['standings_list'];
?>
<section class="standings" id="standings" aria-label="Season standings">
	<div class="clay-divider" aria-hidden="true"></div>

	<div class="standings__inner">
		<h2 class="standings__heading">SEASON LEADERS</h2>

		<!-- Podium: DOM order P1, P2, P3 (semantic); CSS reorders to P2, P1, P3 on desktop -->
		<div class="standings__podium" id="standings-podium">
			<?php foreach ( array( 'p1', 'p2', 'p3' ) as $i => $key ) :
				$card = $podium[ $key ];
				$rank = $i + 1;
				?>
				<article class="standings__card standings__card--<?php echo esc_attr( $key ); ?>" aria-label="<?php echo esc_attr( $rank ); ?><?php echo esc_attr( 1 === $rank ? 'st' : ( 2 === $rank ? 'nd' : 'rd' ) ); ?> place: <?php echo esc_attr( $card['name'] ); ?>">
					<img class="standings__photo"
					     src="<?php echo esc_url( $card['photo'] ); ?>"
					     alt=""
					     width="400" height="500"
					     loading="lazy" decoding="async">
					<div class="standings__wash" aria-hidden="true"></div>
					<div class="standings__info">
						<span class="standings__rank" aria-hidden="true"><?php echo esc_html( $rank ); ?></span>
						<h3 class="standings__name"><?php echo esc_html( $card['name'] ); ?></h3>
						<span class="standings__pts u-mono-num"><?php echo esc_html( $card['pts'] ); ?></span>
					</div>
				</article>
			<?php endforeach; ?>
		</div>

		<ol class="standings__list" aria-label="Standings positions 4 and 5">
			<?php foreach ( $list as $row ) : ?>
				<li class="standings__list-item">
					<span class="standings__list-rank" aria-hidden="true"><?php echo esc_html( $row['pos'] ); ?></span>
					<span class="standings__list-name"><?php echo esc_html( $row['name'] ); ?></span>
					<span class="standings__list-pts u-mono-num"><span class="sr-only">Points: </span><?php echo esc_html( $row['pts'] ); ?></span>
				</li>
			<?php endforeach; ?>
		</ol>
	</div>

	<div class="clay-divider" aria-hidden="true"></div>
</section>
