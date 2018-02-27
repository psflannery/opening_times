<?php
/**
 * SVG icons related functions and filters
 *
 * @package Opening Times
 */

/**
 * Add SVG definitions to <footer>.
 *
 * @since opening_times 1.0.0
 */
function opening_times_include_svg_icons() {
	// Define SVG sprite file.
	$svg_icons = get_template_directory() . '/img/svg-icons.svg';
    
	// If it exsists, include it.
	if ( file_exists( $svg_icons ) ) {
		require_once( $svg_icons );
	}
}
add_action( 'wp_footer', 'opening_times_include_svg_icons', 9999 );

/**
 * Output an SVG.
 * 
 * @param  string  $svg  filename to display.
 * @param  boolean $echo If true, echo the svg. Default true.
 * @return string        If false, return the svg.
 *
 * @since opening_times 1.0.0
 */
function opening_times_do_svg( $svg, $echo = true ) {

	// Define SVG logo file.
	$svg_logo = get_template_directory() . '/img/' . $svg;
	
	// If it exsists, include it.
	if ( file_exists( $svg_logo ) ) {
		if ( $echo ) {
			require_once( $svg_logo );
		}
		else {
			ob_start();
				require_once( $svg_logo );
			return ob_get_clean();
		}
	}
}

/**
 * Return SVG icon markup.
 *
 * @param  array  $args {
 *     Parameters needed to display an SVG.
 *
 *     @param string $icon Required. Use the icon filename, e.g. "facebook-square".
 *     @param string $title Optional. SVG title, e.g. "Facebook".
 *     @param string $desc Optional. SVG description, e.g. "Share this post on Facebook".
 * }
 * @return string SVG markup.
 *
 * @since opening_times 1.0.0
 */
function opening_times_get_svg_icon( $args = array() ) {
    
	// Make sure $args are an array.
	if ( empty( $args ) ) {
		return esc_html__( 'Please define default parameters in the form of an array.', 'opening_times' );
	}
    
	// YUNO define an icon?
	if ( false === array_key_exists( 'icon', $args ) ) {
		return esc_html__( 'Please define an SVG icon filename.', 'opening_times' );
	}
    
	// Set defaults.
	$defaults = array(
		'icon'        => '',
		'title'       => '',
		'desc'        => '',
		'aria_hidden' => true, // Hide from screen readers.
	);
    
	// Parse args.
	$args = wp_parse_args( $args, $defaults );

	// Set aria hidden.
	$aria_hidden = '';

	if ( true === $args['aria_hidden'] ) {
		$aria_hidden = ' aria-hidden="true"';
	}

	// Set ARIA.
	$aria_labelledby = '';

	if ( $args['title'] && $args['desc'] ) {
		$aria_labelledby = ' aria-labelledby="title desc"';
	}
    
	// Begin SVG markup.
	$svg = '<svg class="icon icon-' . esc_html( $args['icon'] ) . '"' . $aria_hidden . $aria_labelledby . ' role="img">';

	// If there is a title, display it.
	if ( $args['title'] ) {
		$svg .= '<title>' . esc_html( $args['title'] ) . '</title>';
	}
        
	// If there is a description, display it.
	if ( $args['desc'] ) {
		$svg .= '<desc>' . esc_html( $args['desc'] ) . '</desc>';
	}

	// Use absolute path in the Customizer so that icons show up in there.
	if ( is_customize_preview() ) {
		$svg .= '<use xlink:href="' . get_parent_theme_file_uri( 'img/svg-icons.svg#icon-' . esc_html( $args['icon'] ) ) . '"></use>';
	} else {
		$svg .= '<use xlink:href="#icon-' . esc_html( $args['icon'] ) . '"></use>';
	}

	$svg .= '</svg>';
    
	return $svg;
}

/**
 * Display an SVG icon.
 *
 * @param  array  $args  Parameters needed to display an SVG icon.
 *
 * @since opening_times 1.0.0
 */
function opening_times_do_svg_icon( $args = array() ) {
	echo opening_times_get_svg_icon( $args );
}
