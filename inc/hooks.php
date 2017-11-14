<?php
/**
 * Action hooks and filters.
 *
 * A place to put hooks and filters that aren't necessarily template tags.
 *
 * @package Opening Times
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 *
 * @since opening_times 1.0.0
 */
function opening_times_body_classes( $classes ) {
	if ( 'reading' == get_post_type() && is_singular( 'reading' ) ) {
		$classes[] = 'post-type-archive-reading';
	}
	
	if ( get_background_image() ) {
		$classes[] = 'custom-background-active';
	}
	
	return $classes;
}
add_filter( 'body_class', 'opening_times_body_classes' );


/**
 * Adds custom classes to the array of post classes.
 *
 * @param array $classes Classes for the posts.
 * @return array
 *
 * @since opening_times 1.0.0
 *
 */
function opening_times_post_classes( $classes, $class, $post_id ) {
 
    if ( is_search() ) {
    	$classes[] = 'search-result';
    }

    if ( 'news' !== get_post_type() ) {
    	$classes[] = 'panel';
    }

    $classes[] = 'card';
	
    return $classes;
}
add_filter( 'post_class', 'opening_times_post_classes', 10, 3 );


/**
 * Adds custom classes to the array of menu classes.
 *
 * @param array $classes Classes for the menus.
 * @return array
 *
 * @since opening_times 1.0.0
 *
 */
function opening_times_menu_classes( $classes, $item, $args ) {
	if( $args->theme_location == 'primary' ) {
		$classes[] = 'nav-item';
	}

	return $classes;
}
add_filter( 'nav_menu_css_class', 'opening_times_menu_classes', 10, 3 );


/**
 * Add a data attribute to specified menu items to allow for toggling of the dropdowns
 *
 * @link: http://wordpress.stackexchange.com/questions/121123/how-to-add-a-data-attribute-to-a-wordpress-menu-item
 *
 * @since opening_times 1.0.0
 */
function opening_times_menu_atts( $atts, $item, $args ) {
	// The ID of the target menu item
	$about_target = get_theme_mod( 'opening_times_about_menu_ID' );
	$news_target = get_theme_mod( 'opening_times_news_menu_ID' );

	//$pages = array( 'home', 'reading' );

	if( $args->theme_location == 'primary' ) {
		$class = 'nav-link';

        // Make sure not to overwrite any existing classes
        $atts['class'] = ( !empty( $atts['class'] ) ) ? $atts['class'] .' '. $class : $class;
	}

	if ( ( $item->ID == $about_target && '' != $about_target ) || ( $item->ID == $news_target && '' != $news_target ) ) {
		$atts['data-toggle'] = 'collapse';
		$atts['aria-expanded'] = 'false';
		$atts['aria-controls'] = $item->ID == $about_target ? 'collapse-about' : 'collapse-news';
	}

	/*
	 * Add an attribute if a news post has been published in the last week
	 */
	
	// Loop through news posts
	$args = array( 
		'post_type'      =>'news', 
		'posts_per_page' => 1
	);
	$posts = get_posts( $args );

	// Get the ID of the first post in the loop
	$first_id = $posts[0]->ID;

	if ( ($item->ID == $news_target && '' != $news_target) && get_the_date('U', $first_id) >= strtotime('-1 week') ) {
		$atts['data-test'] = 'foo';
	}

	return $atts;
}
add_filter( 'nav_menu_link_attributes', 'opening_times_menu_atts', 10, 3 );


/**
 * Add a pingback url auto-discovery header for singularly identifiable articles.
 *
 * @return string
 *
 * @since opening_times 1.0.0
 */
function opening_times_pingback_header() {
	if ( is_singular() && pings_open() ) {
		echo '<link rel="pingback" href="', esc_url( get_bloginfo( 'pingback_url' ) ), '">';
	}
}
add_action( 'wp_head', 'opening_times_pingback_header' );


/**
 * Filter the Search Form
 * 
 * @param  string $form Search form markup
 * @return string       Search form with adjusted class and placeholder text
 *
 * @since Opening Times 1.0.0
 */
function opening_times_search_form( $form ) {
    $form = '<form role="search" method="get" class="search-form" action="' . esc_url( home_url( '/' ) ) . '">
                <label>
                    <span class="screen-reader-text">' . _x( 'Search for:', 'label' ) . '</span>
                    <input type="search" class="search-field form-control" placeholder="Search" value="' . get_search_query() . '" name="s" />
                </label>
                <input type="submit" class="search-submit screen-reader-text" value="'. esc_attr_x( 'Search', 'submit button' ) .'" />
            </form>';

    return $form;
}
add_filter( 'get_search_form', 'opening_times_search_form', 10 );


