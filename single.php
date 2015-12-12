<?php
/**
 * The Template for displaying all single posts.
 *
 * @package Opening Times
 */

get_header(); ?>

    <main id="main" class="site-main gradienter container-fluid" role="main">

        <?php 
        while ( have_posts() ) : the_post();

            $post_type = get_post_type();
                switch( $post_type ) {
                    case 'post':
                        get_template_part( 'template-parts/content', get_post_format() );
                    break;

                    case 'reading':
                        get_template_part( 'template-parts/single', 'issue' );
                    break;

                    case 'article':
                        get_template_part( 'template-parts/single', 'reading-article' );
                    break;

                    case 'projects':
                        get_template_part( 'template-parts/content', 'project' );
                    break;

                    case 'take-overs':
                        get_template_part( 'template-parts/content', 'take-over' );
                    break;
                }

        endwhile; ?>

    </main>

<?php get_footer(); ?>