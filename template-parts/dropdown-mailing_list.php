<?php
/**
 * The template used for displaying the Mailing List dropdown.
 *
 * @package Opening Times
 */
?>

<section id="mailing-list" class="info-panel" aria-hidden="true" role="complementary">

	<?php $email = get_theme_mod( 'ot_email_address' ); ?>
	<?php if ( '' != $email ) : ?>
		<h1><span class="ot-email"><?php echo $email ?></span></h1>
	<?php endif; ?>
		
	<?php $mail_subscribe = get_theme_mod( 'ot_mailing_list' ); ?>
	<?php if ( '' != $mail_subscribe ) : ?>
		<?php global $post; ?>
		<?php $page_id = $mail_subscribe; ?>
		<?php $post = get_post( $page_id ); ?>
		<?php setup_postdata( $post ); ?>
		
		<div id="ot-mailing-list" class="mailing-list">
			<?php the_content(); ?>
			<?php edit_post_link( __( 'Edit', 'opening_times' ), '<footer class="entry-meta"><span class="edit-link">', '</span></footer>' ); ?>
		</div>
		
		<?php wp_reset_postdata( $post ); ?>
		
	<?php endif; ?>
	
	<?php get_sidebar(); ?>
</section>