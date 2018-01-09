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
            
            <div class="entry-content col-12 col-lg-5">
            
                <?php 
                    the_content( sprintf(
                        /* translators: %s: Name of current post. */
                        wp_kses( __( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'opening_times' ), array( 'span' => array( 'class' => array() ) ) ),
                        the_title( '<span class="screen-reader-text">"', '"</span>', false )
                    ) );

                    opening_times_editor_bio( '<aside class="issue-content__bio"><div class="row">', '</div></aside>' );
                ?>		
    
            </div>
            <div class="entry-links col-12 col-lg-7">
                <div class="sticky-top">

                    <?php 
                        do_action( 'before_reading_list' );

                        opening_times_do_reading_accordion();

                        do_action( 'after_reading_list' ); 
                    ?>

                </div>
            </div>
        </div>
    </div>
</article>
