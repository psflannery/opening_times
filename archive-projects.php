<?php
/**
 * The template for displaying the Project Archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Opening Times
 */

get_header(); ?>

    <main id="main" class="site-main accordion gradienter container-fluid" role="main">

        <?php 
        if ( have_posts() ) :

            while ( have_posts() ) : the_post();

                get_template_part( 'template-parts/content', 'project' );

            endwhile;

            the_posts_navigation();

        else :

            get_template_part( 'template-parts/content', 'none' );

        endif; ?>

    </main>

<?php get_footer(); ?>
