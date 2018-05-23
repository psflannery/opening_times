<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Opening Times
 */

/**
 * Returns true if a blog has more than 1 category.
 *
 * @since opening_times 1.0.0
 */
function opening_times_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'all_the_cool_cats' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,
			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'all_the_cool_cats', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so opening_times_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so opening_times_categorized_blog should return false.
		return false;
	}
}


/**
 * Flush out the transients used in opening_times_categorized_blog.
 *
 * @since opening_times 1.0.0
 */
function opening_times_category_transient_flusher() {
	// Like, beat it. Dig?
	delete_transient( 'all_the_cool_cats' );
}
add_action( 'edit_category', 'opening_times_category_transient_flusher' );
add_action( 'save_post',     'opening_times_category_transient_flusher' );

/**
 * Display a taxonomy term without it being wrapped in a link
 *
 * @param  string  $tax    Required Taxonomy term
 * @param  string  $before Optional Markup to prepend to the taxonomy. Default empty.
 * @param  string  $after  Optional Markup to append to the taxonomy. Default empty. 
 * @param  boolean $echo            Echo or return the taxonomy term. Default true.
 * @return string                   The taxonomy term as text.
 *
 * @since opening_times 1.0.0
 */
function opening_times_tax_no_link( $tax, $before = '', $after = '', $echo=true ) {
	$terms_as_text = get_the_term_list( get_the_ID(), $tax, '', ', ', '' );
    
    if ( '' == $terms_as_text ) {
        return;
    }
    
    if ( '' == $tax ) {
		return esc_html__( 'Please define a taxonomy.', 'opening-times' );
	}
    
    $terms_as_text = $before . strip_tags( $terms_as_text ) . $after;
	
	if ( $echo )
		echo $terms_as_text;
	else
		return $terms_as_text;
}

/**
 * The template tag for displaying the taxonomy description.
 * Used for displayig the Artist and Author bio.
 *
 * @since opening_times 1.0.0
 */
function opening_times_tax_description( $taxonomy ) {
	$tax_description = get_the_terms( get_the_ID(), $taxonomy );
    
    if ( '' == $tax_description ) {
        return;
    }

    foreach ( $tax_description as $tax ) {
        if (  $tax->description ) {
            echo '<aside class="artist-bio ot-meta ot-bio" role="complementary">' . wpautop( wptexturize( $tax->description ) ) . '</aside>';
        }
    };
}

/**
 * The template used for the User Description AKA the Editor Bio
 *
 * @since opening_times 1.0.0
 */
function opening_times_editor_bio( $before = '', $after = '', $echo=true ) {
	$user_description = get_the_author_meta('description');
	$user_url = get_the_author_meta('url');
        
    if ( '' == $user_url ) {
       return;
    }

    $output = '';
    
    if ( function_exists( 'get_coauthors' ) ) {
	    $authors = get_coauthors();

	    $bio_classes = count($authors) > 1 && has_term( 'accordion-xl', 'format' ) ? 'col-md-4 vcard' : 'col-md-8 vcard';

		foreach( $authors as $coauthor ) {
			$url = $coauthor->website;
			$desc = wpautop( $coauthor->description );

	    	if ( empty( $url || $desc ) ) {
	    		return;
	    	}

	    	$output .= sprintf(
	    		'<div class="%1$s">%2$s<p class="author"><a href="%3$s" target="_blank" rel="noopener">%3$s</a></p></div>',
	    		$bio_classes,
	    		$desc, 
	    		$url 
	    	);
		};

	} else {
		$desc = wpautop( $user_description );

		$output .= sprintf(
			'<div class="col-md-8 vcard">%1$s<p class="author"><a href="%2$s" target="_blank" rel="noopener">%2$s</a></p></div>', 
			$desc, 
			$user_url
		);
	}

    $output = $before . $output . $after;

    if ( $echo )
		echo $output;
	else
		return $output;
}

/**
 * Return the header scripts saved in the Customizer.
 * 
 * @return string
 *
 * @since opening_times 1.0.0
 */
