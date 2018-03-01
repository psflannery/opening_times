<?php
/**
 * Custom CMB2 template blocks for this theme.
 * Configurable markup for all the custom fields.
 *
 * @subpackage: Opening Times
 *
 * @link https://github.com/WebDevStudios/CMB2
 */

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
 * Filters the section oembed iframe.
 * 
 * @param  string $output  The section oembed.
 * @param  array  $section Array of sections elements.
 * @return string          The filtered section oembed.
 *
 * @since opening_times 1.0.1
 */
function opening_times_filter_section_embed( $output, $section ) {
	if( ! empty( $section['slide_bg_embed'] ) && has_term( 'accordion-xl', 'format' ) ) {
		
		$output = opening_times_get_section_oembed(
			$section['slide_bg_embed'],
			array (
				'attr'  => array (
					'data-autoplay' => '',
				)
			),
			true,
			'<figure class="mb-0">',
			'</figure>'
		);
	}

	return $output;
}
add_filter( 'ot_section_oembed', 'opening_times_filter_section_embed', 10, 2 );


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


/**
 * Filters the section text HTML
 * 
 * @param  string $output  The section text HTML.
 * @param  array  $section The section elements.
 * @return string          The filtered section text HTML.
 *
 * @since opening_times 1.0.1
 */
function opening_times_filter_section_text( $output, $section ) {
	if( ! empty( $section['slide_text'] ) && has_term( 'accordion-xl', 'format' ) ) {
		
		$output = opening_times_get_section_text( $section['slide_text'], '<div class="card-block col-lg-6">', '</div>' );

	}

	return $output;
}
add_filter( 'ot_section_text', 'opening_times_filter_section_text', 10, 2 );

/**
 * Filters the section title HTML
 *
 * @param  string $output  The section title HTML.
 * @param  array  $section The section elements.
 * @return string          The filtered section title HTML.
 *
 * @since opening_times 1.0.1
 */
function opening_times_filter_section_title( $output, $section ) {
	// Check for text and audio
	// The interlude sections in SiNB trigger an audio popover
	// Establish if required, and markup accordingly...
	$bg_audio = $text = '';

	if ( isset( $section['slide_text'] ) && ! empty( $section['slide_text'] ) ) {	
		$text = $section['slide_text'];
	}
	
	if ( isset( $section['slide_bg_audio_id'] ) && ! empty( $section['slide_bg_audio_id'] ) ) {
		$bg_audio = $section['slide_bg_audio_id'];
	}

	if( ! empty( $section['slide_title'] ) && has_term( 'accordion-xl', 'format' ) ) {
		if( '' == $text && '' != $bg_audio ) {

			$output = opening_times_get_section_title( esc_html( $section['slide_title'] ), array(
				'class' => 'slide-content__title media-sample mb-0',
				'attr' => array (
					'data-position' => 'bottom',
					'data-media'    => wp_get_attachment_url( $bg_audio ),
				)
			) );

		} else {

			$output = opening_times_get_section_title( esc_html( $section['slide_title'] ), array(
				'class' => 'slide-content__title col mb-0 text-truncate',
			) );
		}
	}

	return $output;
}
add_filter( 'ot_section_title', 'opening_times_filter_section_title', 10, 2 );


/**
 * Filters the section image HTML
 * 
 * @param  string $output  The section image HTML.
 * @param  array  $section The section elements.
 * @return string          The filtered section image HTML.
 *
 * @since opening_times 1.0.1
 */
function opening_times_filter_section_image( $output, $section ) {
	if( ! empty( $section['slide_bg_img_id'] ) && has_term( 'accordion-xl', 'format' ) ) {
	
		$output = opening_times_get_the_attached_image( $section['slide_bg_img_id'], 'full', array(
			'class' => 'mb-0'
		) );

	}

	return $output;
}
add_filter( 'ot_section_image', 'opening_times_filter_section_image', 10, 4 );


/**
 * Filter the attached image html
 * 
 * @param  string $html          The attachment image HTML.
 * @param  string $attachment_id The attachment ID.
 * @param  string $size          The attachment image size.
 * @return string                The filtered attachment image HTML.
 *
 * @since opening_times 1.0.1
 */
function opening_times_filter_attached_image_html( $html, $attachment_id, $size ) {
	if( has_term( 'annotation', 'format' ) ) {
		$html = opening_times_attached_image_html( 
			$attachment_id, 
			$size,
			array(
				'class' => 'lazyload w-100'
			)
		);
	}

	return $html;
}
add_filter( 'ot_attached_image', 'opening_times_filter_attached_image_html', 10, 3 );
