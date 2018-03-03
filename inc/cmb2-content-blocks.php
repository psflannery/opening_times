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
 * Output the reading accordion sections
 *
 * @param  string|array $attr   Query string of attributes.
 * @param  string       $before Markup before the accordion element. Default empty. Optional.
 * @param  string       $after  Markup after the aaccordion element. Default empty. Optional.
 * @return string               The accordion content and HTML.
 *
 * @since Opening Times 1.0.0
 */
function opening_times_do_reading_accordion( $attr = '', $before = '', $after = '' ) {
	$accordion_panels = get_post_meta( get_the_ID(), '_ot_panel_slide', true );

	if ( '' == $accordion_panels ) {
		return;
	}

	$default_atts = array(
		'container_id'    => 'accordion-' . opening_times_the_slug(false),
		'container_class' => 'accordion gradient-container mb-5',
		'header_class'    => 'collapsed accordion-header container-fluid gradient-text',
		'content_class'   => 'container-fluid collapse w-100'
	);

	$atts = wp_parse_args( $attr, $default_atts );

	$accordion = '<div id="' . esc_attr( $atts['container_id'] ) . '" class="' . esc_attr( $atts['container_class'] ) . '" role="tablist" aria-multiselectable="true">';
    
	$i = 0;
	foreach ( (array) $accordion_panels as $key => $accordion_panel ) {
		// Set default values.
		$title = $sub_title = $text = $bg_img = $bg_audio = $bg_video = $bg_embed = $links = '';
		
		// Set Classes and attributes
		$classes = array();
		$data_attributes = array();

		// Markup for the title
		if ( isset( $accordion_panel['slide_title'] ) && ! empty( $accordion_panel['slide_title'] ) ) {
			
			$output = opening_times_get_section_title( esc_html( $accordion_panel['slide_title'] ), array(
				'class' => 'col mb-0 text-truncate'
			) );

			/**
			 * Filter the section title
			 *
			 * @param string $output          The section title HTML.
			 * @param array  $accordion_panel The accordion panel attributes.
			 *
			 * @since opening_times 1.0.1
			 */
			$title = apply_filters( 'ot_section_title', $output, $accordion_panel );
		}

		// Markup for the subtitle
		if ( isset( $accordion_panel['slide_sub-title'] ) && ! empty( $accordion_panel['slide_sub-title'] ) ) {
			
			$output = opening_times_get_section_title( esc_html( $accordion_panel['slide_sub-title'] ), array(
				'class' => 'col mb-0 text-right'
			) );

			/**
			 * Filter the section subtitle
			 *
			 * @param string $output          The section subtitle HTML.
			 * @param array  $accordion_panel The accordion panel.
			 *
			 * @since opening_times 1.0.1
			 */
			$sub_title = apply_filters( 'ot_section_sub-title', $output, $accordion_panel );
		}

		// Markup for the links
		if ( isset( $accordion_panel['link_url'] ) && ! empty( $accordion_panel['link_url'] ) ) {
			foreach ( $accordion_panel['link_url'] as $link ) {
				$links .= opening_times_get_featured_link_html(
					$link,
					array (
						'atts' => array (
							'target' => opening_times_has_external_url( $link ) !== false ? '_blank' : '',
							'rel'    => opening_times_has_external_url( $link ) !== false ? 'noopener' : '',
						) 
					) 
				);
			}
		}

		// Markup for the text
		if ( isset( $accordion_panel['slide_text'] ) && ! empty( $accordion_panel['slide_text'] ) ) {

			$output = opening_times_get_section_text( $accordion_panel['slide_text'], '<div class="entry-content mb-3">', '</div>' );
			
			/**
			 * Filter the section text
			 * 
			 * @param string $output          The section text HTML.
			 * @param array  $accordion_panel The accordion panel.
			 *
			 * @since opening_times 1.0.1
			 */
			$text = apply_filters( 'ot_section_text', $output, $accordion_panel ) ;
		}

		// Set the text position
		if ( isset( $accordion_panel['text_position'] ) && ! empty( $accordion_panel['text_position'] ) ) {
		
			$classes[] = $accordion_panel['text_position'];

		}

		// Set the image
		if ( isset( $accordion_panel['slide_bg_img_id'] ) && ! empty( $accordion_panel['slide_bg_img_id'] ) ) {
		
			$image = opening_times_get_the_attached_image( $accordion_panel['slide_bg_img_id'], 'accordion-thumb' );

			if ( isset( $accordion_panel['link_url'] ) && ! empty( $accordion_panel['link_url'] ) ) {
				$link = reset( $accordion_panel['link_url'] );

				$image_link = opening_times_get_featured_link_html( 
					esc_url( $link ),
					array (
						'anchor' => $image,
						'class'  => '',
						'attr'   => array (
							'target' => opening_times_has_external_url( $link ) !== false ? '_blank' : '',
							'rel'    => opening_times_has_external_url( $link ) !== false ? 'noopener' : '',
						)
					) 
				);

				$output = $image_link;

			} else {

				$output = $image;

			}

			/**
			 * Filter the section image
			 * 
			 * @param string $output          The section image HTML.
			 * @param array  $accordion_panel The accordion panel.
			 *
			 * @since opening_times 1.0.1
			 */
			$bg_img = apply_filters( 'ot_section_image', $output, $accordion_panel ) ;
				
			$classes[] = 'slide__bg-img';
		}

		// Set the audio
		if ( isset( $accordion_panel['slide_bg_audio_id'] ) && ! empty( $accordion_panel['slide_bg_audio_id'] ) ) {
			
			$is_lazy = $accordion_panel['lazy_load'];
			$audio_src = wp_get_attachment_url( $accordion_panel['slide_bg_audio_id'] );
		
			$audio = opening_times_get_section_audio( 
				$audio_src,
				array (
					'id'            => 'audio-panel-' . $i,
					'class'         => $is_lazy === 'on' ? 'lazyload' : '',
					'attr'          => $accordion_panel['media_atts'],
					'src'           => $is_lazy !== 'on' ? $audio_src : '', 
					'data-lazy-src' => $is_lazy === 'on' ? $audio_src : '',
				)
			);

			/**
			 * Filter the section audio
			 * @param array  $audio           The section audio content.
			 * @param array  $accordion_panel The accordion panel.
			 *
			 * @since opening_times 1.0.1
			 */
			$bg_audio = apply_filters( 'ot_section_audio', $audio, $accordion_panel ) ;

			$data_attributes[] = 'data-media';
			$classes[] = 'slide__audio';
		}
		
		// Set the video
		if ( isset( $accordion_panel['slide_bg_video_id'] ) && ! empty( $accordion_panel['slide_bg_video_id'] ) ) {
			
			$is_lazy = $accordion_panel['lazy_load'];
			$video_src = wp_get_attachment_url( $accordion_panel['slide_bg_video_id'] );

			$video = opening_times_get_section_video(
				$video_src,
				array (
					'id'            => 'video-panel-' . $i,
					'class'         => $is_lazy === 'on' ? 'lazyload' : '',
					'attr'          => $accordion_panel['media_atts'],
					'src'           => $is_lazy !== 'on' ? $video_src : '', 
					'data-lazy-src' => $is_lazy === 'on' ? $video_src : '',
				)
			);

			/**
			 * Filter the section video
			 * @param array  $video           The section video content.
			 * @param array  $accordion_panel The accordion panel.
			 *
			 * @since opening_times 1.0.1
			 */
			$bg_video = apply_filters( 'ot_section_video', $video, $accordion_panel ) ;
			
			$data_attributes[] = 'data-media';
			$classes[] = 'slide__video';
		}

		// Set the embeds
		if ( isset( $accordion_panel['slide_bg_embed'] ) && ! empty( $accordion_panel['slide_bg_embed'] ) ) {

			$oembed = opening_times_get_section_oembed(
				$accordion_panel['slide_bg_embed'],
				null,
				false,
				'<figure>',
				'</figure>'
			);

			/**
			 * Filter the section video oembed
			 * @param array  $oembed          The section video oembed content.
			 * @param array  $accordion_panel The accordion panel.
			 *
			 * @since opening_times 1.0.1
			 */
			$bg_embed = apply_filters( 'ot_section_oembed', $oembed, $accordion_panel ) ;
			
			$data_attributes[] = 'data-media';
			$classes[] = 'slide__embed';
		}

		$classes = array_map( 'esc_attr', $classes );
		$accordion_class = join( ' ', $classes );
		
		$accordion_attributes = join( ' ', $data_attributes );
    	
		ob_start(); ?>

		<?php if( '' != ( $text || $bg_video || $bg_video || $bg_img ) ) : ?>

		<div class="card panel <?php echo $accordion_class; ?>" <?php echo $accordion_attributes; ?>>
			<header class="<?php echo esc_attr( $atts['header_class'] ) ?>" role="tab" id="header-panel-<?php echo $i ?>" data-toggle="collapse" data-parent="#<?php echo esc_attr( $atts['container_id'] ) ?>" data-target="#panel-<?php echo $i ?>" aria-expanded="false" aria-controls="panel-<?php echo $i ?>">
				<div class="row">

				<?php 
					echo $title;
					echo $sub_title;
				?>

				</div>
			</header>
			<div id="panel-<?php echo $i ?>" class="<?php echo esc_attr( $atts['content_class'] ) ?>" role="tabpanel" aria-labelledby="header-panel-<?php echo $i ?>" aria-expanded="false">
				<div class="row">
					<div class="col-12">

					<?php
						echo $bg_img;
						echo $bg_video;
						echo $bg_embed;
						echo $links;
						echo $text;
						echo $bg_audio;
					?>

					</div>
				</div>
			</div>
		</div>

		<?php else: ?>

		<div class="card panel <?php echo $accordion_class; ?>" <?php echo $accordion_attributes; ?>>
			<header id="header-panel-<?php echo $i ?>" class="accordion-header accordion-header--closed container-fluid gradient-text" id="header-panel-<?php echo $i ?>">

				<?php echo $title; ?>

			</header>
		</div>

		<?php 
		endif; 
		$accordion .= ob_get_clean();
		$i ++;
		}

		$accordion .= '</div>';

		echo $before . $accordion . $after;
}


