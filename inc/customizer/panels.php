<?php
/**
 * Customizer panels.
 *
 * @package Opening Times
 */

/**
 * Add a custom panels to attach sections too.
 */
function opening_times_customize_panels( $wp_customize ) {

	// Register a new panel.
	$wp_customize->add_panel( 'site-options', array(
		'priority'       => 10,
		'capability'     => 'edit_theme_options',
		'theme_supports' => '',
		'title'          => esc_html__( 'Site Options', 'opening_times' ),
		'description'    => esc_html__( 'Other theme options.', 'opening_times' ),
	) );
}
add_action( 'customize_register', 'opening_times_customize_panels' );
