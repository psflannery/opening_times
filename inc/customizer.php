<?php
/**
 * Opening Times Theme Customizer
 *
 * @package Opening Times
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function opening_times_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport              = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport       = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport      = 'postMessage';
	$wp_customize->get_setting( 'background_color' )->transport      = 'postMessage';
	$wp_customize->get_setting( 'background_image' )->transport      = 'postMessage';
	$wp_customize->get_setting( 'background_repeat' )->transport     = 'postMessage';
	$wp_customize->get_setting( 'background_position_x' )->transport = 'postMessage';
	$wp_customize->get_setting( 'background_attachment' )->transport = 'postMessage';

	/**-----------------------------------------------------------
	 * Site Identity
	 *-----------------------------------------------------------*/

	// Add custom description to controls or sections.
    $wp_customize->get_control( 'blogdescription' )->description  = __( 'Tagline is hidden in this theme.', 'opening_times' );

	/**-----------------------------------------------------------
	 * Arts Council Link
	 *-----------------------------------------------------------*/
	
	// Add the Arts Council Link Section
	$wp_customize->add_section(	
		'ot_arts_council_link', 
		array(
			'title'     => __( 'Arts Council Link', 'opening_times' ),
			'description' => sprintf( __( 'The Link for the Arts Council Logo in the footer', 'opening_times' ) ),
			'priority'  => 130,
		)
	);
	
	// Add the  Arts Council Link Setting and Control
	$wp_customize->add_setting(
		'ot_arts_council_link',
		array(
			'sanitize_callback' => 'ot_sanitize_text',
		)
	);
	
	$wp_customize->add_control(
		'ot_arts_council_link',
		array(
			'label' => __( 'Address', 'opening_times' ),
			'section' => 'ot_arts_council_link',
			'type' => 'text',
		)
	);
	
	/**-----------------------------------------------------------
	 * Menu Dropdowns
	 *-----------------------------------------------------------*/
	
	// Add the Dropdown Section
	$wp_customize->add_section(	
		'ot_dropdown_select', 
		array(
			'title'     => __( 'Dropdowns', 'opening_times' ),
			'description' => sprintf( __( 'Configure the dropdowns by first selecting the page you wish to appear in the About dropdown and then entering the ID of the menu item for them.', 'opening_times' ) ),
			'priority'  => 120,
		)
	);
	
	// Add the About Menu Setting and Control
	$wp_customize->add_setting(
		'ot_about_menu_ID',
		array(
			'sanitize_callback' => 'ot_sanitize_text',
		)
	);
	
	$wp_customize->add_control(
		'ot_about_menu_ID',
		array(
			'label' => __( 'About Menu ID', 'opening_times' ),
			'section' => 'ot_dropdown_select',
			'type' => 'text',
		)
	);
	
	// Add the Mailing List Setting and Control
	$wp_customize->add_setting(
		'ot_mailing-list_menu_ID',
		array(
			'sanitize_callback' => 'ot_sanitize_text',
		)
	);
	
	$wp_customize->add_control(
		'ot_mailing-list_menu_ID',
		array(
			'label' => __( 'Mailing List Menu ID', 'opening_times' ),
			'section' => 'ot_dropdown_select',
			'type' => 'text',
		)
	);
}
add_action( 'customize_register', 'opening_times_customize_register' );

/**
 * Sanitize the Text Inputs.
 */
function ot_sanitize_text( $input ) {
    return wp_kses_post( force_balance_tags( $input ) );
}

/**
 * Sanitize the Integer Inputs.
 */
function ot_sanitize_integer( $input ) {
    if( is_numeric( $input ) ) {
        return intval( $input );
    }
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function opening_times_customize_preview_js() {
	wp_enqueue_script( 'opening_times_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20130508', true );
}
add_action( 'customize_preview_init', 'opening_times_customize_preview_js' );
