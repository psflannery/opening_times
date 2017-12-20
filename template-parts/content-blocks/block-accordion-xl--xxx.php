<?php
/**
 * Output the Reading Section Large Accordion
 * 
 * @param  string $before Optional Markup to prepend to the large accordion. Default empty.
 * @param  string $after  Optional Markup to append to the large accordion. Default empty.
 * @return string         Large Accordion markup
 *
 * @since Opening Times 1.0.0
 */

$accordion_panels = get_post_meta( get_the_ID(), '_ot_panel_slide', true );

if ( '' == $accordion_panels ) {
	return;
}
?>

<div id="accordion-xl" class="accordion accordion--large gradient-container" role="tablist" aria-multiselectable="true">'

	<?php
    $i = 0;
    foreach ( (array) $accordion_panels as $key => $accordion_panel ) {
    	$title = $text = $media_atts = $text_position = $lazy_load = $bg_color = $bg_img = $bg_audio = $bg_video = $bg_embed = '';

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
			$bg_embed = $accordion_panel['slide_bg_embed'];

			if ( isset( $accordion_panel['slide_bg_audio_id'] ) && ! empty( $accordion_panel['slide_bg_audio_id'] ) ) {
				$bg_audio = $accordion_panel['slide_bg_audio_id'];
			}

			if( '' == ( $text || $bg_embed ) && '' != $bg_audio ) {
				$title = sprintf(
					'<h2 class="slide-content__title media-sample" data-position="bottom" data-media="%1$s">%2$s</h2>',
					wp_get_attachment_url( $bg_audio ),
					esc_html( $accordion_panel['slide_title'] )
				);
			} else {
				$title = sprintf(
                    '<h2 class="slide-content__title">%s</h2>',
                    esc_html( $accordion_panel['slide_title'] )
                );
			}
    	}

		// Markup for the text
		if ( isset( $accordion_panel['slide_text'] ) && ! empty( $accordion_panel['slide_text'] ) ) {
			global $wp_embed;

			//$text = $accordion_panel['slide_text'];

			$text = sprintf(
				'<div class="card-block col-lg-6">%1$s</div>',
				apply_filters( 'the_content', $accordion_panel['slide_text'] )
				//edit_post_link( __( 'Edit', 'opening_times' ), '<span class="edit-link">', '</span>'); 
			);

			$data_attributes[] = 'data-caption="#caption-' . $i . '"';
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

		// Set the background image
		if ( isset( $accordion_panel['slide_bg_img_id'] ) && ! empty( $accordion_panel['slide_bg_img_id'] ) ) {
		
			$img_attributes = wp_get_attachment_image_src( $accordion_panel['slide_bg_img_id'], 'full' );
			
			// Calculate aspect ratio: h / w * 100%.
			// $ratio = $img_attributes[2] / $img_attributes[1] * 100;

			// Calculate the orientation.
			$orientation = $img_attributes[1] < $img_attributes[2] ? 'portrait' : 'landscape';
			
			$bg_img = sprintf(
				'<div class="w-100 %1$s"><img class="slide--fh img-cover lazyload" src="%2$s" data-src="%3$s " srcset="%4$s" alt="%5$s"></div>',
				$orientation,
				opening_times_placeholder_img( false ),
				wp_get_attachment_url( $accordion_panel['slide_bg_img_id'] ),
				wp_get_attachment_image_srcset( $accordion_panel['slide_bg_img_id'], 'full' ),
				get_post_meta( $accordion_panel['slide_bg_img_id'], '_wp_attachment_image_alt', true )
			);
				
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
				'<div class="embed-responsive embed-responsive-16by9 %1$s">%2$s</div>',
				strpos($media_atts, 'data-autoplay') !== false ? $autoPlay = 'autoplay' : $autoPlay = '',
				wp_oembed_get( $accordion_panel['slide_bg_embed'] )
			);

			$data_attributes[] = 'data-media';
			$classes[] = 'slide__embed';
		}

		$classes = array_map( 'esc_attr', $classes );
        $accordion_class = join( ' ', $classes );

        $accordion_attributes = join( ' ', $data_attributes );

    	?>
    	
    	<?php if( '' != ( $text || $bg_video || $bg_video || $bg_img ) ) : ?>

    	<div class="card panel <?php echo $accordion_class; ?>" <?php echo $accordion_attributes; ?>>
	    	<header class="collapsed accordion-header accordion-header--large container-fluid gradient-text" role="tab" id="header-panel-<?php echo $i ?>" data-toggle="collapse" data-parent="#accordion-xl" data-target="#panel-<?php echo $i ?>" aria-expanded="false" aria-controls="panel-<?php echo $i ?>">

	    		<?php echo $title; ?>

	    	</header>
	    	<div id="panel-<?php echo $i ?>" class="container-fluid collapse w-100" role="tabpanel" aria-labelledby="header-panel-<?php echo $i ?>" aria-expanded="false">
	    		<div class="row">

	    		<?php
					echo $bg_img;
					echo $bg_video;
					echo $bg_embed;
					if ( $accordion_panel['text_position'] !== 'sidebar' ) {
						echo $text;
					}
					echo $bg_audio;
	    		?>

	    		</div>
	    	</div>
    	</div>

    	<?php else: ?>

    		<div class="card panel <?php echo $accordion_class; ?>" <?php echo $accordion_attributes; ?>>
				<header id="header-panel-<?php echo $i ?>" class="accordion-header accordion-header--large accordion-header--closed container-fluid gradient-text" role="tab" id="header-panel-<?php echo $i ?>"">

					<?php echo $title; ?>

				</header>
	    	</div>

    	<?php 
    	endif; 
		$i ++;
    }
    ?>

</div>
