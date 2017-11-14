<?php
/**
 * The template for displaying the Standard Reading Accordion pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Opening Times
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> data-transition="fade">
    <div id="<?php opening_times_the_slug(); ?>" class="sceneElement container-fluid">
        <div class="card-block row">

            <?php get_template_part( 'template-parts/content-blocks/block-reading-title' ); ?>
            
            <div class="entry-content col-md-6 col-lg-5">
            
                <?php 
                    the_content( sprintf(
                        /* translators: %s: Name of current post. */
                        wp_kses( __( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'opening_times' ), array( 'span' => array( 'class' => array() ) ) ),
                        the_title( '<span class="screen-reader-text">"', '"</span>', false )
                    ) );

                    opening_times_editor_bio( '<aside class="issue-content__bio"><div class="row">', '</div></aside>' );
                ?>		
    
            </div>
            <div class="entry-links col-md-6 col-lg-7">

                <?php 
                    do_action( 'before_reading_list' );

                    $args = array(
                        'post_parent' => $post->ID,
                        'post_type' => 'reading',
                        'orderby' => 'menu_order',
                        'posts_per_page' => -1,
                    );

                    $child_query = new WP_Query( $args );

                    if ( $child_query->have_posts() ) :
                        echo '<div class="sticky-top accordion-container">';
                        echo '<div id="accordion-' . opening_times_the_slug(false) . '" class="accordion mb-5" role="tablist" aria-multiselectable="true">';

                        while ( $child_query->have_posts() ) : $child_query->the_post();
     
                            get_template_part( 'template-parts/content-blocks/block-reading-accordion' );

                        endwhile;
                        wp_reset_postdata();

                        echo '</div>';

                        do_action( 'after_reading_list' ); 

                        echo '</div>';

                    endif;
                ?>

            </div>
        </div>
    </div>
</article>
