<?php
/**
 * Custom CMB2 template tags for this theme.
 *
 * @subpackage: Opening Times
 *
 * @link https://github.com/WebDevStudios/CMB2
 */

/**
 * Outputs the event dates in a pretty format.
 *
 * @since opening_times 1.0.0
 */
function opening_times_event_dates() {
	$meta_start_date = get_post_meta( get_the_ID(), '_ot_residency_start_date', true );
	$meta_end_date = get_post_meta( get_the_ID(), '_ot_residency_end_date', true );
    
    if ( '' == $meta_start_date ) {
        return;
    }

    $eventdate = '';

    // Only a start date
	if( empty( $meta_end_date ) )
		$eventdate = date( 'jS F, Y', $meta_start_date  );

	// Same day
	elseif( date( 'j F', $meta_start_date ) == date( 'j F', $meta_end_date ) )
		$eventdate = date( 'jS F Y', $meta_start_date );

	// Same Month
	elseif( date( 'F', $meta_start_date ) == date( 'F', $meta_end_date ) )
		$eventdate = date( 'jS', $meta_start_date ) . '-' . date( 'jS F, Y', $meta_end_date );

	// Same Year
	elseif( date( 'Y', $meta_start_date ) == date( 'Y', $meta_end_date ) )
		//$eventdate = date( 'jS F', $meta_start_date ) . '-' . date( 'jS F, Y', $meta_end_date );
		$eventdate = date( 'F', $meta_start_date ) . '-' . date( 'F, Y', $meta_end_date );

	// Any other dates
	else
		$eventdate = date( 'jS F, Y', $start ) . '-' . date( 'jS F, Y', $end );
    
    return $eventdate;
}


/**
 * Prints HTML with meta information for the collection pages.
 *
 * @since opening_times 1.0.0
 */
function opening_times_collection_meta() {

	// Set up the function variables
	$file_url = get_post_meta( get_the_ID(), '_ot_file', true );
	$link_url = get_post_meta( get_the_ID(), "_ot_link_url", true );
	$postyear = get_the_time('Y', get_the_ID());
	$user_description = get_the_author_meta( 'description' );
	$event_start_date = get_post_meta( get_the_ID(), '_ot_residency_start_date', true );

	$categories_list = get_the_category_list( esc_html__( ', ', 'opening_times' ) );

	$meta = '<dl class="dl-inline">';

	if ( get_the_terms( get_the_ID(), 'artists') ) {
		$meta .= sprintf(
			'<dt>%1$s</dt><dd>%2$s</dd>',
			esc_html( 'Artist', 'opening_times' ),
			get_the_term_list( get_the_ID(), 'artists', ' ', ', ', '' )
		);
	}

	if ( in_category( array( 'take-over', 'screening' ) ) ) {
		$meta .= sprintf(
			'<dt>%1$s</dt><dd>%2$s</dd>',
			esc_html( 'Dates', 'opening_times' ),
			esc_html( opening_times_event_dates() )
		);
	}

	if ( in_category( 'editorial-introduction' ) ) {
		$meta .= sprintf(
			'<dt>%1$s</dt><dd>%2$s</dd>',
			esc_html( 'Author', 'opening_times' ),
			the_author_posts_link()
		);
	}

	if ( $categories_list && opening_times_categorized_blog() ) {
		$meta .= sprintf(
			'<dt>%1$s</dt><dd>%2$s</dd>',
			esc_html( 'Category', 'opening_times' ),
			$categories_list
		);
	}

	if ( in_category( 'residency' ) && '' != $event_start_date ) {
		$meta .= sprintf(
			'<dt>%1$s</dt><dd>%2$s</dd>',
			esc_html( 'Dates', 'opening_times' ),
			esc_html( opening_times_event_dates() )
		);
	}

	if( get_the_tag_list() ) {
		$meta .= sprintf(
			'<dt>%1$s</dt><dd>%2$s</dd>',
			esc_html( 'Tags', 'opening_times' ),
			get_the_tag_list( ' ', ', ', '' )
		);
	}

	if ( ! is_post_type_archive( array ( 'reading' ) ) ) {
		$meta .= sprintf(
			'<dt>%1$s</dt><dd>%2$s</dd>',
			esc_html( 'Year', 'opening_times' ),
			'<a href="' . get_year_link( $postyear ) . '">' . $postyear . '</a>'
		);
	}

	$meta .= '</dl>';

	echo $meta;
}


