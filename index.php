<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Opening Times
 */

get_header(); ?>

    <main id="main" class="site-main" role="main" data-transition="fade">
        <div id="accordion" class="accordion gradient-container infinite sceneElement" role="tablist" aria-multiselectable="true">

            <?php 
            if ( have_posts() ) :

                while ( have_posts() ) : the_post();

                    get_template_part( 'template-parts/content', 'accordion' );

                endwhile;

                the_posts_navigation();

            else :

                get_template_part( 'template-parts/content', 'none' );

            endif; ?>

        </div>
    </main>

    <?php 
    if ( opening_times_has_featured_posts() ) : 
        // Get our Featured Content posts
        $featured = opening_times_get_featured_posts();

        // If we have no posts, our work is done here
        if ( empty( $featured ) ) {
            return;
        }

        foreach ( $featured as $post ) : setup_postdata( $post );
            echo '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">';
                get_template_part( 'template-parts/content-featured' );
            echo '</a>';
        endforeach;
        wp_reset_postdata();

    endif;

get_footer(); ?>
