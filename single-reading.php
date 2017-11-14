<?php
/**
 * The Template for displaying all single reading posts.
 *
 * @package Opening Times
 */

get_header(); ?>

	<main id="main" class="site-main container-fluid issue__container" role="main">
		<div class="row">
            <div class="issue__issue-list col-md-2 px-0">

            	<?php
                    opening_times_do_large_accordion_sidebar('<div class="sticky-top">', '</div>');
                    
            		get_template_part( 'template-parts/loops/loop', 'reading-issues' );
            	 ?>

            </div>
            <div class="issue__issue col-md-10">
                <div class="row">

			        <?php 
			        while ( have_posts() ) : the_post();

			        	do_action( 'opening-times-before-reading-issue' );

			            get_template_part( 'template-parts/content-reading', opening_times_get_reading_format() );

			            do_action( 'opening-times-after-reading-issue' );

			        endwhile; 
			        ?>

                </div>
            </div>
        </div>
	</main>

<?php get_footer(); ?>
