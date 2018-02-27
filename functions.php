<?php
/**
 * Opening Times functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Opening Times
 */

if ( ! function_exists( 'opening_times_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function opening_times_setup() {

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );
	
	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails', array( 
		'post', 
		'reading',
		'article',
	) );
	add_image_size( 'accordion-thumb', 600, 9999 ); //600 pixels wide (and unlimited height)

	// This theme uses wp_nav_menu() in three locations.
	register_nav_menus( array(
		'primary' => esc_html__( 'Primary Menu', 'opening_times' ),
		'info'    => esc_html__( 'Footer Menu', 'opening_times' ),
	) );

	// Enable support for Post Formats.
	add_theme_support( 'post-formats', array( 
		'link',
	) );
	
	// Enable support for HTML5 markup.
	add_theme_support( 'html5', array(
		'comment-list',	
		'search-form', 
		'comment-form', 
		'gallery', 
		'caption',
	) );
	
	// Add Editor Styles.
	add_editor_style( 'css/editor.css' );
	
	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'opening_times_background_args', array(
		'default-color' => 'ffffff',
	) ) );

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );
}
endif; // opening_times_setup
add_action( 'after_setup_theme', 'opening_times_setup' );


/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function opening_times_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'opening_times_content_width', 960 );
}
add_action( 'after_setup_theme', 'opening_times_content_width', 0 );


/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function opening_times_widgets_init() {

	// Define sidebars
	$sidebars = array(
		'sidebar-1'  => esc_html__( 'About Dropdown Widget Area', 'opening_times' ),
		'sidebar-2'  => esc_html__( 'Mailing List Dropdown Widget Area', 'opening_times' ),
		'sidebar-3'  => esc_html__( 'Footer Widget Area', 'opening_times' ),
		'sidebar-4'  => esc_html__( 'News Dropdown Widget Area', 'opening_times' ),
	);

	// Loop through each standard sidebar and register
	foreach ( $sidebars as $sidebar_id => $sidebar_name ) {
		register_sidebar( array(
			'name'          => $sidebar_name,
			'id'            => $sidebar_id,
			'description'   => sprintf ( esc_html__( 'Widget area for %s', 'opening_times' ), $sidebar_name ),
			'before_widget' => '<div id="%1$s" class="widget %2$s" role="complementary">',
			'after_widget'  => '</div>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		) );
	}
}
add_action( 'widgets_init', 'opening_times_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function opening_times_scripts() {
	/**
	 * If WP is in script debug, or we pass ?script_debug in a URL - set debug to true.
	 */
	$debug = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG == true ) || ( isset( $_GET['script_debug'] ) ) ? true : false;

	$version = '1.0.0';

	/**
	 * Should we load minified files?
	 */
	$suffix = ( true === $debug ) ? '' : '.min';
	
	wp_enqueue_style( 'opening-times-style', get_stylesheet_uri() );

	wp_enqueue_script( 'opening-times-plugins', get_template_directory_uri() . '/js/plugins.min.js', array(), $version, true );

	wp_enqueue_script( 'opening-times-main', get_template_directory_uri() . '/js/main' . $suffix . '.js', array( 'jquery' ), $version, true );

		
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	/*
	if ( is_home() || is_front_page() || is_archive() || is_search() ) {
		global $wp_rewrite;
		
		wp_enqueue_script( 'opening-times-backbone-loop', get_template_directory_uri() . '/js/loop.js', array( 'jquery', 'backbone', 'underscore', 'wp-api'  ), $version, true );
		
		$queried_object = get_queried_object();
		
		$local = array(
			'loopType' => 'home',
			'queriedObject' => $queried_object,
			'pathInfo' => array(
				//'author_permastruct' => $wp_rewrite->get_author_permastruct(),
				'host' => preg_replace( '#^http(s)?://#i', '', untrailingslashit( get_option( 'home' ) ) ),
				'path' => opening_times_get_request_path(),
				'use_trailing_slashes' => $wp_rewrite->use_trailing_slashes,
				'parameters' => opening_times_get_request_parameters(),
			),
		);
		
		if ( is_category() || is_tag() || is_tax() ) {
			$local['loopType'] = 'archive';
			$local['taxonomy'] = get_taxonomy( $queried_object->taxonomy );
		} 
		elseif ( is_search() ) {
			$local['loopType'] = 'search';
			$local['searchQuery'] = get_search_query();
		} 
		elseif ( is_author() ) {
			$local['loopType'] = 'author';
		}
		
		//set the page we're on so that Backbone can load the proper state
		if ( is_paged() ) {
			$local['page'] = absint( get_query_var( 'paged' ) ) + 1;
		}
		
		wp_localize_script( 'opening-times-backbone-loop', 'settings', $local );
	}
	*/

}
add_action( 'wp_enqueue_scripts', 'opening_times_scripts' );

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Load custom filters and hooks.
 */
require get_template_directory() . '/inc/hooks.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

/**
 * Load custom queries.
 */
require get_template_directory() . '/inc/queries.php';

/**
 * Load custom endpoints.
 */
//require get_template_directory() . '/inc/endpoints.php';

/**
 * Load custom CMB2 template tags.
 */
require get_template_directory() . '/inc/cmb2-template-tags.php';

/**
 * Load custom CMB2 features.
 */
require get_template_directory() . '/inc/cmb2-content-blocks.php';

/**
 * Load Admin customisations file.
 */
require get_template_directory() . '/inc/admin.php';

/**
 * Tidy up some of the default Wordpress output.
 */
require get_template_directory() . '/inc/tidy.php';

/**
 * SVG icons functions and filters.
 */
require get_template_directory() . '/inc/icon-functions.php';

/**
 * Image related functions and filters.
 */
require get_template_directory() . '/inc/image-functions.php';

/**
 * Video related functions and filters.
 */
require get_template_directory() . '/inc/video-functions.php';