/**
 * Output the featured media content
 * 
 * @param  array  $args {
 *     Optional. Default featured media arguments. Default empty array.
 *
 *     @type string $before   Optional markup before the figure element. Default empty.
 *     @type string $after    Optional markup after the figure element. Default empty.
 *     @type $size  $size     Thumbnail size.
 *     @type bool   $fallback Fallback image if true.
 *     @type array  $fig_attr Array of attributes to apply to the figure element. Default empty.
 * }
 * @return string      Markup for featured media items.
 *
 * @since opening_times 1.0.0
 */
function opening_times_featured_content( $args = array() ) {
	$args = wp_parse_args( $args, array(
		'before'   => '',
		'after'    => '',
		'size'     => 'accordion-thumb',
		'fallback' => true,
		'fig_attr' => array(),
	) );

	$oembed = get_post_meta( get_the_ID(), '_ot_embed_url', true );
	$link_url = get_post_meta( get_the_ID(), '_ot_link_url', true );
	$iframe_src = get_post_meta( get_the_ID(), '_ot_iframe_url', true );
	$iframe_height = get_post_meta( get_the_ID(), '_ot_iframe_height', true );

	$featured = '';
    
	if ( $oembed ) {

		$featured .= opening_times_get_section_oembed(
			$oembed,
			null,
			false,
			'<figure>',
			'</figure>'
		);

	} elseif ( $iframe_src ) {
		
		$featured .= sprintf( 
			'<div class="featured-image col-sm-3"><iframe src="about:blank" data-lazy-src="%1$s" height="%2$s" frameborder="0"></iframe></div>', 
			esc_url( $iframe_src ), 
			esc_attr( $iframe_height ) 
		);
	
	} else {

		$post_thumbnail = opening_times_get_the_post_thumbnail( array(
			'before'   => $args['before'],
			'after'    => $args['after'],
			'size'     => $args['size'],
			'fallback' => $args['fallback'],
			'attr'     => $args['fig_attr'],
		) );

		if ( $link_url ) {
			$link = reset( $link_url );

			$post_thumbnail_link = opening_times_get_featured_link_html( 
				esc_url( $link ),
				array (
					'anchor' => $post_thumbnail,
					'class'  => '',
					'attr'   => array (
						'target' => opening_times_has_external_url( $link ) !== false ? '_blank' : '',
						'rel'    => opening_times_has_external_url( $link ) !== false ? 'noopener' : '',
					)
				) 
			);

			$featured .= $post_thumbnail_link;

		} else {

			$featured .= $post_thumbnail;

		}
	}
    
    echo $featured;
}


/**
 * Display the featured links
 * 
 * @param  boolean $echo Echo featured links if true, return them if false. Default true.
 * @return string        Featured links.
 *
 * @since opening_times 1.0.1
 */
function opening_times_do_featured_link( $echo = true ) {
    // Get our featured links
    $link_url = get_post_meta( get_the_ID(), "_ot_link_url", true );
    $file_url = get_post_meta( get_the_ID(), '_ot_file', true );
    
    // Bail if no links.
    if ( '' == $link_url && '' == $file_url ) {
        return;
    }
    
    $featured_link = '';
    
    if ( $link_url ) {
        foreach ( $link_url as $link ) {
            $featured_link .= opening_times_get_featured_link_html( 
                $link,
                array (
                    'atts' => array (
                        'target' => opening_times_has_external_url( $link ) !== false ? '_blank' : '',
                        'rel'    => opening_times_has_external_url( $link ) !== false ? 'noopener' : '',
                    ),
                )
            );
        }
    }
    
    if ( $file_url ) {
        $featured_link .= opening_times_get_featured_link_html( $file_url );
    }
    
    if ( $echo ) {
        echo $featured_link;
    } else {
        return $featured_link; 
    }
}


