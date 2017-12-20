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
 * @return string Featured media HTML.
 *
 * @since opening_times 1.0.0
 */
function opening_times_featured_content() {
	$oembed = get_post_meta( get_the_ID(), '_ot_embed_url', true );
	$link_url = get_post_meta( get_the_ID(), '_ot_link_url', true );
	$iframe_src = get_post_meta( get_the_ID(), '_ot_iframe_url', true );
	$iframe_height = get_post_meta( get_the_ID(), '_ot_iframe_height', true );

	$featured = '';
    
	if ( has_post_thumbnail() || $oembed || $iframe_src ) {
		if ( $oembed ) {

			$featured .=  sprintf( 
				'<figure>%1$s</figure>', 
				apply_filters( 'the_content', $oembed ) 
			);

		} elseif ( $iframe_src ) {
			
			$featured .=  sprintf( 
				'<div class="featured-image col-sm-3"><iframe src="about:blank" data-src="%1$s" height="%2$s" frameborder="0"></iframe></div>', 
				esc_url( $iframe_src ), 
				esc_attr( $iframe_height ) 
			);

		} elseif ( has_post_thumbnail() ) {
			// Define a thumbnail size
			$thumb_size = 'reading' === get_post_type() ? 'full' : 'accordion-thumb';

			// Define the featured image caption
			$caption = get_post( get_post_thumbnail_id() )->post_excerpt;

			// If we have a caption, mark it up.
			$fig_caption = ! empty( $caption ) ? '<figcaption class="wp-caption-text w-25">' . $caption . '</figcaption>' : '';

			// Only show featured image caption on the reading post type
            $reading_caption = 'reading' === get_post_type() ? $fig_caption : '';

            if ( $link_url ) {

            	$featured .= sprintf( 
            		'<figure class="featured-image"><a href="%1$s" target="_blank" rel="noopener">%2$s</a>%3$s</figure>', 
            		reset( $link_url ), 
            		get_the_post_thumbnail( get_the_ID(), $thumb_size, array( 'alt' => the_title_attribute( 'echo=0' ) ) ), 
            		$reading_caption
            	);
            
            } else {
  
            	$featured .= sprintf( 
            		'<figure class="featured-image">%1$s %2$s</figure>',
            		get_the_post_thumbnail( get_the_ID(), $thumb_size, array( 'alt' => the_title_attribute( 'echo=0' ) ) ), 
            		$reading_caption
            	);
            }
		}
	} else {

		// We got nothing...
		// Don't display a fallback in the reading section
		if ( ! is_post_type_archive( array ( 'reading' ) ) && ! is_singular( array ( 'reading', 'article' ) ) ) {
			$featured .= sprintf( 
				'<figure class="featured-image">%1$s</figure>', 
				opening_times_get_svg_icon( array( 'icon' => 'placeholder' ) ) 
			);

		}
	}
    
    echo $featured;
}

/**
 * Output the Collection Links
 *
 * @since opening_times 1.0.0
 */
