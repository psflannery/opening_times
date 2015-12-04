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

    <main id="main" class="site-main gradienter container-fluid container-padding" role="main">

        <?php 
        while ( have_posts() ) : the_post();

            get_template_part( 'template-parts/content', 'page' );

        endwhile; ?>

    </main>

<?php get_footer(); ?>
