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
 * Output the After Reading List content
 *
 * Postscript/Footnote
 * Submission Form
 *
 * Attatched to `after_reading_list` action hook.
 *
 * @since Opening Times 1.4.4
 */
function opening_times_after_reading_list() {

	$footnote = wpautop( get_post_meta( get_the_ID(), '_ot_after_reading_footnote', true ) );
	$article_submit = get_post_meta( get_the_ID(), '_ot_after_reading_post_submit', true );

	// Bail, if there's nothing to do.
	if ( '' == ( $footnote && $article_submit ) )
		return;

	$output = '';

	$output .= '<div class="issue__epilogue">';

	if ( '' != $footnote ) {
		$output .= sprintf( 
			'<div>%1$s</div>', 
			$footnote 
		);
	}
	if ( '' != $article_submit ) {
		$output .= ot_do_frontend_form_submission_shortcode();
	}

	$output .= '</div>';

	$output = apply_filters( 'ot_after_reading_list', $output );

	echo $output;
}
add_action( 'after_reading_list', 'opening_times_after_reading_list', 1 );


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
 * Output the Reading Section Accordion
 * 
 * @param  string $before Optional Markup to prepend to the large accordion. Default empty.
 * @param  string $after  Optional Markup to append to the large accordion. Default empty.
 * @return string         Reading Section Accordion markup
 *
 * @since Opening Times 1.0.0
 */
function opening_times_accordion_type() {
	$accordionXl = false;
	
	if( has_term( 'accordion-xl', 'format' ) ) {
		$accordionXl = true;
	}

	return $accordionXl;
}


/**
 * Output the reading section accordion
 * 
 * @param  string $before Markup before the accordion element. Default empty. Optional.
 * @param  string $after  Markup after the aaccordion element. Default empty. Optional.
 * @return string         The accordion content and HTML.
 *
 * @since Opening Times 1.0.0
 */
function opening_times_do_reading_accordion( $before = '', $after = '' ) {
	$accordion_panels = get_post_meta( get_the_ID(), '_ot_panel_slide', true );

	if ( '' == $accordion_panels ) {
		return;
	}

	$accordion_classes = opening_times_accordion_type() ? 'accordion--large' : 'accordion mb-5';

	$accordion = '<div id="accordion-' . opening_times_the_slug(false) . '" class="accordion gradient-container ' . $accordion_classes . '" role="tablist" aria-multiselectable="true">';
    
	$i = 0;
	foreach ( (array) $accordion_panels as $key => $accordion_panel ) {
		// Set default values.
		$title = $sub_title = $text = $text_position = $lazy_load = $bg_img = $bg_audio = $bg_video = $bg_embed = $links = '';
		
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
		$accordion_header_class = opening_times_accordion_type() ? 'accordion-header--large' : '';
		$accordion_content_class = opening_times_accordion_type() ? 'px-0' : '';
		$accordion_attributes = join( ' ', $data_attributes );
    	
    	ob_start(); ?>
    	
    	<?php if( '' != ( $text || $bg_video || $bg_video || $bg_img ) ) : ?>

    	<div class="card panel <?php echo $accordion_class; ?>" <?php echo $accordion_attributes; ?>>
	    	<header class="collapsed accordion-header container-fluid gradient-text <?php echo $accordion_header_class; ?>" role="tab" id="header-panel-<?php echo $i ?>" data-toggle="collapse" data-parent="#accordion-<?php opening_times_the_slug(); ?>" data-target="#panel-<?php echo $i ?>" aria-expanded="false" aria-controls="panel-<?php echo $i ?>">
	    		<div class="row">

	    		<?php 
	    			echo $title;
	    			echo $sub_title;
	    		?>

	    		</div>
	    	</header>
	    	<div id="panel-<?php echo $i ?>" class="container-fluid collapse w-100" role="tabpanel" aria-labelledby="header-panel-<?php echo $i ?>" aria-expanded="false">
	    		<div class="row">
					<div class="col-12 <?php echo $accordion_content_class; ?>">

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
				<header id="header-panel-<?php echo $i ?>" class="accordion-header accordion-header--closed container-fluid gradient-text <?php echo $accordion_header_class; ?>" role="tab" id="header-panel-<?php echo $i ?>"">
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
