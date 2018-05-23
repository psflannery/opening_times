<?php
/**
 * Video Embed related functions and filters
 *
 * Much of the below leans heavily upon the Jetpack responsive videos module.
 * See: https://github.com/Automattic/jetpack/blob/master/modules/theme-tools/responsive-videos.php
 *
 * @package Opening Times
 */


/**
 * Checks if the oembed is a YouTube video.
 * @return string Pattern match for YouTube embed
 *
 * @since Opening Times 1.0.0
 */
function opening_times_is_youtube_check() {
	return '#^https?://(?:www\.)?(?:youtube\.com/watch|youtu\.be/)#';
}


/**
 * Checks if the oembed is a Vimeo video.
 * 
 * @return string Pattern match for Vimeo embed
 *
 * @since Opening Times 1.0.0
 */
function opening_times_is_vimeo_check() {
	return '#^https?://(.+\.)?vimeo\.com/.*#';
}


/**
 * Checks if the oembed is a Vimeo or YouTube video.
 * 
 * @return bool The video check result. 
 *
 * @since Opening Times 1.0.0
 */
function opening_times_oembed_video_check( $url ) {
    $check = false;

    $yt_pattern = opening_times_is_youtube_check();
	$vimeo_pattern = opening_times_is_vimeo_check();
   
	if ( $yt_pattern || $vimeo_pattern ) {
		$check = true;
	}
    
    return $check;
}


/**
 * Define query args for Vimeo URLs
 * 
 * @return array default api calls
 *
 * @since Opening Times 1.0.1
 */
function opening_times_get_vimeo_defaults() {

	if( ! has_term( 'accordion-xl', 'format' ) ) {	
		$defs = '';
	} else {
		$defs = array(
    		'background' => 1,
			'autoplay'   => 0,
			'loop'       => 1,
			'muted'      => 0
    	);
	}

	return $defs;
}

/**
 * Define query args for YouTube URLs
 * 
 * @return array default api calls
 *
 * @since Opening Times 1.0.1
 */
function opening_times_get_youtube_defaults() {

	if( ! has_term( 'accordion-xl', 'format' ) ) {
		$defs = array(
			'enablejsapi' => 1,
		);
	} else {
		$defs = array (
			'enablejsapi'    => 1,
			'autohide'       => 1,
			'autoplay'       => 0,
			'controls'       => 0,
			'fs'             => 0,
			'loop'           => 1,
			'modestbranding' => 1,
			'playsinline'    => 1,
		);
	}

	return $defs;
}


/**
 * Adds a wrapper div to videos
 *
 * @return string
 *
 * @since Opening Times 1.0.1
 */
function opening_times_responsive_videos_embed_html( $html ) {
	if ( empty( $html ) || ! is_string( $html ) ) {
		return $html;
	}

	// The customizer video widget wraps videos with a class of wp-video
	// mejs as of 4.9 apparently resizes videos too which causes issues
	// skip the video if it is wrapped in wp-video.
	$video_widget_wrapper = 'class="wp-video"';
	
	$mejs_wrapped = strpos( $html, $video_widget_wrapper );
	
	// If this is a video widget wrapped by mejs, return the html.
	if ( false !== $mejs_wrapped ) {
		return $html;
	}

	return '<div class="embed-responsive embed-responsive-16by9">' . $html . '</div>';
}
add_filter( 'wp_video_shortcode', 'opening_times_responsive_videos_embed_html' );
add_filter( 'video_embed_html',   'opening_times_responsive_videos_embed_html' );


/**
 * Check if oEmbed is a `$video_patterns` provider video before wrapping.
 *
 * @return string
 *
 * @since Opening Times 1.0.1
 */
function opening_times_responsive_videos_maybe_wrap_oembed( $html, $url = null ) {
	if ( empty( $html ) || ! is_string( $html ) || ! $url ) {
		return $html;
	}

	$opening_times_video_wrapper = '<div class="embed-responsive embed-responsive-16by9">';

	$already_wrapped = strpos( $html, $opening_times_video_wrapper );

	// If the oEmbed has already been wrapped, return the html.
	if ( false !== $already_wrapped ) {
		return $html;
	}

	// oEmbed Video Providers.
	$video_patterns = apply_filters( 'ot_responsive_videos_oembed_videos', array(
		'https?://((m|www)\.)?youtube\.com/watch',
		'https?://((m|www)\.)?youtube\.com/playlist',
		'https?://youtu\.be/',
		'https?://(.+\.)?vimeo\.com/',
		'https?://(www\.)?dailymotion\.com/',
		'https?://dai.ly/',
		'https?://(www\.)?hulu\.com/watch/',
		'https?://wordpress.tv/',
		'https?://(www\.)?funnyordie\.com/videos/',
		'https?://vine.co/v/',
		'https?://(www\.)?collegehumor\.com/video/',
		'https?://(www\.|embed\.)?ted\.com/talks/'
	) );

	// Merge patterns to run in a single preg_match call.
	$video_patterns = '(' . implode( '|', $video_patterns ) . ')';

	$is_video = preg_match( $video_patterns, $url );

	// If the oEmbed is a video, wrap it in the responsive wrapper.
	if ( false === $already_wrapped && 1 === $is_video ) {
		return opening_times_responsive_videos_embed_html( $html );
	}

	return $html;
}
add_filter( 'embed_oembed_html',  'opening_times_responsive_videos_maybe_wrap_oembed', 10, 2 );
add_filter( 'embed_handler_html', 'opening_times_responsive_videos_maybe_wrap_oembed', 10, 2 );


/**
 * Add query args to Vimeo and YouTube URLs
 * 
 * @param  string $html URL of oembed content.
 * @return string       modified embed URL.
 *
 * @since Opening Times 1.0.1
 */
function opening_times_oembed_url( $html, $url ) {
	// Bail if we are in admin or don't have a Vimeo or YouTube url.
	if ( is_admin() || ! opening_times_oembed_video_check( $url ) ) {
		return;
	}

	$youtube_defs = opening_times_get_youtube_defaults();
	$vimeo_defs = opening_times_get_vimeo_defaults();

	$is_vimeo = ( preg_match( opening_times_is_vimeo_check(), $url ) );

	// Decide which api to plug in to.
	$provider = $is_vimeo ? $vimeo_defs : $youtube_defs;

	// Return default if we haven't set any definitions.
	if ( '' === $provider ) {
		return $html;
	}

	// Use preg_match to find iframe src
	preg_match( '/src="(.+?)"/', $html, $matches );
	$src = $matches[1];

	// Define query args
	$oembed_url = add_query_arg( $provider, $src );

	// Set the new omebed src
	$html = preg_replace( '@src=(["])?([^">\s]*)@', 'src=$1' . $oembed_url,  $html );

	return $html;
	
}
//add_filter( 'embed_oembed_html', 'opening_times_oembed_url' );
add_filter( 'oembed_result', 'opening_times_oembed_url', 10, 2 );
