<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package Opening Times
 */

/**
 * Get the Post Slug
 *
 * @link: http://www.tcbarrett.com/2011/09/wordpress-the_slug-get-post-slug-function/#.U0GiBfldWSo
 *
 * @since opening_times 1.0.0
 */
function opening_times_the_slug( $echo = true ) {
	$slug = basename( get_permalink() );
	
	do_action( 'before_slug', $slug );
    
	$slug = apply_filters( 'slug_filter', $slug );

	if( $echo ) 
		echo $slug;
		
	do_action( 'after_slug', $slug );

	return $slug;
}


/**
 * Get the Post Parent slug
 * 
 * @param  boolean $echo If true, echo the post parent slug.
 * @return string        If false, return the post parent slug.
 *
 * @since opening_times 1.0.0
 */
function opening_times_the_parent_slug( $echo = true ) {
    // Get an array of Ancestors and Parents if they exist
    $parents = get_post_ancestors( get_the_ID() );

    // Get the top Level page->ID count base 1, array base 0 so -1
    $id = ($parents) ? $parents[count($parents)-1] : $post->ID;
    
    // Get the parent and set the $class with the page slug (post_name)
    $parent = get_post( $id );
    $slug = $parent->post_name;

    if( $echo )
    	echo $slug;
    else
    	return $slug;
}


/**
 * Add a class to a specific post in the loop
 * 
 * @param  string  $class The class to add.
 * @param  integer $count Which post to apply the class to. Defaults to first post.
 * @return string         The defined class.
 *
 * @since Opening Times 1.0.0
 */
function opening_times_post_class_count( $class = '', $count = 0 ) {
	global $wp_query;

	if ( '' == $class )
		return;

	if ( $count === $wp_query->current_post )
		echo $class;
}


/**
 * Retrieve the format slug for a Reading post
 * 
 * @param  (int|object|null) $post Post ID or post object. Optional, default is the current post from the loop.
 * @return string                  Reading slug
 *
 * @since Opening Times 1.0.0
 */
function opening_times_get_reading_format( $post = null ) {
    if ( ! $post = get_post( $post ) )
        return false;
 
    $_format = get_the_terms( get_the_id(), 'format' );
 
    if ( empty( $_format ) )
        return false;
 
    $format = reset( $_format );
 
    return str_replace( 'reading-', '', $format->slug );
}


/**
 * Checks if the oembed is a Vimeo or YouTube video.
 *
 * @return bool The video check result. 
 *
 * @since Opening Times 1.0.0
 */
function opening_times_oembed_video_check( $url ) {
    $check = false;
    if ( strpos( $url, 'vimeo.com' ) !== false || strpos( $url, 'youtu.be' ) !== false || strpos( $url, 'youtube.com' ) !== false ) {
        $check = true;
    }
    
    return $check;
}


/**
 * Output a transparent gif placeholder for lazyloading images.
 * 
 * @param  boolean $echo return or echo content
 * @return string        1 x 1px gif placeholder
 *
 * @since Opening Times 1.0.0
 */
function opening_times_placeholder_img( $echo = true ) {
    $placeholder = 'data:image/gif;base64,R0lGODdhAQABAPAAAP///wAAACwAAAAAAQABAEACAkQBADs=';

    if ( $echo )
        echo $placeholder;
    else
        return $placeholder;
}

/**
 * Remove inline style attr fron figure shortcode 
 * 
 * @since Opening Times 1.0.0
 */
