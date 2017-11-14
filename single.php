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

            get_template_part( 'template-parts/content', get_post_format() );

        endwhile; 
        ?>

    </main>

<?php get_footer(); ?>