/**
 * Return a statement of responsibilty.
 *
 * @param  string  $before Optional Markup to prepend to the name. Default empty.
 * @param  string  $after  Optional Markup to append to the name. Default empty. 
 * @return string          The artist, writer or institution responsible for the entry.
 * 
 * @since Opening Times 1.0.0
 */
function opening_times_partner_name( $before = '', $after = '', $echo=true ) {
	$submit_url = get_post_meta( get_the_ID(), '_ot_bv_link_submit_link', true );

	$name = '';

	if ( 'post' === get_post_type() ) {
		$name .= opening_times_tax_no_link( 'artists', '', '', false );
		$name .= opening_times_tax_no_link( 'institutions', '', '', false );
	}

	if ( 'reading' === get_post_type() ) {
		if ( '' != $submit_url ) {
			$name .= opening_times_get_link_submitter();
		} elseif ( wp_get_object_terms( get_the_ID(), 'authors' ) ) {
			$name .= opening_times_tax_no_link( 'authors', '', '', false ) ;
		} else {
			if ( function_exists( 'get_coauthors' ) ) {
				$name .= coauthors( ', ', esc_html( ' & ', 'opening_times' ), '', '', false );
			} else {
				$name .= get_the_author();
			}
		}
	}
    
    $name = $before . $name . $after;

	if ( $echo )
		echo $name;
	else
		return $name;
}

/**
 * Issue Title
 * 
 * @param  string  $before Optional Markup to prepend to the issue title. Default empty.
 * @param  string  $after  Optional Markup to append to the issue title. Default empty.
 * @param  boolean $echo   If true, echo the issue title
 * @return string          If false, return the issue title
 *
 * @since Opening Times 1.0.0
 */
function opening_times_reading_issue_title( $before = '', $after = '', $echo=true ) {
	$issue_title = get_post_meta( get_the_ID(), '_ot_editor_title', true );

	if ( '' == $issue_title ) {
        return;
    }

    $issue_title = $before . $issue_title . $after;

    if ( $echo )
		echo $issue_title;
	else
		return $issue_title;
}

/**
 * Outputs the Standfirst
 * 
 * @param  string  $before Optional Markup to prepend to the standfirst. Default empty.
 * @param  string  $after  Optional Markup to append to the standfirst. Default empty.
 * @param  boolean $echo   If true, echo the standfirst
 * @return string          If false, return the standfirst
 *
 * @since Opening Times 1.0.0
 */
function opening_times_reading_issue_standfirst( $before = '', $after = '', $echo=true ) {
	$text = get_post_meta( get_the_ID(), '_ot_standfirst', true );
	$type = get_post_meta( get_the_ID(), '_ot_standfirst_type', true );
	$cite = get_post_meta( get_the_ID(), '_ot_standfirst_cite', true );

	if ( '' == $text ) {
        return;
    }

    if( '' != $type && $type == 'quote' ) {
		$text = '<blockquote>' . $text . '<footer><cite title="' . $cite . '">' . $cite . '</cite></footer></blockquote>';
	}

    $standfirst = $before . wpautop( $text ) . $after;

    if ( $echo )
    	echo $standfirst;
    else
    	return $standfirst;
}


/**
 * Panel section title
 * 
 * @param  string $title  Panel title. Required.
 * @param  array  $args   Array of attributes to apply to title the element. Default empty. Required.
 * @param  string $before Optional markup before the title element. Default empty. Optional.
 * @param  string $after  Optional markup after the title element. Default empty. Optional.
 * @return string         Panel title markup.
 *
 * @since opening_times 1.0.1
 */
function opening_times_get_section_title( $title = '', $args = array(), $before = '', $after = '' ) {
    // Abort if no title
    if ( '' == $title ) {
        return false;
    }

    $args = wp_parse_args( $args, array(
        'id'     => '',
        'class'  => 'col mb-0',
        'attr'   => array(),
    ) );

    $args['attr'] = array_map( 'esc_attr', $args['attr'] );

    // Set attributes
    $attr = '';

    if ( $args['id'] ) {
        $attr .= ' id="' . esc_attr( $args['id'] ) . '"';
    }

    if ( $args['class'] ) {
        $attr .= ' class="' . esc_attr( $args['class'] ) . '"';
    }

    foreach ( $args['attr'] as $name => $value ) {
        if ( ! empty( $value ) ) {
            $attr .= " $name=" . '"' . $value . '"';
        }
    }

    $section_title = sprintf(
        '<h2 %1$s>%2$s</h2>',
        $attr,
        $title
    );

    $output = $before . $section_title . $after;

    return $output;
}


