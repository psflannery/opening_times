<?php
/**
 * Output the featured content
 *
 * @package: Openiing Times
 */

$oembed = get_post_meta( $post->ID, '_ot_embed_url', true );
$link_url = get_post_meta( $post->ID, '_ot_link_url', true );
$file_url = get_post_meta( $post->ID, '_ot_file', true ); 
?>

<?php // If there is a thumbnail

if ( '' != get_the_post_thumbnail() ) : ?>
	<?php if ( is_post_type_archive( 'reading' ) || is_singular( 'reading' ) || is_singular( 'article' ) ) : ?>
		<figure class="featured-image"><?php the_post_thumbnail('reading-thumb'); ?></figure>
	
	<?php //elseif ( '' != get_the_post_thumbnail() && '' != $link_url  ) : ?>
		<!--<figure class="featured-image col-sm-3"><a href="<?php echo $link_url ?>"><?php the_post_thumbnail('reading-thumb'); ?></a></figure>-->
	
	<?php else : ?>
		<figure class="featured-image col-sm-3"><?php the_post_thumbnail('reading-thumb'); ?></figure>
		
	<?php endif; ?>

<?php 
// If there is no thumbnail, but there is an embed, and we're not in the reading section or take-overs section. This will format the posts the appear in the archives.

elseif ( '' != $oembed && ( !is_post_type_archive( array ( 'reading', 'take-overs' ) ) && !is_singular( array ( 'reading', 'take-overs', 'article' ) ) ) ) : ?>
	<figure class="col-sm-3"><?php echo apply_filters( 'the_content', $oembed ); ?></figure>
	
<?php
// If there is no thumbnail, but there is an embed, and we ARE in the TAKE-OVERS section

elseif ( '' != $oembed && ( is_post_type_archive( 'take-overs' ) || is_singular( 'take-overs' ) ) ) : ?>
	<figure class="col-sm-5 fitvids"><?php echo apply_filters( 'the_content', $oembed ); ?></figure>

<?php 
// If there is no thumbnail, but there is an embed, and we ARE in the READING section

elseif ( '' != $oembed ) : ?>
	<figure><?php echo apply_filters( 'the_content', $oembed ); ?></figure>

<?php 
// None of the above, everything is empty

elseif ( !is_post_type_archive( 'reading' ) && !is_singular( array ( 'reading', 'article' ) ) ) : ?>	
	<figure class="featured-image col-sm-3"><?php get_template_part('img/inline', 'future_content_thumbnail.svg'); ?></figure>

<?php endif; ?>