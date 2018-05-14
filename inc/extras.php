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
 * @param  boolean $echo If true, echo the post slug. If false, return it. Default true.
 * @return string        The post slug.
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
 * @param  boolean $echo If true, echo the post parent slug. If false, return it. Default true.
 * @return string        The post parent slug.
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
 * Remove inline style attr fron figure shortcode 
 * 
 * @since Opening Times 1.0.0
 */
add_shortcode('wp_caption', 'fixed_img_caption_shortcode');
add_shortcode('caption', 'fixed_img_caption_shortcode');
function fixed_img_caption_shortcode( $attr, $content = null ) {
    
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
 * Determine if internal or external link
 * 
 * @param  string $url The link to examine.
 * @return bool        False if internal link, True if external link.
 *
 * @link( https://stackoverflow.com/questions/25090563/php-determine-if-a-url-is-an-internal-or-external-url, source)
 *
 * @since opening_times 1.0.1
 */
function opening_times_has_external_url( $url ) {
    // Abort if no URL
    if( empty( $url ) ) {
        return false;
    }

    // Parse home URL and parameter URL
    $link_url = parse_url( $url );

    //$home_url = parse_url( $_SERVER['HTTP_HOST'] );     
    $home_url = parse_url( home_url() );

    if( empty( $link_url['host'] ) ) {
        // Is a relative internal link
        $external_url = false;
    } elseif( $link_url['host'] == $home_url['host'] ) {
        // Is an absolute internal link
        $external_url = false;
    } else {
        // Is an external link
        $external_url = true;
    }

    return $external_url;
}


/**
 * Markup for featured links
 * 
 * @param  string $url    The link url. Required.
 * @param  array  $args   {
 *     Array of attributes to apply to the link element. Default empty.
 *
 *     @type string $anchor The link target. Defaults to URL
 *     @type string $id     The link ID. Default empty
 *     @type string $class  The link classes. Default `featured-link word-wrap`
 *     @type array  $attr   Additional attributes to be applied to the link. Default empty.
 * }
 * @param  string $before Optional Markup to prepend the link. Default empty.
 * @param  string $after  Optional Markup to append the link. Default empty.
 * @return string         The link HTML.
 *
 * @since opening_times 1.0.1
 */
function opening_times_get_featured_link_html( $url = null, $args = array(), $before = '', $after = '' ) {
    // Abort if no URL
    if( empty( $url ) ) {
        return false;
    }

    $url = esc_url( $url );

    $args = wp_parse_args( $args, array(
        'anchor' => $url,
        'id'     => '',
        'class'  => 'featured-link word-wrap',
        'attr'   => array(),
    ) );
    
    $link = '';

    $args['attr'] = array_map( 'esc_attr', $args['attr'] );

    // Set attributes
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

    $link .= sprintf(
        '<a href="%1$s" %2$s>%3$s</a>',
        $url,
        $attr,
        $args['anchor']
    );
    
    $featured_link = $before . $link . $after;
    
    return $featured_link;
}


/**
 * Echo markup for featured links
 * 
 * @param  string $url  The link url. Required.
 * @param  array  $args Array of attributes to apply to the link element. Default empty.
 * @return string       The link HTML.
 *
 * @since opening_times 1.0.1
 */
function opening_times_the_featured_link_html( $url, $args = array(), $before = '', $after = '' ) {
    echo opening_times_get_featured_link_html( $url, $args, $before, $after );
}


/**
 * Display the attributes for the post div.
 * 
 * @param  boolean $echo If true, echo the post attributes
 * @return array        Array of post attributes
 *
 * @since Opening Times 1.0.0
 */
function opening_times_post_attributes( $echo = true ) {
    $data_attributes = array();

    $speed_reader = get_post_meta( get_the_ID(), '_ot_speed_read', true );

    if ( $speed_reader ) {
        $data_attributes[] = 'data-text="' . esc_attr ('split') . '" data-toggle="' . esc_attr ('theme') . '"';
    }

    //$data_attributes = array_map( 'esc_attr', $data_attributes );
    $ouput =  join( ' ', $data_attributes );

    if ( $echo ) {
        echo $ouput;
    } else {
        return $ouput;
    }
};


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
 *
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
        // Is a relative internal link
        $target = '';
        $rel = '';
        $class = $internal_class;

    } elseif( $link_url['host'] == $home_url['host'] ) {
        // Is an absolute internal link
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
*/
