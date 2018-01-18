<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package Opening Times
 */

get_header(); ?>

    <main id="main" class="site-main container-fluid page-container page-container--page" role="main" data-transition="fade">
    	<div class="row sceneElement">

	        <?php 
	        while ( have_posts() ) : the_post();

	            get_template_part( 'template-parts/content', 'page' );

	        endwhile; ?>

   		</div>
    </main>

<?php get_footer(); ?>
