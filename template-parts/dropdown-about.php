<?php
/**
 * The template used for displaying the About dropdown.
 *
 * @package Opening Times
 */
?>

<section id="about" class="info-panel info-columns" aria-hidden="true" role="complementary">
	<?php $about_page = get_theme_mod( 'ot_about-menu' ); ?>
	<?php if ( '' != $about_page ) : ?>
		<?php global $post; ?>
		<?php $page_id = $about_page; ?>
		<?php $post = get_post( $page_id ); ?>
		<?php setup_postdata( $post ); ?>
		
		<article id="post-<?php the_ID(); ?>" class="about-drop-down drop-down">
			<?php the_content(); ?>
			<?php edit_post_link( __( 'Edit', 'opening_times' ), '<footer class="entry-meta"><span class="edit-link">', '</span></footer>' ); ?>
		</article>
		
		<?php wp_reset_postdata( $post ); ?>
		
	<?php endif; ?>
</section>