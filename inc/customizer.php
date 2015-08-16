<?php
/**
 * Opening Times Theme Customizer
 *
 * @package Opening Times
 */

/**
 * Custom Controls
 */

// User Select Control
if ( ! class_exists( 'WP_Customize_Control' ) )
return NULL;
class User_Dropdown_Custom_Control extends WP_Customize_Control {
	private $users = false;
	public function __construct($manager, $id, $args = array(), $options = array()) {
		$this->users = get_users( $options );
		parent::__construct( $manager, $id, $args );
	}
	// Render the content of the category dropdown
	public function render_content() {
		if(empty($this->users)) {
		return false;
	}
	?>
	<label>
		<span class="customize-control-title" ><?php echo esc_html( $this->label ); ?></span>
		<select <?php $this->link(); ?>>
		<?php 
			foreach( $this->users as $user ) {
				printf('<option value="%s" %s>%s</option>',
				$user->data->ID,
				selected($this->value(), $user->data->ID, false),
				$user->data->display_name);
			} 
		?>
		</select>
	</label>
	<?php
	}
}

// Category Control
if ( ! class_exists( 'WP_Customize_Control' ) )
return NULL;
class Category_Dropdown_Custom_Control extends WP_Customize_Control {
	private $cats = false;
	public function __construct($manager, $id, $args = array(), $options = array()) {
		$this->cats = get_categories($options);
		parent::__construct( $manager, $id, $args );
	}
	// Render the content of the category dropdown
	public function render_content() {
		if(!empty($this->cats)) {
		?>
			<label>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<select <?php $this->link(); ?>>
				<?php
					foreach ( $this->cats as $cat ) {
						printf('<option value="%s" %s>%s</option>', $cat->term_id, selected($this->value(), $cat->term_id, false), $cat->name);
					}
				?>
				</select>
			</label>
			<?php
		}
	}
}	

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function opening_times_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
	
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

	/*-----------------------------------------------------------*
 	 * User Selected Links - Ben Vickers
 	 *-----------------------------------------------------------*/
	$wp_customize->add_section(
	    'ot_bv_user_selected_links',
	    array(
	        'title'     => 'User Submitted Links',
	        'description' => sprintf( __( 'Configure the category and user to loop through for the submitted links section.', 'opening_times' ) ),
	        'priority'  => 120
	    )
	);

	// Category Select
	$wp_customize->add_setting(
		'ot_bv_user_selected_links_cat',
		array(
			'sanitize_callback' => 'ot_sanitize_integer',
		)
	);

	$wp_customize->add_control(
		new Category_Dropdown_Custom_Control(
			$wp_customize,
			'ot_bv_user_selected_links_cat',
			array(
				'label'    => 'Category',
				'section'  => 'ot_bv_user_selected_links',
			)
		)
	);

	// User Select
	$wp_customize->add_setting(
		'ot_bv_user_selected_links_author',
		array(
			'sanitize_callback' => 'ot_sanitize_integer',
		)
	);

	$wp_customize->add_control(
		new User_Dropdown_Custom_Control(
			$wp_customize,
			'ot_bv_user_selected_links_author',
			array(
				'label'    => 'User',
				'section'  => 'ot_bv_user_selected_links',
			)
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