function opening_times_get_additional_header_scripts() {

	// Grab our customizer settings.
	$additional_header_scripts = get_theme_mod( 'opening_times_header_scripts' );

	// Stop if there's nothing to display.
	if ( ! $additional_header_scripts ) {
		return false;
	}

	// Return the scripts.
	return $additional_header_scripts;
}

/**
 * Return the footer scripts saved in the Customizer.
 * 
 * @return string
 *
 * @since opening_times 1.0.0
 */
function opening_times_get_additional_footer_scripts() {

	// Grab our customizer settings.
	$additional_footer_scripts = get_theme_mod( 'opening_times_footer_scripts' );

	// Stop if there's nothing to display.
	if ( ! $additional_footer_scripts ) {
		return false;
	}

	// Return the scripts.
	return $additional_footer_scripts;
}

/**
 * Optional footer text.
 * 
 * @param  string  $before Optional Markup to add before the text. Default empty.
 * @param  string  $after  Optional Markup to add after the text. Default empty.
 * @param  boolean $echo   If true, echo the footer text.
 * @return string          If false, return the footer text.
 *
 * @since opening_times 1.0.0
 */
function opening_times_footer_text( $before = '', $after = '', $echo = true  ) {

	// Grab our customizer settings.
	$footer_text = wpautop( get_theme_mod( 'opening_times_footer_text' ) );

	// Stop if there's nothing to display.
	if ( ! $footer_text ) {
		return false;
	}

	$text = $before . $footer_text . $after;

	if ( $echo )
		echo $text;
	else
		return $text;
}

function openining_times_ace_link ( $before = '', $after = '' ) {

	// Grab our customizer settings.
	$ace_link = get_theme_mod( 'opening_times_arts_council_link' );

	// Stop if there's nothing to display.
	if ( ! $ace_link ) {
		return false;
	}

	$logo = opening_times_do_svg( 'ac-black.svg', false );

	$link = sprintf( '<a href="%1$s" target="_blank" rel="noopener">%2$s</a>', $ace_link, $logo );

	$output = $before . $link . $after;

	echo $output;
}

/**
 * Build social sharing icons.
 *
 * @return string
 *
 * @since opening_times 1.0.0
 */
function opening_times_get_social_share() {

	// Get the page fragment
	$slug = 'post' === get_post_type() ? home_url( '/#' . opening_times_the_slug( $echo=false ) ) : get_permalink();
	
	// Build the sharing URLs.
	$twitter_url  = 'https://twitter.com/share?text=' . rawurlencode( html_entity_decode( get_the_title() ) ) . '&amp;url=' . rawurlencode( $slug ) . '&via=otdac';
	$facebook_url = 'https://www.facebook.com/sharer/sharer.php?u=' . rawurlencode( $slug );
	
	// Start the markup.
	ob_start(); ?>
	<div class="social-share">
		<ul class="ot-social-links ot-meta list-unstyled">
			<li>
				<a href="<?php echo esc_url( $slug ); ?>" rel="bookmark">
					<?php esc_html_e( 'Share Link', 'opening_times' ); ?>
				</a>
			</li>
			<li class="popout-link">
				<a href="<?php echo esc_url( $facebook_url ); ?>">
					<?php esc_html_e( 'Share on Facebook', 'opening_times' ); ?>
				</a>
			</li>
			<li class="popout-link">
				<a href="<?php echo esc_url( $twitter_url ); ?>">
					<?php esc_html_e( 'Share on Twitter', 'opening_times' ); ?>
				</a>
			</li>
		</ul>
	</div>

	<?php
	return ob_get_clean();
}


/**
 * Retrieve the social links saved in the customizer and create our social menu.
 *
 * @return mixed HTML output of social links
 *
 * @since opening_times 1.0.0
 */
