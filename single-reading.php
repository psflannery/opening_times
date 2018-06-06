<?php
/**
 * The Template for displaying all single reading posts.
 *
 * @package Opening Times
 */

get_header(); ?>

	<main id="main" class="site-main container-fluid page-container" role="main">
		<div class="row">
            <div class="issue__issue-list col-md-2 px-0 scene_element--fadeinup">

            	<?php get_template_part( 'template-parts/loops/loop', 'reading-issues' ); ?>

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

                    <div class="container-fluid w-100">
                        <div class="card-block row">
                            <div class="col-12">

                                <?php 
                                    echo opening_times_get_social_share();
                                    edit_post_link( __( 'Edit', 'opening_times' ), '<p class="edit-link">', '</p>');
                                ?>

                            </div>
                        </div>
                    </div>
                </div>                
            </div>
        </div>
	</main>

<?php get_footer(); ?>
