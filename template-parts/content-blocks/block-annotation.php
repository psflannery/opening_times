<?php
/**
 * Output the Reading Section Annotation template
 *
 * @package Opening Times
 * @since Opening Times 1.0.0
 */

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
		$heading = sprintf(
			'<h2 class="col-12">%1$s</h2>',
			esc_html( $section['slide_title'] )
		);
	}

	// Markup for the text
	if ( isset( $section['slide_text'] ) && ! empty( $section['slide_text'] ) ) {
		global $wp_embed;

		$text = sprintf(
			'<div class="col-md-6 col-lg-5">%1$s</div>',
			apply_filters( 'the_content', $section['slide_text'] )
		);
	}

	// Markup for the note
	if ( isset( $section['slide_text_note'] ) && ! empty( $section['slide_text_note'] ) ) {
		$text_note = sprintf(
			'<div class="pb-4">%1$s</div>',
			esc_html( $section['slide_text_note'] )
		);
	}

	if ( isset( $section['slide_bg_img_id'] ) && ! empty( $section['slide_bg_img_id'] ) ) {
		$img_note = sprintf(
			'<img src="%1$s" srcset="%2$s" alt="%3$s" class="pb-4">',
			wp_get_attachment_url( $section['slide_bg_img_id'], 'medium' ),
			wp_get_attachment_image_srcset( $section['slide_bg_img_id'], 'full' ),
			get_post_meta( $section['slide_bg_img_id'], '_wp_attachment_image_alt', true )
		);
	}

	if ( isset( $section['slide_bg_embed'] ) && ! empty( $section['slide_bg_embed'] ) ) {
		$embed_note = sprintf(
			'<div class="embed-responsive embed-responsive-16by9">%1$s</div>',
			wp_oembed_get( $section['slide_bg_embed'] )
		);
	}

	if ( isset( $section['slide_text_note'] ) || isset( $section['slide_bg_img_id'] ) ) {
		$aside = sprintf(
			'<aside class="col-md-6 col-lg-4 small"><div class="sticky-top top-3">%1$s %2$s %3$s</div></aside>',
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
