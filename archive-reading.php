<?php
/**
 * The template for displaying the Journal Archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Opening Times
 */

get_header(); ?>
	
    <main id="main" class="site-main accordion-issue container-fluid gradienter" role="main">

        <?php
        if ( have_posts() ) :

            while ( have_posts() ) : the_post();

                get_template_part( 'template-parts/single', 'issue' );

            endwhile;

            the_posts_navigation();

        else :

            get_template_part( 'template-parts/content', 'none' );

        endif; ?>

    </main>

<?php get_footer(); ?>
