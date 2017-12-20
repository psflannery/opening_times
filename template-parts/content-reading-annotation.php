<?php
/**
 * The template for displaying the Large Reading Annotation pages.
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
                ?>

				<div class="row">
					<div class="col-12">
						<div class="row">

                    		<?php get_template_part( 'template-parts/content-blocks/block-annotation' ); ?>
                    		
						</div>
					</div>
				</div>

                <?php opening_times_editor_bio( '<aside class="issue-content__bio"><div class="row">', '</div></aside>' ); ?>	

			</div>
		</div>
	</div>
</article>