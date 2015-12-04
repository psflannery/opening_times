<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package Opening Times
 */

get_header(); ?>

    <main id="main" class="site-main gradienter container-fluid container-padding" role="main">

        <?php 
        if ( have_posts() ) : ?>

            <header class="page-header">
                <h1 class="page-title"><?php printf( __( 'Search Results for: %s', 'opening_times' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
            </header>

            <?php while ( have_posts() ) : the_post();

                get_template_part( 'template-parts/content', 'search' );

            endwhile;

            the_posts_navigation();

        else :

            get_template_part( 'template-parts/content', 'none' );

        endif; ?>

    </main>

<?php get_footer(); ?>
