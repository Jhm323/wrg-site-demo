<?php
/**
 * DEV MODE panel — the brand-skin swatcher + live-state toggle from the
 * static prototype. Gated behind WP_DEBUG (see dirtcar_devpanel_enabled()
 * in functions.php) so it never renders for real visitors in production.
 */
if ( ! dirtcar_devpanel_enabled() ) {
	return;
}
?>
<div class="devpanel" id="devpanel">
	<button class="devpanel__tab" id="devpanel-toggle" aria-expanded="false" aria-controls="devpanel-body">
		DEV MODE &#9662;
	</button>
	<div class="devpanel__body" id="devpanel-body" hidden>
		<p class="devpanel__note">Review tool for this demo — not part of production. Real brand skin is set per-site in Customizer &rarr; Site Identity &rarr; Brand Skin.</p>

		<fieldset class="devpanel__fieldset">
			<legend class="devpanel__legend">SKIN</legend>
			<div class="devpanel__skins">
				<button class="devpanel__skin-btn" data-brand="woo-sprint">WoO Sprint</button>
				<button class="devpanel__skin-btn" data-brand="woo-latemodel">WoO Late Model</button>
				<button class="devpanel__skin-btn" data-brand="super-dirtcar">Super DIRTcar</button>
			</div>
		</fieldset>

		<fieldset class="devpanel__fieldset">
			<legend class="devpanel__legend">LIVE STATE</legend>
			<label class="devpanel__live-label">
				<input type="checkbox" id="devpanel-islive" checked>
				Race is live
			</label>
		</fieldset>
	</div>
</div>
