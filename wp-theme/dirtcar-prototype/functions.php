<?php
/**
 * DIRTcar Prototype theme bootstrap.
 *
 * This file does three jobs:
 *   1. Standard WP theme setup (support flags, nav menus).
 *   2. Enqueue the ported CSS/JS from the static prototype.
 *   3. Define the data layer (dirtcar_get_data()) that every template part
 *      reads from. Right now it returns hardcoded fallback data identical
 *      to the static prototype's placeholder content. Swap the body of
 *      that one function for wp_remote_get() calls against the WRG public
 *      API (or WP_Query against custom post types) and nothing in the
 *      templates needs to change — see README.md "Wiring real data".
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // No direct access.
}

/**
 * Theme setup.
 */
function dirtcar_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'custom-logo' );

	register_nav_menus( array(
		'primary'         => __( 'Primary Navigation', 'dirtcar-prototype' ),
		'footer-series'   => __( 'Footer — Series', 'dirtcar-prototype' ),
		'footer-explore'  => __( 'Footer — Explore', 'dirtcar-prototype' ),
		'footer-connect'  => __( 'Footer — Connect', 'dirtcar-prototype' ),
	) );
}
add_action( 'after_setup_theme', 'dirtcar_setup' );

/**
 * Styles + scripts.
 * Google Fonts + the theme stylesheet (which already contains the WP theme
 * header, see style.css) + the ported vanilla-JS shell behavior.
 */
function dirtcar_enqueue_assets() {
	wp_enqueue_style(
		'dirtcar-google-fonts',
		'https://fonts.googleapis.com/css2?family=Anton&family=Oswald:wght@400;500;600&family=Inter:wght@400;500&family=JetBrains+Mono:wght@400;700&display=swap',
		array(),
		null
	);

	wp_enqueue_style(
		'dirtcar-style',
		get_stylesheet_uri(),
		array(),
		wp_get_theme()->get( 'Version' )
	);

	wp_enqueue_script(
		'dirtcar-main',
		get_theme_file_uri( '/js/main.js' ),
		array(),
		wp_get_theme()->get( 'Version' ),
		true // footer, matches the `defer` placement in the static prototype
	);
}
add_action( 'wp_enqueue_scripts', 'dirtcar_enqueue_assets' );

/**
 * Brand skin.
 * The static prototype toggled brands via <html data-brand="..."> and a
 * DEV MODE panel. In WP, the brand is a per-site setting (one multisite
 * sub-site per brand) exposed through the Customizer so an editor can set
 * it without touching code.
 */
function dirtcar_customize_register( $wp_customize ) {
	$wp_customize->add_setting( 'dirtcar_brand_skin', array(
		'default'           => 'super-dirtcar',
		'sanitize_callback' => 'sanitize_text_field',
	) );

	$wp_customize->add_control( 'dirtcar_brand_skin', array(
		'label'    => __( 'Brand Skin', 'dirtcar-prototype' ),
		'section'  => 'title_tagline',
		'type'     => 'select',
		'choices'  => array(
			'woo-sprint'     => 'World of Outlaws — Sprint Cars',
			'woo-latemodel'  => 'World of Outlaws — Late Models',
			'super-dirtcar'  => 'Super DIRTcar Series',
		),
	) );
}
add_action( 'customize_register', 'dirtcar_customize_register' );

/**
 * Small helper — resolves a filename in the theme's /assets/photos folder
 * to a full URL. Swap for wp_get_attachment_image() / wp_get_attachment_url()
 * once real content is coming out of the Media Library instead of these
 * bundled demo photos.
 */
function dirtcar_photo_url( $filename ) {
	return get_theme_file_uri( '/assets/photos/' . $filename );
}

/**
 * THE DATA SEAM.
 *
 * Every dynamic block on the page (rail schedule/standings, hero slides,
 * featured blocks, standings podium, season stats, news cards) reads from
 * this single function. It currently returns the same placeholder content
 * the static prototype shipped with, so the theme renders identically to
 * the demo out of the box — no WordPress content required to see it work.
 *
 * To wire it to something real: replace the body below with either
 *   - WP_Query calls against custom post types (races, drivers, news), or
 *   - wp_remote_get() calls against the WRG public API, cached with
 *     transients (e.g. set_transient( 'dirtcar_data', $data, 15 * MINUTE_IN_SECONDS )).
 * No template file needs to change either way — they only read the array
 * shape returned here.
 */