/**
 * Filter oembed URL for Vimeo and YouTube videos - add query args.
 *
 * @param  string       $provider The URL to the oEmbed provider.
 * @param  string       $url      The URL to the content that is desired to be embedded.
 * @param  array|string $args     Optional. Arguments, usually passed from a shortcode. Default empty.
 *
 * @return false|object False on failure, otherwise the result in the form of an object.
 *
 * @since Opening Times 1.0.0
 */
function opening_times_oembed_url( $cache ) {   

    if( ! is_admin() && opening_times_oembed_video_check( $cache ) !== false ) {
        
        if( 'reading' === get_post_type() && has_term( array( 'accordion-xl' ), 'format' ) ) {

	        // Get CMB2 field
	        $query_args = get_post_meta( get_the_ID(), '_ot_panel_slide', true );
	        
	        foreach ( (array) $query_args as $key => $query_arg ) {       
	            if ( isset( $query_arg['media_atts'] ) && ! empty( $query_arg['media_atts'] ) ) {
					foreach( $query_arg['media_atts'] as $key => $attribute ) {
						strpos( $attribute, 'auto-play' ) !== false ? $maybe_autoplay = 1 : $maybe_autoplay = 0;
						strpos( $attribute, 'loop' ) !== false ? $maybe_loop = 1 : $maybe_loop = 0;
						strpos( $attribute, 'controls' ) !== false ? $maybe_controls = 1 : $maybe_controls = 0;
						strpos( $attribute, 'muted' ) !== false ? $maybe_mute = 1 : $maybe_mute = 0;
	                }
	            }
	        }
	       
			if ( strpos( $cache, 'vimeo.com' ) !== false ) {
				// Ref: https://vimeo.com/forums/topic:278001			
				$provider = preg_replace("@src=(['\"])?([^'\">\s]*)@", "src=$1$2?background=1&autoplay=" . $maybe_autoplay . "&loop=" . $maybe_loop . "&mute=" . $maybe_mute . "",  $cache);
				
				return  $provider;
			} else {
				// Ref: https://developers.google.com/youtube/player_parameters
				$provider = preg_replace("@src=(['\"])?([^'\">\s]*)@", "src=$1$2&enablejsapi=1&loop=" . $maybe_loop . "&controls=" . $maybe_controls . "&modestbranding='1'",  $cache);

				return  $provider;
			}
    	}

    	if( 'post' === get_post_type() ) {
			if ( strpos( $cache, 'youtu.be' ) !== false || strpos( $cache, 'youtube.com' ) !== false ) {
				$provider = preg_replace("@src=(['\"])?([^'\">\s]*)@", "src=$1$2&enablejsapi=1",  $cache);
				
				return  $provider;
			}
    	}
    }
        
    return $cache;

}
add_filter( 'embed_oembed_html', 'opening_times_oembed_url' );
add_filter( 'oembed_result', 'opening_times_oembed_url', 10, 3);

/**
 * Filter oembed html - customise markup and query params.
 * Lazy Load the iframe oembeds in the Reading Section and Archives.
 * 
 * @param  string $cache   The cached HTML result, stored in post meta.
 * @param  string $url     The attempted embed URL.
 * @param  array  $attr    An array of shortcode attributes.
 * @param  int    $post_id Post ID.
 *
 * @return string          The altered markup
 *
 * @link( http://tutorialshares.com/youtube-oembed-urls-remove-showinfo/, link)
 *
 * @since Opening Times 1.0.0
 */
function opening_times_oembed_html( $cache, $url, $attr, $post_id ) {
    if( ! is_admin() && opening_times_oembed_video_check( $cache ) !== false ) {
        
        // Give the video a unique id
        $unique_id = 'ot-video-' . rand();
        
        // Add extra attributes to iframe HTML
		if ( is_home() || is_archive() ) {
			$attributes = 'id="' . $unique_id . '" width="100%" height="100%" class="lazyload"';
		} else {
			$attributes = 'id="' . $unique_id . '" width="100%" height="100%"';
		}
        
        // Create the new oembed HTML.
		$cache = str_replace( '<iframe', '<iframe ' . $attributes . '', $cache );
        
        // Return the new oembed markup
		if ( is_home() || is_archive() ) {
			return '<div class="embed-responsive embed-responsive-16by9">' . str_replace( 'src="', 'src="about:blank" data-src="', $cache ) . '</div>';
		} else {
			return '<div class="embed-responsive embed-responsive-16by9">' . $cache . '</div>';
		}
    } else {
    	return $cache;
    } 
}
add_filter( 'embed_oembed_html', 'opening_times_oembed_html', 99, 4 );


