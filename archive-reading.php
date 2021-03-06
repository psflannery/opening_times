<?php
/**
 * The template for displaying the Journal Archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Opening Times
 */

get_header(); ?>
	
    <main id="main" class="site-main container-fluid page-container" role="main">
        <div class="row">
            <div class="issue__issue-list col-md-2 px-0">

                <?php
                if ( have_posts() ) :

                    echo '<div class="gradient-container list-group reading__issue-list sticky-top">';

                    while ( have_posts() ) : the_post();
                        if ( 0 === $wp_query->current_post ) :
                            
                            the_title( '<a href="#" class="gradient-text list-group-item active d-block">' . opening_times_reading_issue_title('<span class="d-block">', '</span>', false) . '<span class="small d-block">', '</span></a>' );

                        else:
                            
                            the_title( '<a href="' . esc_url( get_permalink() ) . '" class="gradient-text list-group-item list-group-item-action d-block" rel="bookmark">' . opening_times_reading_issue_title('<span class="d-block">', '</span>', false) . '<span class="small d-block">', '</span></a>' );

                        endif;
                    endwhile;

                    echo '</div>';

                    the_posts_navigation();

                else :

                    get_template_part( 'template-parts/content', 'none' );

                endif;
                rewind_posts();
                ?>

            </div>
            <div class="issue__issue col-md-10">
                <div class="row">

                    <?php
                    while ( have_posts() ) : the_post();
                        if ( 0 === $wp_query->current_post ) :

                            do_action( 'opening-times-before-reading-issue' );

                            get_template_part( 'template-parts/content-reading', opening_times_get_reading_format() );

                            do_action( 'opening-times-after-reading-issue' );

                        endif;
                    endwhile; 
                    rewind_posts();
                    ?>

                </div>
            </div>
        </div>
    </main>

<?php get_footer(); ?>
