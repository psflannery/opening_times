<?php
/**
 * The template for displaying the Large Reading Accordion pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Opening Times
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> data-transition="fade">
	<div id="<?php opening_times_the_slug(); ?>" class="container-fluid sceneElement">
        <div class="card-block row">

        	<?php get_template_part( 'template-parts/content-blocks/block-reading-title' ); ?>
            
            <div class="entry-content col-md-12">

            	<?php 
                    the_content( sprintf(
                        /* translators: %s: Name of current post. */
                        wp_kses( __( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'opening_times' ), array( 'span' => array( 'class' => array() ) ) ),
                        the_title( '<span class="screen-reader-text">"', '"</span>', false )
                    ) );

                    opening_times_do_reading_accordion(
                        array (
                            'container_class' => 'accordion gradient-container accordion--large',
                            'header_class'    => 'collapsed accordion-header accordion-header--large container-fluid gradient-text',
                            'content_class'   => 'container-fluid collapse w-100 px-0',
                        )
                    );

                    opening_times_editor_bio( '<aside class="issue-content__bio"><div class="row">', '</div></aside>' );
                ?>	

            </div>
        </div>
    </div>
</article>