<!DOCTYPE html>
<html lang="<?php bloginfo( 'language' ); ?>" data-brand="<?php echo esc_attr( get_theme_mod( 'dirtcar_brand_skin', 'super-dirtcar' ) ); ?>">

<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<?php $dirtcar_data = dirtcar_get_data(); ?>

<!-- LAYER 2: Fixed graded background — z-index: 0. Never scrolls. -->
<div class="layer-bg" aria-hidden="true"></div>

<?php get_template_part( 'template-parts/rail', null, array( 'data' => $dirtcar_data ) ); ?>

<?php get_template_part( 'template-parts/pill-header', null, array( 'data' => $dirtcar_data ) ); ?>

<?php get_template_part( 'template-parts/nav-drawer' ); ?>

<main class="content-channel">