/**
 * Output the reading annotation sections
 * 
 * @param  string|array $attr   Query string of attributes.
 * @param  string       $before Optional markup before the annotation. Default empty.
 * @param  string       $after  Optional markup after the annotation. Default empty.
 * @return string               The annotation content and HTML.
 *
 * @since Opening Times 1.0.1
 */
function opening_times_do_reading_annotation( $attr = '', $before = '', $after = '' ) {
	// Get the Anontation post meta
	$sections = get_post_meta( get_the_ID(), '_ot_panel_slide', true );

	// Bail if we don't have any
	if ( '' == $sections ) {
		get_template_part( 'template-parts/content', 'none' );
	}

	// Loop through the sections
	foreach ( $sections as $section ) {
		$heading = $text = $text_note = $img_note = $embed_note = $aside = '';

		// Markup for the title
		if ( isset( $section['slide_title'] ) && ! empty( $section['slide_title'] ) ) {
			$output = '<h2 class="col-12">' . esc_html( $section['slide_title'] ) . '</h2>';

			/**
			 * Filter the annotation title
			 *
			 * @param string $output          The annotation title HTML.
			 * @param array  $accordion_panel The section attributes.
			 *
			 * @since opening_times 1.0.1
			 */
			$heading = apply_filters( 'ot_annotation_title', $output, $section );
		}

		// Markup for the text
		if ( isset( $section['slide_text'] ) && ! empty( $section['slide_text'] ) ) {
			global $wp_embed;

			$text = '<div class="col-md-6">' . apply_filters( 'the_content', $section['slide_text'] ) . '</div>';
		}

		// Markup for the note
		if ( isset( $section['slide_text_note'] ) && ! empty( $section['slide_text_note'] ) ) {
			$output = '<div class="pb-4">' . esc_html( $section['slide_text_note'] ) . '</div>';

			/**
			 * Filter the annotation text note
			 *
			 * @param string $output  The annotation text note HTML.
			 * @param array  $section The section attributes.
			 *
			 * @since opening_times 1.0.1
			 */
			$text_note = apply_filters( 'ot_annotation_text_note', $output, $section );
		}

		if ( isset( $section['slide_bg_img_id'] ) && ! empty( $section['slide_bg_img_id'] ) ) {
			$output = opening_times_get_the_attached_image( 
				$section['slide_bg_img_id'], 
				'accordion-thumb',
				array (
					'class' => '',
				)
			);

			/**
			 * Filter the annotation image note
			 *
			 * @param string $output  The annotation image note HTML.
			 * @param array  $section The section attributes.
			 *
			 * @since opening_times 1.0.1
			 */
			$img_note = apply_filters( 'ot_annotation_img_note', $output, $section );
		}

		if ( isset( $section['slide_bg_embed'] ) && ! empty( $section['slide_bg_embed'] ) ) {
			
			$output = opening_times_get_section_oembed(
				$section['slide_bg_embed'],
				null,
				false,
				'<figure>',
				'</figure>'
			);

			/**
			 * Filter the annotation oembed note
			 *
			 * @param string $output          The annotation oembed note HTML.
			 * @param array  $accordion_panel The section attributes.
			 *
			 * @since opening_times 1.0.1
			 */
			$embed_note = apply_filters( 'ot_annotation_oembed_note', $output, $section );
		}

		if ( isset( $section['slide_text_note'] ) || isset( $section['slide_bg_img_id'] ) ) {
			$aside = sprintf(
				'<aside class="col-md-6 col-lg-5 pb-4"><div class="sticky-top top-3">%1$s %2$s %3$s</div></aside>',
				$text_note,
				$img_note,
				$embed_note
			);
		}

		// Output the content
		echo $heading;
		echo $text;
		echo $aside;
	};
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