function opening_times_get_social_menu( $before = '', $after = '' ) {
	
	// Create an array of our social links for ease of setup.
	// Change the order of the networks in this array to change the output order.
	$social_networks = array( 'facebook', 'instagram', 'twitter' );
	
	// Kickoff our output buffer.
	ob_start(); ?>

	<ul class="navigation-social nav">

		<?php	
		$output = '';

		// Loop through our network array.
		foreach ( $social_networks as $network ) :
			
			// Look for the social network's URL.
			$network_url = get_theme_mod( 'opening_times_' . $network . '_link' ); 

			// Only display the list item if a URL is set.
			if ( isset( $network_url ) && ! empty( $network_url ) ) :
				$output .= '<li class="menu-item nav-item nav-item__' . esc_attr( $network ) . '">
							<a href="' . esc_url( $network_url ) . '" class="nav-link">' . ucwords( esc_html( $network ) ) . '</a>
						</li>';
			endif;
		endforeach; 

		$output = $before . $output . $after;

		return $output; ?>

	</ul>

	<?php
	return ob_get_clean();
}

/**
 * The template used for displaying the Speed Read theme switcher.
 *
 * @param array $args {
 *     Optional. Array of theme switcher arguments.
 * 
 *     @type string $menu_id       The ID that is applied to the button element which toggles the menu.
 *     @type string $btn_text      The text in the toggle button.
 *     @type string $position      The position of the dropdown menu relative to the toggle button.
 *     @type string $alignment     The alignment of the dropdown menu relative to the toggle button.
 *     @type string $dropdown_wrap How the dropdown should be wrapped. Uses printf() format with numbered placeholders.
 *     @type string $menu_wrap     How the dropdown menu should be wrapped. Uses printf() format with numbered placeholders.
 * }
 * 
 * @return string|void       String when $echo is false.
 * 
 * @since opening_times 2.0.6
 */

function ot_get_theme_switcher( $args = '' ) {
	$default_args = array(
		'btn_id'        => 'theme-toggle',
		'btn_text'      => 'Switch View',
		'position'      => 'dropup',
		'alignment'     => 'dropdown-menu-right',
		'dropdown_wrap' => '<div class="%1$s theme-toggle" role="navigation">%2$s</div>',
		'menu_wrap'     => '<div class="dropdown-menu %1$s" aria-labelledby="%2$s">%3$s</div>',
	);
	$args = wp_parse_args( $args, $default_args );

	/**
	 * Filters the arguments used to display the theme switcher menu.
	 *
	 * @since 2.0.6
	 *
	 * @param array $args Array of ot_get_theme_switcher() arguments.
	 */
	$args = apply_filters( 'theme_switcher_args', $args );

	// Set up the button
	$button = '<button id="' . esc_attr( $args['btn_id'] ) . '" class="btn dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' . esc_html__( $args['btn_text'], 'opening_times' ) . '</button>';

	/**
	 * Filters the HTML output of the dropdown button.
	 *
	 * @since 2.0.6
	 *
	 * @param string $button The dropdown button HTML button.
	 */
	$button = apply_filters( 'theme_switcher_button_toggle', $button, $args );

	// Set up the menu  
	$menu_items = array(
		'speed'   => esc_html__( 'Speed Read', 'opening_times' ),
		'default' => esc_html__( 'Default', 'opening_times' ),
	);

	/**
	 * Filters the HTML output of the theme swither list.
	 *
	 * @since 2.0.6
	 *
	 * @param array $menu_items The theme swither list HTML output.
	 */
	$menu_items = apply_filters( 'theme_switcher_list', $menu_items );

	$menu_item = '';
	$i = 0;
	// Loop through the menu items and build the HTML output
	foreach ( $menu_items as $menu_item_type => $menu_item_name ) {
		$maybe_disabled = $i === 0 ? ' disabled="true"' : '';
		$maybe_active = $i === 0 ? ' active' : '';
		
		$menu_item .= '<button class="btn dropdown-item ' . $maybe_active . '" type="button" data-theme="' . $menu_item_type . '" aria-label="' . $menu_item_type . '"' . $maybe_disabled . '>' . $menu_item_name . '</button>';
		
		$i++;
	};    

	$menu = sprintf(
		$args['menu_wrap'],
		esc_attr( $args['alignment'] ),
		esc_attr( $args['btn_id'] ),
		$menu_item
	);

	$menu = $button . $menu;

	$dropdown = sprintf(
		$args['dropdown_wrap'],
		esc_attr( $args['position'] ),
		$menu
	);

	echo $dropdown;
}
