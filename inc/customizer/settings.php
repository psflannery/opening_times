<?php
/**
 * Customizer settings.
 *
 * @package Opening Times
 */

/**
 * Register additional scripts.
 *
 * @since opening_times 1.0.0
 */
function opening_times_customize_additional_scripts( $wp_customize ) {
	
	// Register a setting.
	$wp_customize->add_setting(
		'opening_times_header_scripts',
		array(
			'default'           => '',
			'sanitize_callback' => 'force_balance_tags',
		)
	);

	// Create the setting field.
	$wp_customize->add_control(
		'opening_times_header_scripts',
		array(
			'label'       => esc_html__( 'Header Scripts', 'opening_times' ),
			'description' => esc_html__( 'Additional scripts to add to the header. Basic HTML tags are allowed.', 'opening_times' ),
			'section'     => 'opening_times_additional_scripts_section',
			'type'        => 'textarea',
		)
	);

	// Register a setting.
	$wp_customize->add_setting(
		'opening_times_footer_scripts',
		array(
			'default'           => '',
			'sanitize_callback' => 'force_balance_tags',
		)
	);

	// Create the setting field.
	$wp_customize->add_control(
		'opening_times_footer_scripts',
		array(
			'label'       => esc_html__( 'Footer Scripts', 'opening_times' ),
			'description' => esc_html__( 'Additional scripts to add to the footer. Basic HTML tags are allowed.', 'opening_times' ),
			'section'     => 'opening_times_additional_scripts_section',
			'type'        => 'textarea',
		)
	);
}
add_action( 'customize_register', 'opening_times_customize_additional_scripts' );

/**
 * Register the placeholder setting.
 *
 * @since opening_times 1.0.0
 */
function opening_times_customize_placeholder_setting( $wp_customize ) {

	// Add the placeholder Setting.
    $wp_customize->add_setting(
        'opening_times_placeholder', 
        array(
            'default'           => get_stylesheet_directory_uri() . '/img/placeholder.png',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => 'opening_times_sanitize_customizer_url',
            'type'              => 'theme_mod',
        )
    );

    // Add the placeholder Control.
    $wp_customize->add_control( 
        new WP_Customize_Image_Control( 
            $wp_customize, 
            'opening_times_placeholder', 
            array(
                'label'    => __( 'Placeholder Image', 'opening_times' ),
                'section'  => 'opening_times_placeholder_section',
            )
        )
    );
}
add_action( 'customize_register', 'opening_times_customize_placeholder_setting' );

/**
 * Register a social icons setting.
 *
 * @since opening_times 1.0.0
 */
function opening_times_customize_social_icons( $wp_customize ) {

    // Create an array of our social links for ease of setup.
    $social_networks = array( 'facebook', 'instagram', 'twitter' );

    // Loop through our networks to setup our fields.
    foreach ( $social_networks as $network ) {
        $wp_customize->add_setting(
            'opening_times_' . $network . '_link',
            array(
                'default' => '',
                'sanitize_callback' => 'opening_times_sanitize_customizer_url',
            )
        );

        $wp_customize->add_control(
            'opening_times_' . $network . '_link',
            array(
                'label'   => sprintf( esc_html__( '%s Link', 'opening_times' ), ucwords( $network ) ),
                'section' => 'opening_times_social_links_section',
                'type'    => 'text',
            )
        );
    }
}
add_action( 'customize_register', 'opening_times_customize_social_icons' );

/**
 * Register footer text setting.
 *
 * @since opening_times 1.0.0
 */
function opening_times_customize_footer_text( $wp_customize ) {
    // Register a setting.
    $wp_customize->add_setting(
        'opening_times_footer_text',
        array(
            'default' => '',
        )
    );

    // Create the setting field.
    $wp_customize->add_control(
        new opening_times_footer_text_Text_Editor_Custom_Control(
            $wp_customize,
            'opening_times_footer_text',
            array(
                'label'       => esc_html__( 'Footer Text', 'opening_times' ),
                'description' => esc_html__( 'The text will be displayed in the footer. Basic HTML tags allowed.', 'opening_times' ),
                'section'     => 'opening_times_footer_section',
                'type'        => 'textarea',
            )
        )
    );
}
add_action( 'customize_register', 'opening_times_customize_footer_text' );

/**
 * Register Arts Council link setting.
 *
 * @since opening_times 1.0.0
 */
function opening_times_ac_link( $wp_customize ) {
	// Add the  Arts Council Link Setting and Control
	$wp_customize->add_setting(
		'opening_times_arts_council_link',
		array(
			'sanitize_callback' => 'opening_times_sanitize_customizer_url',
		)
	);
	
	$wp_customize->add_control(
		'opening_times_arts_council_link',
		array(
			'label'       => __( 'Arts Council Link', 'opening_times' ),
			'description' => esc_html__( 'The Link for the Arts Council Logo in the footer', 'opening_times' ),
			'section'     => 'opening_times_footer_section',
			'type'        => 'text',
		)
	);
}
add_action( 'customize_register', 'opening_times_ac_link' );

/**
 * Register Drop down settings.
 *
 * @since opening_times 1.0.0
 */
function opening_times_menu_dropdowns( $wp_customize ) {
	// Add the About Menu Setting and Control
	$wp_customize->add_setting(
		'opening_times_about_menu_ID',
		array(
			'sanitize_callback' => 'opening_times_sanitize_integer',
		)
	);
	
	$wp_customize->add_control(
		'opening_times_about_menu_ID',
		array(
			'label' => __( 'About Menu ID', 'opening_times' ),
			'section' => 'opening_times_dropdown_select',
			'type' => 'text',
		)
	);

	// Add the Mailing List Setting and Control
	$wp_customize->add_setting(
		'opening_times_news_menu_ID',
		array(
			'sanitize_callback' => 'opening_times_sanitize_integer',
		)
	);
	
	$wp_customize->add_control(
		'opening_times_news_menu_ID',
		array(
			'label' => __( 'News Menu ID', 'opening_times' ),
			'section' => 'opening_times_dropdown_select',
			'type' => 'text',
		)
	);

}
add_action( 'customize_register', 'opening_times_menu_dropdowns' );

/**
 * Register New Posts Splash settings.
 *
 * @since opening_times 1.0.0
 */
function opening_times_new_posts_splash( $wp_customize ){
	// Add the Post Splash Setting and Control
	$wp_customize->add_setting(
		'opening_times_posts_splash',
		array(
			'sanitize_callback' => 'opening_times_sanitize_integer',
		)
	);

	$wp_customize->add_control(
		'opening_times_posts_splash',
		array(
			'label' => __( 'New Posts', 'opening_times' ),
			'description' => esc_html__( 'Set the ammount of time to dsiplay the new posts splash (in days).', 'opening_times' ),
			'section' => 'opening_times_new_posts_splash',
			'type' => 'number',
		)
	);
}
add_action( 'customize_register', 'opening_times_new_posts_splash' );
