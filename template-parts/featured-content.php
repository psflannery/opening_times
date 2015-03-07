<?php
/**
 * Output the featured content
 *
 * @package: Opening Times
 *
 * Get the fist value from the link array
 * @link: http://stackoverflow.com/questions/1921421/get-the-first-element-of-an-array
 */

$oembed = get_post_meta( $post->ID, '_ot_embed_url', true );
$link_url = get_post_meta( $post->ID, '_ot_link_url', true );
$file_url = get_post_meta( $post->ID, '_ot_file', true );
?>

<?php if ( '' != get_the_post_thumbnail() ) : ?>

	<?php if ( '' != $link_url  ) : ?>
		<figure class="featured-image col-sm-3"><a href="<?php echo reset($link_url) ?>" target="_blank"><?php the_post_thumbnail('reading-thumb'); ?></a></figure>
			
	<?php elseif ( is_post_type_archive( 'reading' ) || (is_singular( 'reading' ) || is_singular( 'article' )) ) : ?>
		<figure class="featured-image"><?php the_post_thumbnail('reading-thumb'); ?></figure>
		
	<?php else : ?>
		<figure class="featured-image col-sm-3"><?php the_post_thumbnail('accordion-thumb'); ?></figure>
		
	<?php endif; ?>

<?php elseif ( '' != $oembed ) : ?>

	<?php // If there is no thumbnail, but there is an embed, and we're not in the reading section or take-overs section. This will format the posts the appear in the archives. ?>
	
	<?php if ( !is_post_type_archive( array ( 'reading', 'take-overs' ) ) && !is_singular( array ( 'reading', 'take-overs', 'article' ) ) ) : ?>
		<figure class="col-sm-3"><?php echo apply_filters( 'the_content', $oembed ); ?></figure>
		
	<?php // If there is no thumbnail, but there is an embed, and we ARE in the TAKE-OVERS section ?>
	
	<?php elseif ( '' != $oembed && ( is_post_type_archive( 'take-overs' ) || is_singular( 'take-overs' ) ) ) : ?>
		<figure class="col-sm-5 fitvids"><?php echo apply_filters( 'the_content', $oembed ); ?></figure>
	
	<?php // If there is no thumbnail, but there is an embed, and we ARE in the READING section ?>
	
	<?php elseif ( '' != $oembed ) : ?>
		<figure><?php echo apply_filters( 'the_content', $oembed ); ?></figure>

	<?php endif; ?>
		
<?php // None of the above, everything is empty ?>

<?php elseif ( !is_post_type_archive( 'reading' ) && !is_singular( array ( 'reading', 'article' ) ) ) : ?>	
	<figure class="featured-image col-sm-3"><?php get_template_part('img/inline', 'future_content_thumbnail.svg'); ?></figure>

<?php endif; ?>