function opening_times_featured_links() {
	$file_url = get_post_meta( get_the_ID(), '_ot_file', true );
	$link_url = get_post_meta( get_the_ID(), "_ot_link_url", true );
	$submit_url = get_post_meta( get_the_ID(), '_ot_bv_link_submit_link', true );

	$links = '';

	if ( '' != $link_url ) {
		foreach ( $link_url as $link ) {
			$url_data = parse_external_url($link);

			$links .= sprintf( 
				'<a href="%1$s" class="featured-link word-wrap" %2$s %3$s>%1$s</a>', 
				esc_url( $link ),
				$url_data['target'] != '' ? 'target="' . $url_data['target'] . '"' : '',
				$url_data['rel'] != '' ? 'rel="' . $url_data['rel'] . '"' : ''
			);
		}
	}

	if ( '' != $submit_url ) {
		$links .= sprintf( 
			'<a href="%1$s" class="featured-link" target="_blank" rel="noopener">%1$s</a>', 
			esc_url( $submit_url ) 
		);
	}

	if ( '' != $file_url ) {
		$links .= sprintf( 
			'<a href="%1$s" class="featured-link" target="_blank" rel="noopener">%1$s</a>', 
			esc_url( $file_url ) 
		);
	}

	echo $links;
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
function opening_times_do_reading_accordion( $before = '', $after = '' ) {
	$accordion_panels = get_post_meta( get_the_ID(), '_ot_panel_slide', true );

	if ( '' == $accordion_panels ) {
		return;
	}

	$accordion_classes = opening_times_accordion_type() ? 'accordion--large' : 'accordion mb-5';

	$accordion = '<div id="accordion-' . opening_times_the_slug(false) . '" class="accordion gradient-container ' . $accordion_classes . '" role="tablist" aria-multiselectable="true">';
    
	$i = 0;
	foreach ( (array) $accordion_panels as $key => $accordion_panel ) {
		$title = $sub_title = $text = $media_atts = $text_position = $lazy_load = $bg_color = $bg_img = $bg_audio = $bg_video = $bg_embed = $links = '';
		
		// Set Classes and attributes
		$classes = array();
		$data_attributes = array();
		
		if ( isset( $accordion_panel['media_atts'] ) && ! empty( $accordion_panel['media_atts'] ) ) {

			$atts = array();
			foreach( $accordion_panel['media_atts'] as $key => $media_att ) {
				if ( $media_att === 'auto-play' ) {
					$atts[] = 'data-autoplay';
				} 
		
				if ( $media_att === 'loop' ) {
					$atts[] = 'loop';
				}
		
				if ( $media_att === 'controls' ) {
					$atts[] = 'controls';
				}
				
				if ( $media_att === 'muted' ) {
					$atts[] = 'muted';
				}
		
				if ( $media_att === 'keepplaying' ) {
					$atts[] = 'data-keepplaying';
				}
				
				if ( $media_att === 'playsinline' ) {
					$atts[] = 'playsinline';
				}
			}
		
			$atts = array_map( 'esc_attr', $atts );
			$media_atts = join( ' ', $atts );
		}

		// Markup for the title
		if ( isset( $accordion_panel['slide_title'] ) && ! empty( $accordion_panel['slide_title'] ) ) {
			$text = $accordion_panel['slide_text'];

			$accordion_title_class = opening_times_accordion_type() ? 'slide-content__title' : '';
		
			if ( isset( $accordion_panel['slide_bg_audio_id'] ) && ! empty( $accordion_panel['slide_bg_audio_id'] ) ) {
				$bg_audio = $accordion_panel['slide_bg_audio_id'];
			}
		
			if( '' == $text && '' != $bg_audio ) {
				$title = sprintf(
					'<h2 class="%1$s media-sample mb-0" data-position="bottom" data-media="%2$s">%3$s</h2>',
					$accordion_title_class,
					wp_get_attachment_url( $bg_audio ),
					esc_html( $accordion_panel['slide_title'] )
				);
			} else {
				$title = sprintf(
					'<h2 class="%1$s col mb-0 text-truncate">%2$s</h2>',
					$accordion_title_class,
					esc_html( $accordion_panel['slide_title'] )
				);
			}
		}

		// Markup for the subtitle
		if ( isset( $accordion_panel['slide_sub-title'] ) && ! empty( $accordion_panel['slide_sub-title'] ) ) {
			$sub_title = sprintf(
				'<h2 class="col mb-0 text-right">%1$s</h2>',
				esc_html( $accordion_panel['slide_sub-title'] )
			);
		}

		// Markup for the links
		if ( isset( $accordion_panel['link_url'] ) && ! empty( $accordion_panel['link_url'] ) ) {
			foreach ( $accordion_panel['link_url'] as $link ) {
				$url_data = parse_external_url( $link );

				$links .= sprintf(
					'<a href="%1$s" class="featured-link word-wrap" %2$s %3$s>%1$s</a>',
					esc_url( $url_data['url'] ),
					$url_data['target'] != '' ? 'target="' . $url_data['target'] . '"' : '',
					$url_data['rel'] != '' ? 'rel="' . $url_data['rel'] . '"' : ''
				);
			}
		}

		// Markup for the text
		if ( isset( $accordion_panel['slide_text'] ) && ! empty( $accordion_panel['slide_text'] ) ) {
			global $wp_embed;
		
			$text = sprintf(
				'<div class="%1$s">%2$s</div>',
				$accordion_text_class = opening_times_accordion_type() ? 'card-block col-lg-6' : 'entry-content mb-3',
				apply_filters( 'the_content', $accordion_panel['slide_text'] )
			);
			
			//$data_attributes[] = 'data-caption="#caption-' . $i . '"';
		}

		// Set the text position
		if ( isset( $accordion_panel['text_position'] ) && ! empty( $accordion_panel['text_position'] ) ) {
			switch( $accordion_panel['text_position'] ) {
				case 'sidebar' :
					$position = 'slide_text--sidebar';
					break;
				case 'top-left':
					$position = 'slide__text-overlay slide__text--top-left';
					break;
				case 'top-right':
					$position = 'slide__text-overlay slide__text--top-right';
					break;
				case 'center':
					$position = 'slide__text-overlay slide__text--center';
					break;
				case 'bottom-left':
					$position = 'slide__text-overlay slide__text--bottom-left';
					break;
				case 'bottom-right':
					$position = 'slide__text-overlay slide__text--bottom-right';
					break;
			}

			$classes[] = $position;
		}

		// Set the background color
		if ( isset( $accordion_panel['slide_bg'] ) && ! empty( $accordion_panel['slide_bg'] ) ) {
			$bg_color = sprintf(
				'data-bg-color="%s"',
				esc_attr( $accordion_panel['slide_bg'] )
			);

			$classes[] = 'slide__bg-color';
		}

		// Set the image
		if ( isset( $accordion_panel['slide_bg_img_id'] ) && ! empty( $accordion_panel['slide_bg_img_id'] ) ) {
		
			// Get image size
			$accordion_img_size = opening_times_accordion_type() ? 'full' : 'accordion-thumb'; 

			// Get image attributes
			$img_attributes = wp_get_attachment_image_src( $accordion_panel['slide_bg_img_id'], $accordion_img_size );
			
			// Calculate aspect ratio: h / w * 100%.
			$ratio = $img_attributes[2] / $img_attributes[1] * 100;

			// Calculate image orientation.
			$orientation = $img_attributes[1] < $img_attributes[2] ? 'portrait' : 'landscape';
			
			// Print image ratio
			$accordion_img_ratio = esc_attr( $ratio ) . '%';

			if ( ! opening_times_accordion_type() ) {
				$bg_img = sprintf(
					'<figure class="w-50"><div style="padding-bottom: %1$s;" class="aspect-ratio"><img class="lazyload" src="%2$s" data-src="%3$s" alt="%4$s"></div></figure>',
					$accordion_img_ratio,
					opening_times_placeholder_img( false ),
					wp_get_attachment_image_src( $accordion_panel['slide_bg_img_id'], $accordion_img_size )[0],
					get_post_meta( $accordion_panel['slide_bg_img_id'], '_wp_attachment_image_alt', true )
				);
			} else {
				$bg_img = sprintf(
					'<figure class="mb-0"><div class="%1$s"><img class="slide--fh img-cover lazyload" src="%2$s" data-src="%3$s" alt="%4$s"></div></figure>',
					$orientation,
					opening_times_placeholder_img( false ),
					wp_get_attachment_image_src( $accordion_panel['slide_bg_img_id'], $accordion_img_size )[0],
					get_post_meta( $accordion_panel['slide_bg_img_id'], '_wp_attachment_image_alt', true )
				);
			}
				
			$classes[] = 'slide__bg-img';
		}

		// Set the audio
		if ( isset( $accordion_panel['slide_bg_audio_id'] ) && ! empty( $accordion_panel['slide_bg_audio_id'] ) ) {
			
			if ( $accordion_panel['lazy_load'] != true ) {
				$maybe_lazyLoad = '<audio id="audio-panel-%1$s" src="%2$s" preload="meta" ' . $media_atts . '></audio>';
			} else {
				$maybe_lazyLoad = '<audio id="audio-panel-%1$s" class="lazyload" data-src="%2$s" preload="meta" ' . $media_atts . '></audio>';
			}

			$bg_audio = sprintf(
				$maybe_lazyLoad,
				$i,
				wp_get_attachment_url( $accordion_panel['slide_bg_audio_id'] )
			);

			$data_attributes[] = 'data-media';
			$classes[] = 'slide__audio';
		}
		
		// Set the video
		if ( isset( $accordion_panel['slide_bg_video_id'] ) && ! empty( $accordion_panel['slide_bg_video_id'] ) ) {
			
			if ( $accordion_panel['lazy_load'] != true ) {
				$maybe_lazyLoad = '<div class="w-100 mb-3"><video id="video-panel-%1$s" src="%2$s" preload="meta" ' . $media_atts . '></video></div>';
			} else {
				$maybe_lazyLoad = '<div class="w-100 mb-3 lazyload"><video id="video-panel-%1$s" data-src="%2$s" preload="meta" ' . $media_atts . '></video></div>';
			}
			
			$bg_video = sprintf(
				$maybe_lazyLoad,
				$i,
				wp_get_attachment_url( $accordion_panel['slide_bg_video_id'] )
			);
			
			$data_attributes[] = 'data-media';
			$classes[] = 'slide__video';
		}

		// Set the embeds
		if ( isset( $accordion_panel['slide_bg_embed'] ) && ! empty( $accordion_panel['slide_bg_embed'] ) ) {
			$bg_embed = sprintf(
				'<figure %1$s><div class="embed-responsive embed-responsive-16by9 %2$s">%3$s</div></figure>',
				$accordion_header_class = opening_times_accordion_type() ? 'class="mb-0"' : '',
				strpos($media_atts, 'data-autoplay') !== false ? $autoPlay = 'autoplay' : $autoPlay = '',
				wp_oembed_get( $accordion_panel['slide_bg_embed'] )
			);
			
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
						//if ( $accordion_panel['text_position'] !== 'sidebar' ) {
							echo $text;
						//}
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


/**
 * Output the Sidebar Text for the Large Accordion
 * 
 * @param  string $before Optional Markup to prepend to the large accordion. Default empty.
 * @param  string $after  Optional Markup to append to the large accordion. Default empty.
 * @return string         Large Accordion markup
 *
 * @since Opening Times 1.0.0
 */
function opening_times_do_large_accordion_sidebar( $before = '', $after = '' ) {
	/*
	$accordion_panels = get_post_meta( get_the_ID(), '_ot_panel_slide', true );

	if ( '' == $accordion_panels && ! has_term( array( 'accordion-xl' ), 'format' ) ) {
        return;
    }

    echo $before;
	
	$i = 0;
	foreach ( (array) $accordion_panels as $key => $accordion_panel ) {
		$text = '';

		if ( isset( $accordion_panel['slide_text'] ) && ! empty( $accordion_panel['slide_text'] ) && $accordion_panel['text_position'] === 'sidebar' ) {
			global $wp_embed;

			$text = $accordion_panel['slide_text'];

			$text = sprintf(
				'<div id="caption-' . $i . '" class="card-block slide__text--sidebar col mb-4">%s</div>',
				apply_filters( 'the_content', $accordion_panel['slide_text'] )
			);
		}

		$i ++;

		echo $text;
	};

	echo $after;
	*/
}
