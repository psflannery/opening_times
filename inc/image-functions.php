<?php
/**
 * Image related functions and filters
 *
 * @package Opening Times
 */

/**
 * Return Post thumbnail, with fallback
 * 
 * @param array $args {
 *     Optional. Default featured media arguments. Default empty array.
 *
 *     @type string       $before   Optional markup before the figure element. Default empty.
 *     @type string       $after    Optional markup after the figure element. Default empty.
 *     @type string       $size     Thumbnail size. Default thumbnail.
 *     @type bool         $fallback Whether to use a fallback image if no thumbnail is set. Default false.
 *     @type array        $attr     Array of attributes to apply to the figure element. Default empty.
 * }
 * @return string   Markup for Post thumbnail.
 *
 * @since opening_times 1.0.1
 */
function opening_times_get_the_post_thumbnail( $args = array() ) {
	$args = wp_parse_args( $args, array(
		'before'   => '',
		'after'    => '',
		'size'     => 'thumbnail',
		'fallback' => true,
		'attr'     => array(),
	) );

	// Grab the customizer settings.
	$placeholder = get_theme_mod( 'opening_times_placeholder' );

	$html = '';

	if ( has_post_thumbnail() ) {
		$html .= get_the_post_thumbnail(
			get_the_ID(),
			$args['size'],
			array( 
				'alt'   => opening_times_get_img_alt( get_post_thumbnail_id( get_the_ID() ), false ),
				'class' => 'lazyload'
			)
		);
	} else {
		if ( $args['fallback'] !== true ) {
			return;
		}

		if ( ! $placeholder ) {
			$html .= opening_times_get_svg_icon( array( 'icon' => 'placeholder' ) );
		}

		if ( class_exists( 'Jetpack_Lazy_Images' ) ) {
			$html .= sprintf(
				'<img src="%1$s" data-lazy-src="%2$s" class="attachment-thumbnail wp-post-image lazyload" alt="%3$s"/>',
				opening_times_lazy_placeholder_img(),
				esc_url( $placeholder ),
				esc_html( get_the_title() )
			);
		} else {
			$html .= sprintf(
				'<img src="%1$s" class="attachment-thumbnail wp-post-image" alt="%2$s"/>',
				esc_url( $placeholder ),
				esc_html( get_the_title() )
			);
		}
	}

	// Optionally add a caption.
	$fig_caption = opening_times_maybe_caption( get_post_thumbnail_id(), false );
	$html .= $fig_caption;

	// Add attributes to the figure element.
	$fig_attributes = array_map( 'esc_attr', $args['attr'] );
	$attr = '';

	foreach ( $fig_attributes as $name => $value ) {
		if ( ! empty( $value ) ) {
            $attr .= " $name=" . '"' . $value . '"';
        }
	}

	$featured_image = $args[ 'before' ] . '<figure'. $attr .'>' . $html . '</figure>' . $args[ 'after' ];
	
	return $featured_image;
}


/**
 * Echo Featured image, with fallback
 *
 * @see opening_times_get_the_featured_image()
 * 
 * @param  array  $args Array of arguments to apply to the featured image.
 * @return string       Markup for Post thumbnail.
 *
 * @since opening_times 1.0.1
 */
function opening_times_the_post_thumbnail( $args ) {
	echo opening_times_get_the_post_thumbnail( $args );
}


/**
 * Calculate image aspect ratio
 *
 * @param  string $attachment_id Image ID.
 * @param  string $size          Thumbnail size
 * @return string                Aspect ratio
 *
 * @since opening_times 1.0.1
 */
function opening_times_image_ratio( $attachment_id = null, $size = 'full' ) {
	if ( ! $attachment_id ) {
		return;
	}

	// Get image attributes
	$img_attributes = wp_get_attachment_image_src( $attachment_id, $size );

	// Calculate aspect ratio: h / w * 100%.
	$ratio = $img_attributes[2] / $img_attributes[1] * 100;

	// Print image ratio
	return esc_attr( $ratio ) . '%';
}


/**
 * Calculate image orientation
 * 
 * @param  string $attachment_id Image ID.
 * @param  string $size          Thumbnail size.
 * @return string                Image orientation.
 *
 * @since opening_times 1.0.1
 */
function opening_times_image_orientation( $attachment_id = null, $size = 'full' ) {
	if ( ! $attachment_id ) {
		return;
	}

	// Get image attributes
	$img_attributes = wp_get_attachment_image_src( $attachment_id, $size );

	// Calculate image orientation.
	$orientation = $img_attributes[1] < $img_attributes[2] ? 'portrait' : 'landscape';

	return esc_attr( $orientation );
}


/**
 * Return attached images.
 * 
 * @param  string       $attachment_id The attachment ID.
 * @param  string       $size          The attachment image size. Default `thumbnail`.
 * @param  string|array $attr          Array of attributes to apply to the figure element. Default empty.
 * @return string                      The attachment image.
 *
 * @since opening_times 1.0.1
 */
function opening_times_attached_image_html( $attachment_id = null, $size = 'thumbnail', $attr = '' ) {
	if ( ! $attachment_id ) {
		return;
	}

	$default_attr = array(
		'class' => 'lazyload',
    	'alt'   => opening_times_get_img_alt( $attachment_id, false ),
	);

	$attr = wp_parse_args( $attr, $default_attr );

	$html = wp_get_attachment_image( 
		$attachment_id, 
		$size, 
		false, 
		array( 
			'alt'   => $attr['alt'],
			'class' => $attr['class']
		) 
	);

	/**
     * Filters the attached image HTML.
     *
     * @param string       $html          The attached image HTML.
     * @param void         null           Null value so that the args can be matched and aligned to
     *                                    the post thumbnail values.
     * @param string       $attachment_id The attachment ID.
     * @param string|array $size          The image size. Image size or array of width and height
     *                                    values (in that order). Default 'post-thumbnail'.
     * @param string       $attr          Query string of attributes.
     *
     * @since opening_times 1.0.1
     */
	return apply_filters( 'ot_attached_image_html', $html, null, $attachment_id, $size, $attr );
}