function dirtcar_get_data() {
	return array(
		'rail' => array(
			'logo_wordmark' => 'WoO SPRINT',
			'next_race'     => 'KNOXVILLE NATIONALS',
			'race_date'     => '2026-08-13T19:00:00-05:00',
			'schedule'      => array(
				array( 'date' => 'JUL 9',  'track' => 'Knoxville, IA' ),
				array( 'date' => 'JUL 16', 'track' => 'Eldora, OH' ),
				array( 'date' => 'JUL 23', 'track' => 'Lernerville, PA' ),
				array( 'date' => 'JUL 30', 'track' => 'Haubstadt, IN' ),
				array( 'date' => 'AUG 6',  'track' => 'Williams Grove, PA' ),
			),
			'standings' => array(
				array( 'pos' => 1, 'name' => 'D. SCHATZ',  'pts' => '1,240' ),
				array( 'pos' => 2, 'name' => 'K. MADSEN',  'pts' => '1,190' ),
				array( 'pos' => 3, 'name' => 'G. LOGAN',   'pts' => '1,155' ),
			),
		),

		'hero_slides' => array(
			array(
				'photo' => dirtcar_photo_url( '_DSC0522.jpg' ),
				'alt'   => 'Sprint cars lined up for the main event with fireworks in the night sky',
				'eyebrow' => 'KNOXVILLE NATIONALS · KNOXVILLE, IA',
				'name'    => 'DONNY SCHATZ',
			),
			array(
				'photo' => dirtcar_photo_url( '150A0096.jpg' ),
				'alt'   => 'Sprint cars racing three-wide through a dusty turn at night',
				'eyebrow' => 'DIRT SUMMER NATIONALS · ELDORA, OH',
				'name'    => 'BRAD SWEET',
			),
			array(
				'photo' => dirtcar_photo_url( '_DSC0522.jpg' ),
				'alt'   => 'Sprint cars lined up for the main event with fireworks in the night sky',
				'eyebrow' => 'WILLIAMS GROVE 410 SPRINT · WILLIAMS GROVE, PA',
				'name'    => 'KERRY MADSEN',
			),
			array(
				'photo' => dirtcar_photo_url( '150A0096.jpg' ),
				'alt'   => 'Sprint cars racing three-wide through a dusty turn at night',
				'eyebrow' => 'DIRT CLASSIC · TULSA, OK',
				'name'    => 'SHELDON HAUDENSCHILD',
			),
		),

		'floating_blocks' => array(
			array(
				'photo'   => dirtcar_photo_url( '6L4A5231.jpg' ),
				'eyebrow' => 'This week at Knoxville',
				'headline' => 'NATIONALS WEEK RETURNS TO THE IOWA SPEED PLANT',
				'blurb'   => "The Knoxville Nationals — dirt track racing's biggest stage — returns July 9–12. Donny Schatz leads an all-star field of 58 cars chasing the most coveted trophy in the sport. Gates open Wednesday for hot laps.",
			),
			array(
				'photo'   => dirtcar_photo_url( 'DSC01640 copy.jpg' ),
				'eyebrow' => 'This week at Eldora',
				'headline' => 'WORLD 100 QUALIFIER SETS THE KNOXVILLE STAGE',
				'blurb'   => 'Eldora Speedway hosts the final tune-up before Nationals: a 40-lap qualifier that has historically predicted the Knoxville champion. Brad Sweet and Kerry Madsen have both won here in the last four seasons.',
			),
			array(
				'photo'   => dirtcar_photo_url( '150A0096.jpg' ),
				'eyebrow' => 'This week at Williams Grove',
				'headline' => 'GROVE 410 SPRINT DRAWS 38-CAR FIELD',
				'blurb'   => 'Williams Grove Speedway continues its streak of 38-plus car fields as the season builds toward the championship stretch. Local favorite Lance Dewease headlines the entry list alongside two WoO title contenders.',
			),
		),

		'track_context' => array(
			'name'     => 'KNOXVILLE RACEWAY',
			'location' => 'Knoxville, Iowa · ½ Mile Dirt Oval',
			'aerial'   => dirtcar_photo_url( 'DSC_8585.jpg' ),
			'winners'  => array(
				array( 'name' => 'Donny Schatz',    'year' => '2024' ),
				array( 'name' => 'Brad Sweet',      'year' => '2023' ),
				array( 'name' => 'Donny Schatz',    'year' => '2022' ),
				array( 'name' => 'Logan Schuchart', 'year' => '2021' ),
				array( 'name' => 'Donny Schatz',    'year' => '2019' ),
			),
		),

		'standings_podium' => array(
			'p1' => array( 'photo' => dirtcar_photo_url( 'DSC01640 copy.jpg' ), 'name' => 'DONNY SCHATZ', 'pts' => '1,240 PTS' ),
			'p2' => array( 'photo' => dirtcar_photo_url( '150A0096.jpg' ),      'name' => 'KERRY MADSEN', 'pts' => '1,190 PTS' ),
			'p3' => array( 'photo' => dirtcar_photo_url( 'DSC01640 copy.jpg' ), 'name' => 'GRANT LOGAN',  'pts' => '1,155 PTS' ),
		),
		'standings_list' => array(
			array( 'pos' => 4, 'name' => 'BRAD SWEET',           'pts' => '987' ),
			array( 'pos' => 5, 'name' => 'SHELDON HAUDENSCHILD', 'pts' => '943' ),
		),

		'season_stats' => array(
			'wins'        => array( 'photo' => dirtcar_photo_url( 'JJA_1870-2.jpg' ),          'value' => 18,   'label' => 'Season Wins' ),
			'laps_led'    => array( 'photo' => dirtcar_photo_url( 'DSC_0811.jpg' ),             'value' => 847,  'label' => 'Laps Led' ),
			'heat_wins'   => array( 'photo' => dirtcar_photo_url( 'DSC_0451.jpg' ),             'value' => 62,   'label' => 'Heat Wins' ),
			'miles'       => array( 'photo' => dirtcar_photo_url( '6L4A5231.jpg' ),             'value' => 2481, 'label' => 'Miles' ),
			'events'      => array( 'photo' => dirtcar_photo_url( 'DSC01532 copy.jpg' ),        'value' => 47,   'label' => 'Total Events' ),
			'podiums'     => array( 'photo' => dirtcar_photo_url( 'DSC01640 copy.jpg' ),        'value' => 34,   'label' => 'Podiums' ),
		),

		'news' => array(
			array( 'photo' => dirtcar_photo_url( 'DSC_0451.jpg' ),      'eyebrow' => 'RACE RESULTS · JUL 9',  'headline' => 'SCHATZ CLAIMS KNOXVILLE IN WIRE-TO-WIRE RUN' ),
			array( 'photo' => dirtcar_photo_url( 'DSC01532 copy.jpg' ), 'eyebrow' => 'RACE RESULTS · JUN 28', 'headline' => 'SWEET HOLDS OFF MADSEN IN ELDORA THRILLER' ),
			array( 'photo' => dirtcar_photo_url( 'JJA_1870-2.jpg' ),    'eyebrow' => 'EVENT PREVIEW · JUN 20', 'headline' => 'DIRT SUMMER NATIONALS RETURNS TO ELDORA FOR 40TH YEAR' ),
			array( 'photo' => dirtcar_photo_url( 'DSC_0811.jpg' ),      'eyebrow' => 'DRIVER NEWS · MAR 3',   'headline' => 'HAUDENSCHILD EXTENDS WITH STENHOUSE JR.–MARSHALL' ),
			array( 'photo' => dirtcar_photo_url( 'DSC_0451.jpg' ),      'eyebrow' => 'RACE RESULTS · JUN 14', 'headline' => 'MADSEN DOMINATES WILLIAMS GROVE IN FLAG-TO-FLAG WIN' ),
			array( 'photo' => dirtcar_photo_url( 'DSC01532 copy.jpg' ), 'eyebrow' => 'STANDINGS · JUL 1',     'headline' => 'POINTS BATTLE TIGHTENS WITH SIX ROUNDS REMAINING' ),
			array( 'photo' => dirtcar_photo_url( 'JJA_1870-2.jpg' ),    'eyebrow' => 'SERIES NEWS · JAN 15',  'headline' => '2026 SPRINT SEASON EXPANDS TO 90 EVENTS ACROSS 22 STATES' ),
		),

		'is_live' => true,
	);
}

/**
 * DEV MODE panel (brand-skin swatcher + live-state toggle) from the static
 * prototype. Gated behind WP_DEBUG so it never ships to real visitors.
 */
function dirtcar_devpanel_enabled() {
	return defined( 'WP_DEBUG' ) && WP_DEBUG;
}
