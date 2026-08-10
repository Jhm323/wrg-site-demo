<?php
/**
 * LAYER 1A: Left rail — DATA frame. position:fixed, always visible.
 * Reads from dirtcar_get_data()['rail'].
 *
 * @var array $args ['data' => full data array from dirtcar_get_data()]
 */
$rail = $args['data']['rail'];
?>
<aside class="rail" aria-label="Season data">

	<div class="rail__logo">
		<?php if ( has_custom_logo() ) : ?>
			<?php the_custom_logo(); ?>
		<?php else : ?>
			<span class="rail__logo-wordmark"><?php echo esc_html( $rail['logo_wordmark'] ); ?></span>
		<?php endif; ?>
	</div>

	<div class="rail__ticker">
		<p class="rail__eyebrow">NEXT RACE</p>
		<p class="rail__race-name u-truncate"><?php echo esc_html( $rail['next_race'] ); ?></p>
		<div class="countdown"
		     id="countdown"
		     data-race-date="<?php echo esc_attr( $rail['race_date'] ); ?>"
		     aria-label="Time until race start"
		     aria-live="off">
			<span class="countdown__segment">
				<span class="countdown__digits u-mono-num" id="cd-days">00</span>
				<span class="countdown__unit u-label">D</span>
			</span>
			<span class="countdown__colon" aria-hidden="true">:</span>
			<span class="countdown__segment">
				<span class="countdown__digits u-mono-num" id="cd-hours">00</span>
				<span class="countdown__unit u-label">H</span>
			</span>
			<span class="countdown__colon" aria-hidden="true">:</span>
			<span class="countdown__segment">
				<span class="countdown__digits u-mono-num" id="cd-mins">00</span>
				<span class="countdown__unit u-label">M</span>
			</span>
			<span class="countdown__colon" aria-hidden="true">:</span>
			<span class="countdown__segment">
				<span class="countdown__digits u-mono-num" id="cd-secs">00</span>
				<span class="countdown__unit u-label">S</span>
			</span>
		</div>
	</div>

	<nav class="rail__schedule" aria-label="Upcoming races">
		<h2 class="rail__section-heading u-label">UPCOMING RACES</h2>
		<ul class="rail__race-list" role="list">
			<?php foreach ( $rail['schedule'] as $i => $race ) : ?>
				<li class="rail__race-item<?php echo 0 === $i ? ' rail__race-item--next' : ''; ?>">
					<?php if ( 0 === $i ) : ?><span class="rail__race-dot" aria-hidden="true"></span><?php endif; ?>
					<span class="rail__race-date u-mono-num"><?php echo esc_html( $race['date'] ); ?></span>
					<span class="rail__race-track u-truncate"><?php echo esc_html( $race['track'] ); ?></span>
				</li>
			<?php endforeach; ?>
		</ul>
	</nav>

	<div class="rail__standings" aria-label="Points leaders">
		<h2 class="rail__section-heading u-label">POINTS LEADERS</h2>
		<ol class="rail__standings-list">
			<?php foreach ( $rail['standings'] as $s ) : ?>
				<li class="rail__standings-item">
					<span class="rail__standings-pos"><?php echo esc_html( $s['pos'] ); ?></span>
					<span class="rail__standings-name u-truncate"><?php echo esc_html( $s['name'] ); ?></span>
					<span class="rail__standings-pts u-mono-num"><?php echo esc_html( $s['pts'] ); ?></span>
				</li>
			<?php endforeach; ?>
		</ol>
	</div>

	<div class="rail__cta-wrap">
		<a href="#" class="btn rail__cta">BUY TICKETS</a>
	</div>

</aside>
