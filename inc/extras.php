<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package Opening Times
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
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
 */
function opening_times_post_classes( $classes, $class, $post_id ) {
    if ( ( !is_page() && !is_post_type_archive( 'reading' ) ) || ( is_post_type_archive( 'reading' ) && 'article' == get_post_type() ) || is_page_template( 'page-templates/bv-submitted-links.php' )  ) {
		$classes[] = 'strap-container';
		$classes[] = 'veiled';
    }
	
	if ( 'reading' == get_post_type() ) {
		$classes[] = 'editor-intro';
	}
  
	if ( is_search() ) {
		$classes[] = 'strap-container';
	}
 
    return $classes;
}
add_filter( 'post_class', 'opening_times_post_classes', 10, 3 );

/**
 * Add Search Form To A WordPress Menu
 *
 * @link: http://www.paulund.co.uk/add-search-form-to-a-wordpress-menu
 */
function opening_times_nav_search_form($items, $args) {
	if( $args->theme_location == 'social' )
		$items .= '<li class="menu-item expanding-search">' . get_search_form( false ) . '</li>';
	return $items;
}
add_filter('wp_nav_menu_items', 'opening_times_nav_search_form', 10, 2);

/**
 * Add a data attribute to specified menu items to allow for toggling of the dropdowns
 *
 * @link: http://wordpress.stackexchange.com/questions/121123/how-to-add-a-data-attribute-to-a-wordpress-menu-item
 */
function opening_times_menu_atts( $atts, $item, $args ) {
	// The ID of the target menu item
	$about_target = get_theme_mod( 'ot_about_menu_ID' );
	$mailing_target = get_theme_mod( 'ot_mailing-list_menu_ID' );

	if ($item->ID == $about_target && '' != $about_target) {
		$atts['data-toggle-id'] = 'about';
	}
	if ($item->ID == $mailing_target && '' != $mailing_target) {
		$atts['data-toggle-id'] = 'mailing-list';
	}
	return $atts;
}
add_filter( 'nav_menu_link_attributes', 'opening_times_menu_atts', 10, 3 );

/**
 * Show all the posts in the Loop
 */
function opening_times_all_the_posts($query) {
    if( $query->is_main_query() && !is_admin() ) {
		$query->set('posts_per_page', '-1');
    }
}
add_action('pre_get_posts', 'opening_times_all_the_posts');

/**
 * Show Custom Post Types in the archive.
 */
function opening_times_add_custom_types_to_tax( $query ) {
	if( is_category() || is_tag() && empty( $query->query_vars['suppress_filters'] ) ) {

		// Get all your post types
		$post_types = get_post_types();

		$query->set( 'post_type', $post_types );
		return $query;
	}
}
add_filter( 'pre_get_posts', 'opening_times_add_custom_types_to_tax' );

/**
 * Show Authors used in Custom Post Types in the author archive.
 */
function opening_times_author_archive($query) {
    if ( $query->is_author )
         $query->set( 'post_type', array('post', 'article') );
    remove_action( 'pre_get_posts', 'opening_times_author_archive' );
}
add_action('pre_get_posts', 'opening_times_author_archive');

/**
 * Echo the Post Slug
 *
 * @link: http://www.tcbarrett.com/2011/09/wordpress-the_slug-get-post-slug-function/#.U0GiBfldWSo
 */
function opening_times_the_slug( $echo=true ){
	$slug = basename( get_permalink() );
	do_action( 'before_slug', $slug );
	$slug = apply_filters( 'slug_filter', $slug );
	if( $echo ) echo $slug;
	do_action( 'after_slug', $slug );
	return $slug;
}

/**
 * Set the default image link in the Image Uploader to "None"
 * Prevents annoying and unnecessary linking to attachment posts.
 *
 * @link: http://andrewnorcross.com/tutorials/stop-hyperlinking-images/
 */
function opening_times_imagelink_setup() {
	$image_set = get_option( 'image_default_link_type' );
	
	if ( $image_set !== 'none' ) {
		update_option('image_default_link_type', 'none');
	}
}
add_action('admin_init', 'opening_times_imagelink_setup', 10);

/**
 * Add a `screen-reader-text` class to the search form's submit button.
 * Add a `form-control` class to the search form.
 *
 * @since Opening Times 1.3.0
 *
 * @param string $html Search form HTML.
 * @return string Modified search form HTML.
 */
function opening_times_search_form_modify( $html ) {	
	$html = str_replace( 'class="search-submit"', 'class="search-submit screen-reader-text"', $html );
	$html = str_replace( 'class="search-field"', 'class="search-field form-control"', $html );
	return $html;
}
add_filter( 'get_search_form', 'opening_times_search_form_modify' );

