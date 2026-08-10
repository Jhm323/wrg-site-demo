<?php
/**
 * LAYER 3.4: Season Stats — bento mosaic, count-up animation fires on
 * scroll-into-view (js/main.js, unchanged from the prototype).
 * @var array $args ['data' => full data array from dirtcar_get_data()]
 */
$stats = $args['data']['season_stats'];

// cell key => [ css class suffix, badge size, badge position ]
$cells = array(
	'wins'      => array( 'season-wins', 'xl', 'bottom-left' ),
	'laps_led'  => array( 'laps-led',    'xl', 'bottom-right' ),
	'heat_wins' => array( 'heat-wins',   'sm', 'top-left' ),
	'miles'     => array( 'miles',       'md', 'top-right' ),
	'events'    => array( 'total-events','md', 'top-left' ),
	'podiums'   => array( 'podiums',     'sm', 'bottom-right' ),
);
?>
<section class="season-stats" id="season-stats" aria-label="Season statistics">
	<div class="season-stats__inner">
		<h2 class="season-stats__heading" id="season-stats-heading">SEASON BY THE NUMBERS</h2>

		<div class="season-stats__bento" id="season-stats-cluster">
			<?php foreach ( $cells as $key => $cfg ) :
				list( $cell_class, $badge_size, $badge_pos ) = $cfg;
				$stat = $stats[ $key ];
				?>
				<div class="bento-cell bento-cell--<?php echo esc_attr( $cell_class ); ?>">
					<img class="bento-cell__photo"
					     src="<?php echo esc_url( $stat['photo'] ); ?>"
					     alt=""
					     loading="lazy" decoding="async">
					<div class="bento-badge bento-badge--<?php echo esc_attr( $badge_size ); ?> bento-badge--<?php echo esc_attr( $badge_pos ); ?>">
						<span class="bento-badge__num u-mono-num" data-target="<?php echo esc_attr( $stat['value'] ); ?>" aria-live="off"><?php echo esc_html( $stat['value'] ); ?></span>
						<span class="bento-badge__label u-label"><?php echo esc_html( $stat['label'] ); ?></span>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
