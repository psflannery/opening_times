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

	// display the tax description if it exists, and don't display it on the reading pages.
	if ( !is_post_type_archive( 'reading' ) && !is_singular( 'reading' ) ) {	
        foreach ( $tax_description as $tax ) {
            if (  $tax->description ) {
                echo '<aside class="artist-bio ot-meta ot-bio" role="complementary">' . wpautop( wptexturize( $tax->description ) ) . '</aside>';
            }
        };
	}
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

	    // TODO - fix what happens if no website/url entered
		foreach( $authors as $coauthor ) {
			$url = $coauthor->website ? $coauthor->website : $coauthor->user_url;
			$desc = wpautop( $coauthor->description );

	    	if ( empty( $url ) ) {
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
	$slug = home_url( '/#' . opening_times_the_slug( $echo=false ) );
	
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
 * Display a Splash Banner advertising recent posts
 * 
 * @return [type] [description]
 *
 * @since Opening Times 1.0.0
 */
function opening_times_get_recent_posts() {
    // Grab our customizer settings.
    $time = get_theme_mod( 'opening_times_posts_splash' );

    // Bail early if we haven't set a time or we are not on the home page
    if ( '' == $time || !is_home() ) {
        return;
    }

    $args = array(
        'numberposts' => 2,
        'post_type'   => array( 'post', 'reading' ),
        'date_query'  => array(
            array(
                'after' => $time . ' days ago'
            )
        )
    );

    $recent_posts = wp_get_recent_posts( $args );

    //Bail if we are outside the time params
    if ( empty( $recent_posts ) ) {
        return;
    }

    $before = '<div class="container-fluid w-100 splash">';
    $before .= '<div class="row">';

    echo $before;

    foreach( $recent_posts as $recent ) {
        echo sprintf(
			'<div class="col-md-6 card bg-inverse card-inverse border-0 py-4">
				<div class="row">
					<div class="col-md-6"><a href="%1$s" %3$s data-open="#%6$s">%4$s</a></div>
					<div class="col-md-6"><h2><a href="%1$s" %3$s data-open="#%6$s">%2$s</a></h2>%5$s</div>
				</div>
			</div>',
            $recent["post_type"] === 'post' ? '#' . opening_times_the_slug( $echo=false ) : get_permalink($recent["ID"]),
            $recent["post_title"],
            $recent["post_type"] === 'post' ? 'class="splash-top__link"' : '',
            get_the_post_thumbnail($recent['ID'], 'medium'),
            $recent["post_excerpt"],
            opening_times_the_slug( $echo=false )
        );
    }

    $after = "</div>";
    $after .= "</div>";

    echo $after;

    wp_reset_query();
}
add_action( 'before_header', 'opening_times_get_recent_posts', 10 );