/**
 * Lazy Load the iframe oembeds in the Reading Section and Archives.
 *
 * @since Opening Times 1.3.0
 */

function opening_times_lazy_load_iframes($html, $url, $attr) {
	if ( is_home() || is_archive() || is_singular( 'reading' ) ) {
		$html = str_replace( 'src="', 'src="about:blank" data-src="', $html );
		return $html;
	} else {
        return $html;
    }
}
add_filter('embed_oembed_html', 'opening_times_lazy_load_iframes', 10, 3);

/**
 * JavaScript Detection.
 *
 * Adds a `js` class to the root `<html>` element when JavaScript is detected.
 *
 * @since Opening Times 1.3.0
 * @props Twenty Fifteen 1.1
 */
function opening_times_javascript_detection() {
	echo "<script>(function(html){html.className = html.className.replace(/\bno-js\b/,'js')})(document.documentElement);</script>\n";
}
add_action( 'wp_head', 'opening_times_javascript_detection', 0 );

/**
 * Retina Images
 *
 * Checks to see if the uploaded file is an image. If it is, then it processes it using the opening_times_retina_support_create_images() function
 *
 * @since Opening Times 1.3.0
 * @link: http://code.tutsplus.com/tutorials/ensuring-your-theme-has-retina-support--wp-33430
 */
function opening_times_retina_support_attachment_meta( $metadata, $attachment_id ) {
    foreach ( $metadata as $key => $value ) {
        if ( is_array( $value ) ) {
            foreach ( $value as $image => $attr ) {
                if ( is_array( $attr ) )
                    opening_times_retina_support_create_images( get_attached_file( $attachment_id ), $attr['width'], $attr['height'], true );
            }
        }
    }
 
    return $metadata;
}
add_filter( 'wp_generate_attachment_metadata', 'opening_times_retina_support_attachment_meta', 10, 2 );
 
/**
 * Create retina-ready images
 *
 * Referenced via retina_support_attachment_meta().
 * A new retina-ready image will be created at double the size of the original. Plus @2x will be added to the filename
 *
 * @since Opening Times 1.3.0
 */
function opening_times_retina_support_create_images( $file, $width, $height, $crop = false ) {
    if ( $width || $height ) {
        $resized_file = wp_get_image_editor( $file );
        if ( ! is_wp_error( $resized_file ) ) {
            $filename = $resized_file->generate_filename( $width . 'x' . $height . '@2x' );
 
            $resized_file->resize( $width * 2, $height * 2, $crop );
            $resized_file->save( $filename );
 
            $info = $resized_file->get_size();
 
            return array(
                'file' => wp_basename( $filename ),
                'width' => $info['width'],
                'height' => $info['height'],
            );
        }
    }
    return false;
}

/**
 * Delete retina-ready images
 *
 * This function is attached to the 'delete_attachment' filter hook.
 * If a user deletes an image from the Media Library, let's trash all the extra retina-ready images that were created too.
 *
 * @since Opening Times 1.3.0
 */
function opening_times_delete_retina_support_images( $attachment_id ) {
    $meta = wp_get_attachment_metadata( $attachment_id );
    $upload_dir = wp_upload_dir();
    $path = pathinfo( $meta['file'] );
    foreach ( $meta as $key => $value ) {
        if ( 'sizes' === $key ) {
            foreach ( $value as $sizes => $size ) {
                $original_filename = $upload_dir['basedir'] . '/' . $path['dirname'] . '/' . $size['file'];
                $retina_filename = substr_replace( $original_filename, '@2x.', strrpos( $original_filename, '.' ), strlen( '.' ) );
                if ( file_exists( $retina_filename ) ) {
                    unlink( $retina_filename );
                }
            }
        }
    }
}
add_filter( 'delete_attachment', 'opening_times_delete_retina_support_images' );

/**
 * Add data attribute to images
 *
 * Required by Retina.js
 *
 * @since Opening Times 1.3.0
 */
function opening_times_add_retina_images( $content ) {
    // Don't add retina if the content has already been run through previously
    if ( false !== strpos( $content, 'data-at2x' ) )
    return $content;
        
    $pattern = "/<img(.*?)src=('|\")(.*?).(bmp|gif|jpeg|jpg|png)('|\")(.*?)>/i";
    $replacement = '<img${1}src=${2}${3}.${4}${5} data-at2x=${2}${3}@2x.${4}${5}${6}>';
    $content = preg_replace( $pattern, $replacement, $content );
    return $content;
}
add_filter( 'the_content', 'opening_times_add_retina_images', 99 ); // run this later, so other content filters have run, including image_add_wh on WP.com
add_filter( 'post_thumbnail_html', 'opening_times_add_retina_images', 11 );
