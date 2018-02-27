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
    	$classes[] = 'search-result card border-0';
    }

    if ( 'news' !== get_post_type() ) {
    	$classes[] = 'panel';
    }

    if ( ( is_page() && ! is_page_template( 'page-templates/2-column.php' ) ) && 'news' !== get_post_type() ) {
    //if ( is_page() && 'news' !== get_post_type() ) {
    	$classes[] = 'col-md-8 col-lg-6 mx-auto';
    }

    if ( is_page_template( 'page-templates/2-column.php' ) ) {
    	$classes[] = 'col columns-2-md';
    }

    $classes[] = 'card border-0 bg-transparent';
	
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
 * Determine if ajax request has come from the front end.
 * 
 * @return bool
 *
 * @link( https://wordpress.stackexchange.com/questions/34721/conditional-check-for-front-end-which-includes-ajax, link )
 *
 * @since Opening Times 1.0.1
 *
function opening_times_frontend_ajax_check() {

	define( 'FRONT_AJAX', true );

}
add_action( 'wp_ajax_opening_times_ajax_load_more', 'opening_times_frontend_ajax_check' );
add_action( 'wp_ajax_nopriv_opening_times_ajax_load_more', 'opening_times_frontend_ajax_check' );
*/


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
 * Output a transparent gif placeholder for lazyloading images.
 * 
 * @return string        1 x 1px gif placeholder
 *
 * @since Opening Times 1.0.1
 */
function opening_times_lazy_placeholder_img() {
    $placeholder = 'data:image/gif;base64,R0lGODdhAQABAPAAAP///wAAACwAAAAAAQABAEACAkQBADs=';

    return $placeholder;
}
add_filter( 'lazyload_images_placeholder_image', 'opening_times_lazy_placeholder_img' );


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
 *
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
*/


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