/**
 * Return markup for section image attachments
 * 
 * @param  string       $attachment_id The attachment ID.
 * @param  string       $size          The image size.
 * @param  string|array $attr          Query string of attributes.
 * @return string                	   Markup for image attachments.
 *
 * @since opening_times 1.0.1
 */
function opening_times_get_the_attached_image( $attachment_id = null, $size = 'thumbnail', $attr = '' ) {
	if ( ! $attachment_id ) {
		return;
	}

	$default_attr = array(
		'before' => '',
		'after'  => '',
		'id'     => '',
		'class'  => 'w-50',
		'attr'   => array(),
	);

	$args = wp_parse_args( $attr, $default_attr );

	$html = opening_times_attached_image_html( $attachment_id, $size );

	// Optionally add a caption.
	$fig_caption = opening_times_maybe_caption( get_post_thumbnail_id(), false );
	$html .= $fig_caption;

	// Add attributes to the figure element.
	$fig_attributes = array_map( 'esc_attr', $args['attr'] );
	$attr = '';

	if ( $args['id'] ) {
        $attr .= ' id="' . esc_attr( $args['id'] ) . '"';
    }

    if ( $args['class'] ) {
        $attr .= ' class="' . esc_attr( $args['class'] ) . '"';
    }

    foreach ( $args['attr'] as $name => $value ) {
        if ( ! empty( $value ) ) {
            $attr .= " $name=" . '"' . $value . '"';
        }
    }

	$attached_image = $args[ 'before' ] . '<figure' . $attr . '>' . $html . '</figure>' . $args[ 'after' ];

	return $attached_image;
}

/**
 * Conditionally alter the post thumbnail html
 * 
 * @param  string       $html              Default post thumbnail html.
 * @param  int          $pid               Post ID.
 * @param  string       $post_thumbnail_id Post Thumbnail ID.
 * @param  string       $size              The post thumbnail size.
 * @param  string|array $attr              Query string of attributes.
 * @return string                          Modified post thumbnail html.
 *
 * @since opening_times 1.0.1
 */
function opening_times_filter_post_thumbnail_html( $html, $pid, $post_thumbnail_id, $size, $attr ) {
 
	if ( ! empty( $attr[ 'class' ] ) && $attr[ 'class' ] === 'lazyload' ) {

		$html = sprintf(
			'<div class="aspect-ratio" style="padding-bottom: %1$s">%2$s</div>',
			opening_times_image_ratio( $post_thumbnail_id, $size ),
			$html
		);
	}

	return $html;
}
add_filter( 'post_thumbnail_html', 'opening_times_filter_post_thumbnail_html', 10, 5 );
add_filter( 'ot_attached_image_html', 'opening_times_filter_post_thumbnail_html', 10, 5 );


/**
 * Conditionally alter the post attahment image html
 * 
 * @param  string       $html          Default section attachment html.
 * @param  int          $pid           Post ID.
 * @param  string       $attachment_id Section attachment ID.
 * @param  string       $size          The section attachment size.
 * @param  string|array $attr          Query string of attributes.
 * @return string                      Modified section attachment html.
 *
 * @since opening_times 1.0.1
 */
function opening_times_filter_post_attachment_html( $html, $pid, $attachment_id, $size, $attr ) {
	
	if( has_term( 'accordion-xl', 'format' ) ) {
		
		$html = wp_get_attachment_image( $attachment_id, '$size', false, array( 
			'class' => 'slide--fh img-cover lazyload',
		) );

	}

	return $html;
}
add_filter( 'ot_attached_image_html', 'opening_times_filter_post_attachment_html', 99, 5 );


/**
 * Display attachment caption, if there is one.
 * 
 * @param  string $attachment_id The attachment ID.
 * @param  boolean $echo         Echo caption if true, return caption if not.
 * @return string                Caption HTML.
 *
 * @since opening_times 1.0.1
 */
function opening_times_maybe_caption( $attachment_id = null, $echo = true ) {
	if ( ! $attachment_id ) {
		return;
	}

	$caption = wp_get_attachment_caption( $attachment_id );

	if ( empty( $caption ) ) {
		return;
	}

	$maybe_caption = '<figcaption class="wp-caption-text">' . $caption . '</figcaption>';

	if ( $echo )
		echo $maybe_caption;
	else
		return $maybe_caption;
}


/**
 * Get image alt text
 * 
 * @param  string  $image_id The image ID.
 * @param  boolean $echo     Echo alt text if true, return if not. Default true.
 * @return strong            The image alt text.
 *
 * @since opening_times 1.0.1
 */
function opening_times_get_img_alt( $image_id = null, $echo = true ) {
	if ( ! $image_id ) {
		return;
	}

	$img_alt = get_post_meta( $image_id, '_wp_attachment_image_alt', true );
	$img_title = the_title_attribute( array( 'echo' => '0', 'post' => $image_id ) );

	$alt_text = $img_alt ? $img_alt : $img_title;

	if ( $echo )
		echo $alt_text;
	else
		return $alt_text;
}
















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
