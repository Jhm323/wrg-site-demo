<?php
/**
 * The homepage. header.php has already opened <main class="content-channel">
 * and made $dirtcar_data available; each section is its own template part
 * for readability, all reading from the same data array.
 */
get_template_part( 'template-parts/hero', null, array( 'data' => $dirtcar_data ) );
get_template_part( 'template-parts/floating-blocks', null, array( 'data' => $dirtcar_data ) );
get_template_part( 'template-parts/standings', null, array( 'data' => $dirtcar_data ) );
get_template_part( 'template-parts/season-stats', null, array( 'data' => $dirtcar_data ) );
get_template_part( 'template-parts/news', null, array( 'data' => $dirtcar_data ) );

get_footer();
