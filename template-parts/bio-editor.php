<?php
/**
 * The template used for the User Description AKA the Editor Bio
 *
 * @package Opening Times
 */
?>

<?php
	global $post;
	$user_description = get_the_author_meta('description');
	$user_url = get_the_author_meta('user_url');
?>
<?php if ( '' != $user_description ) : ?>
	<aside class="editor-bio">
		
	<?php if ( 'article' == get_post_type() ) : ?>
		<p><?php _e( 'Selected by: ', 'opening_times' ); ?><?php the_author_posts_link(); ?></p>
	<?php endif; ?>
	
	<?php echo wpautop( wptexturize( $user_description ) ); ?>
	<?php if ( '' != $user_url ) : ?>
		<p><a href="<?php echo $user_url ; ?>" target="_blank"><?php echo $user_url ; ?></a></p>
	<?php endif; ?>
		
	</aside>
<?php endif;