/**
 * Redirect Single posts
 * 
 * @since Opening Times 1.3.0
 */
function opening_times_redirect_singular() {
	$home_slug = home_url( '/#' . opening_times_the_slug( $echo=false ) );
	
	if ( 'post' === get_post_type() ) {
		is_singular()
			and wp_redirect( $home_slug, 301 )
			and exit;
	}
}
add_action( 'template_redirect', 'opening_times_redirect_singular' );


/**
 * Enable custom mime types.
 *
 * @param array $mimes Current allowed mime types.
 * @return array Updated allowed mime types.
 *
 * @since opening_times 1.0.0
 */
function opening_times_custom_mime_types( $mimes ) {
	$mimes['svg'] = 'image/svg+xml';
	$mimes['svgz'] = 'image/svg+xml';
	return $mimes;
}
add_filter( 'upload_mimes', 'opening_times_custom_mime_types' );


/**
 * This removes the annoying […] to a Read More link
 *
 * @return string Read more HTML.
 *
 * @since Opening Times 1.0.0
 */
function opening_times_excerpt_more( $more ) {
	return '...  <a class="excerpt-read-more" href="'. get_permalink( get_the_ID() ) . '" title="'. __('Read more...', 'opening_times') . get_the_title( get_the_ID() ).'">'. __('Read more...', 'opening_times') .'</a>';
}
add_filter('excerpt_more', 'opening_times_excerpt_more');


/**
 * Define a default term, so that all posts have at least one format.
 * 
 * @return array Default term
 *
 * @since Opening Times 1.0.0
 */
function opening_times_insert_default_reading_format() {
	wp_insert_term(
		'Standard',
		'format',
		array(
			'description' => '',
			'slug' 		  => 'standard'
		)
	);
}
add_action( 'init', 'opening_times_insert_default_reading_format' );


/**
 * Define the Reading Post Format Terms
 * 
 * @return array Post format terms
 *
 * @since Opening Times 1.0.0
 *
 * Repeat for each format needed
 */
function opening_times_insert_reading_formats() {
	wp_insert_term(
		'Text',
		'format',
		array(
			'description' => 'A central column of text.',
			'slug'  	  => 'text'
		)
	);
}
add_action( 'init', 'opening_times_insert_reading_formats' );


/**
 * Set a default Format
 * 
 * @param  int    $post_id The post id
 * @param  [type] $post    post
 * @return array           post format term
 *
 * @since Opening Times 1.0.0
 */
function opening_times_default_format_term( $post_id, $post ) {
    if ( 'publish' === $post->post_status ) {
        $defaults = array(
            'format' => 'standard',
        );

        $taxonomies = get_object_taxonomies( $post->post_type );

        foreach ( (array) $taxonomies as $taxonomy ) {
            $terms = wp_get_post_terms( $post_id, $taxonomy );

            if ( empty( $terms ) && array_key_exists( $taxonomy, $defaults ) ) {
                wp_set_object_terms( $post_id, $defaults[$taxonomy], $taxonomy );
            }
        }
    }
}
add_action( 'save_post', 'opening_times_default_format_term', 100, 2 );


// TEMP - will use CMB2 to create custom metabox
// replace checkboxes for the format taxonomy with radio buttons and a custom meta box
function opening_times_term_radio_checklist( $args ) {
    if ( ! empty( $args['taxonomy'] ) && $args['taxonomy'] === 'format' ) {
    	// Don't override 3rd party walkers.
        if ( empty( $args['walker'] ) || is_a( $args['walker'], 'Walker' ) ) {
            if ( ! class_exists( 'OT_Walker_Category_Radio_Checklist' ) ) {
                class OT_Walker_Category_Radio_Checklist extends Walker_Category_Checklist {
                    function walk( $elements, $max_depth, $args = array() ) {
                        $output = parent::walk( $elements, $max_depth, $args );
                        $output = str_replace(
                            array( 
                            	'type="checkbox"', 
                            	"type='checkbox'" 
                            ),
                            array( 
                            	'type="radio"', 
                            	"type='radio'" 
                            ),
                            $output
                        );
                        return $output;
                    }
                }
            }
            $args['walker'] = new OT_Walker_Category_Radio_Checklist;
        }
    }
    return $args;
}
add_filter( 'wp_terms_checklist_args', 'opening_times_term_radio_checklist' );