add_shortcode('wp_caption', 'fixed_img_caption_shortcode');
add_shortcode('caption', 'fixed_img_caption_shortcode');
function fixed_img_caption_shortcode($attr, $content = null) {
    
    // New-style shortcode with the caption inside the shortcode with the link and image tags.
    if ( ! isset( $attr['caption'] ) ) {
        if ( preg_match( '#((?:<a [^>]+>\s*)?<img [^>]+>(?:\s*</a>)?)(.*)#is', $content, $matches ) ) {
            $content = $matches[1];
            $attr['caption'] = trim( $matches[2] );
        }
    }

    // Allow plugins/themes to override the default caption template.
    $output = apply_filters('img_caption_shortcode', '', $attr, $content);
    if ( $output != '' )
        return $output;

    extract(shortcode_atts(array(
        'id'      => '',
        'align'   => 'alignnone',
        'width'   => '',
        'caption' => ''
    ), $attr));
    
    if ( 1 > (int) $width || empty($caption) )
        return $content;

    if ( $id ) $id = 'id="' . esc_attr($id) . '" ';
        return '<figure ' . $id . 'class="wp-caption ' . esc_attr($align) . '">' . do_shortcode( $content ) . '<figcaption class="wp-caption-text">' . $caption . '</figcaption></figure>';
}

/**
 * Build path data for current request.
 *
 * @return string|bool
 *
 * @since Opening Times 1.0.0
 */
function opening_times_get_request_path() {
    global $wp_rewrite;

    if ( $wp_rewrite->using_permalinks() ) {
        global $wp;
        
        // If called too early, bail
        if ( ! isset( $wp->request ) ) {
            return false;
        }
        
        // Determine path for paginated version of current request
        if ( false != preg_match( '#' . $wp_rewrite->pagination_base . '/\d+/?$#i', $wp->request ) ) {
            $path = preg_replace( '#' . $wp_rewrite->pagination_base . '/\d+$#i', $wp_rewrite->pagination_base . '/%d', $wp->request );
        } else {
            $path = $wp->request . '/' . $wp_rewrite->pagination_base . '/%d';
        }
        
        // Slashes everywhere we need them
        if ( 0 !== strpos( $path, '/' ) )
            $path = '/' . $path;
        $path = user_trailingslashit( $path );
    } else {
        
        // Clean up raw $_REQUEST input
        $path = array_map( 'sanitize_text_field', $_REQUEST );
        $path = array_filter( $path );
        $path['paged'] = '%d';
        $path = add_query_arg( $path, '/' );
    }
    
    return empty( $path ) ? false : $path;
}

/**
 * Return query string for current request, prefixed with '?'.
 *
 * @return string
 *
 * @since Opening Times 1.0.0
 */
function opening_times_get_request_parameters() {
    $uri = $_SERVER[ 'REQUEST_URI' ];
    $uri = preg_replace( '/^[^?]*(\?.*$)/', '$1', $uri, 1, $count );
    if ( $count != 1 ) {
        return '';
    }

    return $uri;
}

/**
 * Determine if a URL is an internal or external
 * 
 * @param  string $url            The URL to examine
 * @param  string $internal_class A class to apply to internal links
 * @param  string $external_class A class to apply to external links
 * @return array                  Array of attributes
 *
 * @link( https://stackoverflow.com/questions/25090563/php-determine-if-a-url-is-an-internal-or-external-url, source)
 *
 * @since opening_times 1.0.0
 */
function parse_external_url( $url = '', $internal_class = 'internal-link', $external_class = 'external-link') {
    // Abort if parameter URL is empty
    if( empty($url) ) {
        return false;
    }

    // Parse home URL and parameter URL
    $link_url = parse_url( $url );
    //$home_url = parse_url( $_SERVER['HTTP_HOST'] );     
    $home_url = parse_url( home_url() );

    // Decide on target
    if( empty($link_url['host']) ) {
        // Is an internal link
        $target = '';
        $rel = '';
        $class = $internal_class;

    } elseif( $link_url['host'] == $home_url['host'] ) {
        // Is an internal link
        $target = '';
        $rel = '';
        $class = $internal_class;

    } else {
        // Is an external link
        $target = '_blank';
        $rel = 'noopener';
        $class = $external_class;
    }

    // Return array
    $output = array(
        'class'     => $class,
        'target'    => $target,
        'rel'       => $rel,
        'url'       => $url
    );

    return $output;
}
