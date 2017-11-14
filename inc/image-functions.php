<?php
/**
 * Image related functions and filters
 *
 * @package Opening Times
 */

/**
 * Echo an image, no matter what.
 *
 * @param string  $size  The image size you want to display.
 *
 * @since opening_times 1.0.0
 */
function opening_times_do_post_image( $size = 'thumbnail' ) {
    // Grab our customizer settings.
	$placeholder = get_theme_mod( 'opening_times_placeholder' );
    
	// If featured image is present, use that.
	if ( has_post_thumbnail() ) {
		return the_post_thumbnail( $size, array( 'alt' => the_title_attribute( 'echo=0' ) ) );
	}
    
	// Check for any attached image
	$media = get_attached_media( 'image', get_the_ID() );
	$media = current( $media );
    
	// Set up default image path.
	$media_url = $placeholder;
    
	// If an image is present, then use it.
	if ( is_array( $media ) && 0 < count( $media ) ) {
		$media_url = ( 'thumbnail' === $size ) ? wp_get_attachment_thumb_url( $media->ID ) : wp_get_attachment_url( $media->ID );
	}

	echo '<img src="' . esc_url( $media_url ) . '" class="attachment-thumbnail wp-post-image" alt="' . esc_html( get_the_title() )  . '" />';
}

/**
 * Return an image URI, no matter what.
 *
 * @param  string  $size  The image size you want to return.
 * @return string         The image URI.
 *
 * @since opening_times 1.0.0
 */
function opening_times_get_post_image_uri( $size = 'thumbnail' ) {
    // Grab our customizer settings.
	$placeholder = ! empty( get_theme_mod( 'opening_times_placeholder' ) ) ? get_theme_mod( 'opening_times_placeholder' ) : get_stylesheet_directory_uri() . '/img/placeholder.png';
    
	// If featured image is present, use that.
	if ( has_post_thumbnail() ) {
		$featured_image_id = get_post_thumbnail_id( get_the_ID() );
		$media = wp_get_attachment_image_src( $featured_image_id, $size );
		if ( is_array( $media ) ) {
			return current( $media );
		}
	}
    
	// Check for any attached image.
	$media = get_attached_media( 'image', get_the_ID() );
	$media = current( $media );
    
	// Set up default image path.
	$media_url = $placeholder;
    
	// If an image is present, then use it.
	if ( is_array( $media ) && 0 < count( $media ) ) {
		$media_url = ( 'thumbnail' === $size ) ? wp_get_attachment_thumb_url( $media->ID ) : wp_get_attachment_url( $media->ID );
	}
	return $media_url;
}

/**
 * Get an attachment ID from it's URL.
 *
 * @param  string  $attachment_url  The URL of the attachment.
 * @return int                      The attachment ID.
 *
 * @since opening_times 1.0.0
 */
function opening_times_get_attachment_id_from_url( $attachment_url = '' ) {
	global $wpdb;
	$attachment_id = false;
    
	// If there is no url, return.
	if ( '' == $attachment_url ) {
		return false;
	}
    
	// Get the upload directory paths.
	$upload_dir_paths = wp_upload_dir();
    
	// Make sure the upload path base directory exists in the attachment URL, to verify that we're working with a media library image.
	if ( false !== strpos( $attachment_url, $upload_dir_paths['baseurl'] ) ) {
        
		// If this is the URL of an auto-generated thumbnail, get the URL of the original image.
		$attachment_url = preg_replace( '/-\d+x\d+(?=\.(jpg|jpeg|png|gif)$)/i', '', $attachment_url );
        
		// Remove the upload path base directory from the attachment URL.
		$attachment_url = str_replace( $upload_dir_paths['baseurl'] . '/', '', $attachment_url );
        
		// Finally, run a custom database query to get the attachment ID from the modified attachment URL.
		$attachment_id = $wpdb->get_var( $wpdb->prepare( "SELECT wposts.ID FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta WHERE wposts.ID = wpostmeta.post_id AND wpostmeta.meta_key = '_wp_attached_file' AND wpostmeta.meta_value = '%s' AND wposts.post_type = 'attachment'", $attachment_url ) );
	}
	return $attachment_id;
}

/**
 * Add custom image sizes attribute to enhance responsive image functionality
 * for content images
 *
 * @param string $sizes A source size value for use in a 'sizes' attribute.
 * @param array  $size  Image size. Accepts an array of width and height
 *                      values in pixels (in that order).
 * @return string A source size value for use in a content image 'sizes' attribute.
 *
 * @since opening_times 1.0.0
 */
function opening_times_content_image_sizes_attr( $sizes, $size ) {
	$width = $size[0];
	
	840 <= $width && $sizes = '(max-width: 709px) 85vw, (max-width: 909px) 67vw, (max-width: 1362px) 62vw, 840px';
	
	if ( 'page' === get_post_type() ) {
		840 > $width && $sizes = '(max-width: ' . $width . 'px) 85vw, ' . $width . 'px';
	} else {
		840 > $width && 600 <= $width && $sizes = '(max-width: 709px) 85vw, (max-width: 909px) 67vw, (max-width: 984px) 61vw, (max-width: 1362px) 45vw, 600px';
		600 > $width && $sizes = '(max-width: ' . $width . 'px) 85vw, ' . $width . 'px';
	}

	return $sizes;
}
add_filter( 'wp_calculate_image_sizes', 'opening_times_content_image_sizes_attr', 10 , 2 );

/**
 * Add custom image sizes attribute to enhance responsive image functionality
 * for post thumbnails
 *
 * @param array $attr Attributes for the image markup.
 * @param int   $attachment Image attachment ID.
 * @param array $size Registered image size or flat array of height and width dimensions.
 * @return string A source size value for use in a post thumbnail 'sizes' attribute.
 *
 * @since opening_times 1.0.0
 */
function opening_times_post_thumbnail_sizes_attr( $attr, $attachment, $size ) {
	if ( 'post-thumbnail' === $size ) {
		is_active_sidebar( 'sidebar-1' ) && $attr['sizes'] = '(max-width: 709px) 85vw, (max-width: 909px) 67vw, (max-width: 984px) 60vw, (max-width: 1362px) 62vw, 840px';
		! is_active_sidebar( 'sidebar-1' ) && $attr['sizes'] = '(max-width: 709px) 85vw, (max-width: 909px) 67vw, (max-width: 1362px) 88vw, 1200px';
	}
	return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'opening_times_post_thumbnail_sizes_attr', 10 , 3 );

/**
 * Set the default image link in the Image Uploader to "None"
 * Prevents annoying and unnecessary linking to attachment posts.
 *
 * @link: http://andrewnorcross.com/tutorials/stop-hyperlinking-images/
 *
 * @since opening_times 1.0.0
 */
function opening_times_imagelink_setup() {
	$image_set = get_option( 'image_default_link_type' );
	
	if ( $image_set !== 'none' ) {
		update_option('image_default_link_type', 'none');
	}
}
add_action('admin_init', 'opening_times_imagelink_setup', 10);

