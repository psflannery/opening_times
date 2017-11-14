<?php
/**
 * Opening Times Theme Customizer
 *
 * @package Opening Times
 */

/**
 * Include other customizer files.
 *
 * @since opening_times 1.0.0
 */
function opening_times_include_custom_controls() {
    require get_template_directory() . '/inc/customizer/panels.php';
    require get_template_directory() . '/inc/customizer/sections.php';
    require get_template_directory() . '/inc/customizer/settings.php';
    require get_template_directory() . '/inc/customizer/tinymce.php';
}
add_action( 'customize_register', 'opening_times_include_custom_controls', -999 );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 *
 * @since opening_times 1.0.0
 */
function opening_times_customize_preview_js() {
	wp_enqueue_script( 'opening_times_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '1.0.0', true );
}
add_action( 'customize_preview_init', 'opening_times_customize_preview_js' );

/**
 * Add support for the fancy new edit icons.
 *
 * @link https://make.wordpress.org/core/2016/02/16/selective-refresh-in-the-customizer/
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 *
 * @since opening_times 1.0.0
 */
function opening_times_selective_refresh_support( $wp_customize ) {
    // The <div> classname to append edit icon too.
    $settings = array(
        'blogname'                  => '.site-title a',
        'blogdescription'           => '.site-description',
        'opening_times_footer_text' => '.footer__info-text',
    );

    // Loop through, and add selector partials.
    foreach ( (array) $settings as $setting => $selector ) {
        $args = array( 'selector' => $selector );
        $wp_customize->selective_refresh->add_partial( $setting, $args );
    }
}
add_action( 'customize_register', 'opening_times_selective_refresh_support' );

/**
 * Add live preview support via postMessage.
 *
 * Note: You will need to hook this up via customizer.js
 *
 * @link https://codex.wordpress.org/Theme_Customization_API#Part_3:_Configure_Live_Preview_.28Optional.29
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 *
 * @since opening_times 1.0.0
 */
function opening_times_live_preview_support( $wp_customize ) {
    
    // Settings to apply live preview to.
    $settings = array(
        'blogname',
        'blogdescription',
        'header_textcolor',
        'background_image',
        'opening_times_footer_text',
    );

    // Loop through and add the live preview to each setting.
    foreach ( (array) $settings as $setting_name ) {

        // Try to get the customizer setting.
        $setting = $wp_customize->get_setting( $setting_name );

        // Skip if it is not an object to avoid notices.
        if ( ! is_object( $setting ) ) {
            continue;
        }

        // Set the transport to avoid page refresh.
        $setting->transport = 'postMessage';
    }
}
add_action( 'customize_register', 'opening_times_live_preview_support', 999 );

/**
 * Add a custom messages to Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 *
 * @since opening_times 1.0.0
 */
function opening_times_customize_general_admin( $wp_customize ) {

	// Add custom description to controls or sections.
    $wp_customize->get_control( 'blogdescription' )->description  = __( 'Tagline is hidden in this theme.', 'opening_times' );
}
add_action( 'customize_register', 'opening_times_customize_general_admin' );


// Sanitize the Integer Inputs.
function opening_times_sanitize_integer( $input ) {
    if( is_numeric( $input ) ) {
        return intval( $input );
    }
}

/**
 * Sanitize our customizer text inputs.
 *
 * @param  string $input Text saved in Customizer input fields.
 * @return string        Sanitized output.
 *
 * @author Corey Collins
 */
function opening_times_sanitize_customizer_text( $input ) {
    return sanitize_text_field( force_balance_tags( $input ) );
}

/**
 * Sanitize our customizer URL inputs.
 *
 * - Sanitization: url
 * - Control: text, url
 * 
 * Sanitization callback for 'url' type text inputs. This callback sanitizes `$url` as a valid URL.
 * 
 * NOTE: esc_url_raw() can be passed directly as `$wp_customize->add_setting()` 'sanitize_callback'.
 * It is wrapped in a callback here merely for example purposes.
 * 
 * @see esc_url_raw() https://developer.wordpress.org/reference/functions/esc_url_raw/
 *
 * @param string $url URL to sanitize.
 * @return string Sanitized URL.
 */
function opening_times_sanitize_customizer_url( $url ) {
    return esc_url_raw( $url );
}