/**
 * Section audio file and attributes
 * 
 * @param  string       $audio  Audio URL. Required.
 * @param  string|array $attr   Query string of attributes.
 * @param  string       $before Markup before the audio element. Default empty. Optional.
 * @param  string       $after  Markup after the audio element. Default empty. Optional.
 * @return string               Audio element HMTL.
 *
 * @since opening_times 1.0.1
 */
function opening_times_get_section_audio( $audio = '', $attr = '', $before = '', $after = '' ) {
	// Abort if no file path provided.
	if ( '' == $audio ) {
		return false;
	}

	$default_types = wp_get_audio_extensions();
	$default_atts = array(
		'id'            => '',
		'class'         => '',
		'src'           => $audio,
		'data-lazy-src' => '',
		'preload'       => 'meta',
		'autoplay'      => '',
		'attr'          => array(),
	);
	foreach ( $default_types as $type ) {
		$default_atts[$type] = '';
	}

	$atts = wp_parse_args( $attr, $default_atts );
	
	$maybe_lazy_src = empty( $attr['data-lazy-src'] ) ? $attr['src'] : $attr['data-lazy-src'];
	$type = wp_check_filetype( $maybe_lazy_src, wp_get_mime_types() );

	// Bail if the file provided isn't an audio file.
	if ( ! in_array( strtolower( $type['ext'] ), $default_types ) ) {
		return;
	}

	// Define attributes
	$attr = '';

	if ( $atts['id'] ) {
		$attr .= ' id="' . esc_attr( $atts['id'] ) . '"';
	}

	if ( $atts['class'] ) {
		$attr .= ' class="' . esc_attr( $atts['class'] ) . '"';
	}

	if ( $atts['src'] ) {
		$attr .= ' src="' . esc_url( $atts['src'] ) . '"';
	}

	if ( $atts['data-lazy-src'] ) {
		$attr .= ' data-lazy-src="' . esc_url( $atts['data-lazy-src'] ) . '"';
	}

	if ( $atts['preload'] ) {
		$attr .= ' preload="' . esc_attr( $atts['preload'] ) . '"';
	}

	if ( $atts['autoplay'] ) {
		$attr .= ' autoplay="' . esc_attr( $atts['autoplay'] ) . '"';
	}

	foreach ( (array) $atts['attr'] as $name => $value ) {
		if ( ! empty( $value ) ) {
			$attr .= ' ' . esc_attr( $value );
		}
	}

	$html = $before . '<audio' . $attr . '></audio>' . $after;

	return $html;
}


/**
 * Section video file and attributes
 * 
 * @param  string       $video  Video URL. Required.
 * @param  string|array $attr   Query string of attributes.
 * @param  string       $before Markup before the video element. Default empty. Optional.
 * @param  string       $after  Markup after the video element. Default empty. Optional.
 * @return string               Video element HMTL.
 *
 * @since opening_times 1.0.1
 */
