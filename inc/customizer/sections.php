<?php
/**
 * Customizer sections.
 *
 * @package Opening Times
 */

/**
 * Register the section sections.
 *
 * @since opening_times 1.0.0
 */
function opening_times_customize_sections( $wp_customize ) {

	// Register additional scripts section.
	$wp_customize->add_section(
		'opening_times_additional_scripts_section',
		array(
			'title'       => esc_html__( 'Additional Scripts', 'opening_times' ),
			'description' => '',
			'priority'    => 10,
			'panel'       => 'site-options',
		)
	);

    // Add a default placeholder image.
    $wp_customize->add_section( 
        'opening_times_placeholder_section', 
        array(
            'title'       => __( 'Placeholders', 'opening_times' ),
            'description' => '',
            'priority'    => 50,
            'panel'       => 'site-options',
        )
    );

    // Add a section for our social link options.
    $wp_customize->add_section(
        'opening_times_social_links_section',
        array(
            'title'       => esc_html__( 'Social Links', 'opening_times' ),
            'description' => esc_html__( 'These are the settings for social links.', 'opening_times' ),
            'priority'    => 90,
        )
    );

    // Register a footer section.
    $wp_customize->add_section(
        'opening_times_footer_section',
        array(
            'title'    => esc_html__( 'Footer Customizations', 'opening_times' ),
            'priority' => 90,
            'panel'    => 'site-options',
        )
    );

    // Add the Dropdown Section
    $wp_customize->add_section( 
        'opening_times_dropdown_select', 
        array(
            'title'       => esc_html__( 'Dropdowns', 'opening_times' ),
            'description' => esc_html__( 'Configure the dropdowns by first selecting the page you wish to appear in the About dropdown and then entering the ID of the menu item for them.', 'opening_times' ),
            'priority'    => 120,
            'panel'       => 'site-options',
        )
    );

    // Add the New Posts Splash Info
    $wp_customize->add_section(
        'opening_times_new_posts_splash',
        array(
            'title'       => esc_html__( 'Splash', 'opening_times' ),
            'priority'    => 90,
            'panel'       => 'site-options',
        )
    );
}
add_action( 'customize_register', 'opening_times_customize_sections' );
