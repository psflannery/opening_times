<?php
/**
 * The template for displaying all pages.
 * 
 * Template Name: Full-width page layout
 * Template Post Type: page, reading
 *
 * @package Opening Times
 */

get_header(); ?>

    <main id="main" class="site-main gradienter container-fluid page-container page-container--page" role="main" data-transition="fade">
    	<div class="row sceneElement">

	        <?php 
	        while ( have_posts() ) : the_post();

	            get_template_part( 'template-parts/content', 'page' );

	        endwhile; ?>

   		</div>
    </main>

<?php get_footer(); ?>