function opening_times_get_section_video( $video = '', $attr = '', $before = '', $after = '' ) {
	// Abort if no file path provided.
	if ( '' == $video ) {
		return false;
	}

	$default_types = wp_get_video_extensions();
	$default_atts = array(
		'id'            => '',
		'class'         => '',
		'src'           => $video,
		'data-lazy-src' => '',
		'preload'       => 'meta',
		'poster'        => '',
		'autoplay'      => '',
		'attr'          => array(),
	);
	foreach ( $default_types as $type ) {
		$default_atts[$type] = '';
	}

	$atts = wp_parse_args( $attr, $default_atts );

	$maybe_lazy_src = empty( $attr['data-lazy-src'] ) ? $attr['src'] : $attr['data-lazy-src'];
	$type = wp_check_filetype( $maybe_lazy_src, wp_get_mime_types() );

	// Bail if the file provided isn't an video file.
	if ( ! in_array( strtolower( $type['ext'] ), $default_types ) ) {
		return;
	}

	// Define attributes
	$attr = '';	

	if ( $atts['id'] ) {
		$attr .= ' id="' . esc_attr( $atts['id'] ) . '"';
	}

	if ( $atts['class'] ) {
		$attr .= ' class="' . esc_attr( $atts['class'] ) . '"';
	}

	if ( $atts['src'] ) {
		$attr .= ' src="' . esc_url( $atts['src'] ) . '"';
	}

	if ( $atts['data-lazy-src'] ) {
		$attr .= ' data-lazy-src="' . esc_url( $atts['data-lazy-src'] ) . '"';
	}

	if ( $atts['preload'] ) {
		$attr .= ' preload="' . esc_attr( $atts['preload'] ) . '"';
	}

	if ( $atts['autoplay'] ) {
		$attr .= ' autoplay="' . esc_attr( $atts['autoplay'] ) . '"';
	}

	if ( $atts['poster'] ) {
		$attr .= ' poster="' . esc_url( $atts['poster'] ) . '"';
	}

	foreach ( (array) $atts['attr'] as $name => $value ) {
		if ( ! empty( $value ) ) {
			$attr .= ' ' . esc_attr( $value );
		}
	}

	$html = $before . '<video' . $attr . '></video>' . $after;

	return $html;
}


/**
 * Add attributes to oembed iframe.
 * 
 * @param  string       $oembed The oembed URL
 * @param  string|array $attr   Query string of attributes.
 * @param  boolean      $api    [description]
 * @param  string       $before Markup before the oembed element. Default empty. Optional.
 * @param  string       $after  Markup after the oembed element. Default empty. Optional.
 * @return string               The iframe HTML 
 *
 * @since opening_times 1.0.1
 */
function opening_times_get_section_oembed( $oembed = '', $attr = '', $api = true, $before = '', $after = '' ) {
	// Abort if no oembed path provided.
	if ( '' == $oembed ) {
		return false;
	}

	$default_atts = array(
		'src'   => $oembed,
		'id'    => '',
		'class' => '',
		'attr'  => array(),
	);

	$atts = wp_parse_args( $attr, $default_atts );

	$oembed_html = wp_oembed_get( $atts['src'] );
	$oembed_video = opening_times_responsive_videos_maybe_wrap_oembed( $oembed_html, $atts['src'] );

	// Stop here if we don't need the provider api.
	// Or if the oembed is not from youtube or vimeo.
	if( ! $api || ! opening_times_oembed_video_check( $atts['src'] ) ) {
		return $before . $oembed_video . $after;
	}

	// Set iframe atts
	$attr = '';

	if ( $atts['id'] ) {
		$attr .= ' id="' . esc_attr( $atts['id'] ) . '"';
	}

	if ( $atts['class'] ) {
		$attr .= ' class="' . esc_attr( $atts['class'] ) . '"';
	}

	if ( $atts['attr'] ) {
		foreach ( (array) $atts['attr'] as $name => $value ) {
			if ( ! empty( $value ) ) {
				$attr .= " $name=" . '"' . esc_attr( $value ) . '"';
			} else {
				$attr .= ' ' . esc_attr( $name );
			}
		}
	}

	$oembed_iframe = str_replace( '<iframe', '<iframe' . $attr . '', $oembed_video );

	$output = $before . $oembed_iframe . $after;

	return $output;
}


/**
 * Panel section text
 * 
 * @param  string $text   Panel text. Required.
 * @param  string $before Optional markup before the section text. Default empty. Optional.
 * @param  string $after  Optional markup after the section text. Default empty. Optional.
 * @return string         Panel title markup.
 *
 * @since opening_times 1.0.1
 */
function opening_times_get_section_text( $text = '', $before = '', $after = '' ) {
    // Abort if no text
    if ( '' == $text ) {
        return false;
    }

    global $wp_embed;

    $output = $before . apply_filters( 'the_content', $text ) . $after;

    return $output;
}
