<?php
/**
 * Opening Times functions and definitions
 *
 * @package Opening Times
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 960; /* pixels */
}

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
		'article',
		'projects'
	) );
	add_image_size( 'accordion-thumb', 300, 9999 ); //300 pixels wide (and unlimited height)
	add_image_size( 'accordion-retina', 600, 9999 ); //600 pixels wide (and unlimited height)

	// This theme uses wp_nav_menu() in two locations.
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'opening_times' ),
		'social' => __( 'Secondary Menu', 'opening_times' )
	) );

	// Enable support for Post Formats.
	add_theme_support( 'post-formats', array( 
		'chat',
		'link',
	) );
	
	// Enable support for HTML5 markup.
	add_theme_support( 'html5', array(
		'comment-list',	'search-form', 'comment-form', 'gallery', 'caption',
	) );
	
	// Add Editor Styles.
	add_editor_style( 'css/editor.css' );
	
	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'opening_times_background_args', array(
		'default-color' => 'ffffff',
	) ) );
}
endif; // opening_times_setup
add_action( 'after_setup_theme', 'opening_times_setup' );

/**
 * Register widgetized area and update sidebar with default widgets.
 */
function opening_times_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'About Dropdown Widget Area', 'opening_times' ),
		'id'            => 'sidebar-1',
		'before_widget' => '<div id="%1$s" class="widget %2$s clearfix" role="complementary">',
		'after_widget'  => '</div>',
		'before_title'  => '',
		'after_title'   => '',
	) );
	register_sidebar( array(
		'name'          => __( 'Mailing List Dropdown Widget Area', 'opening_times' ),
		'id'            => 'sidebar-2',
		'before_widget' => '<div id="%1$s" class="widget %2$s row" role="complementary">',
		'after_widget'  => '<div>',
		'before_title'  => '',
		'after_title'   => '',
	) );
}
add_action( 'widgets_init', 'opening_times_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function opening_times_scripts() {
	//register styles
	wp_register_style( 'opening_times-style', get_stylesheet_uri(), array(), '1.0' );

	//register scripts
	wp_register_script( 'opening_times-main', get_template_directory_uri() . '/js/main.min.js', array( 'jquery-ui-accordion' ), '30012015', true );
	wp_register_script( 'opening_times-plugins', get_template_directory_uri() . '/js/plugins.min.js', array( 'jquery' ), '15122013', true );
	
	//enqueue styles
	wp_enqueue_style( 'opening_times-style' );
	
	//enqueue scripts
	wp_enqueue_script( 'opening_times-plugins' );
	wp_enqueue_script( 'opening_times-main' );
		
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
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
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Admin customisations file.
 */
require get_template_directory() . '/inc/admin.php';

/**
 * Tidy up some of the default Wordpress output.
 */
require get_template_directory() . '/inc/tidy.php';
