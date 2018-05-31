<?php
/**
 * @package Opening Times
 * 
 * Template part for displaying featured content
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Opening Times
 */

do_action( 'opening-times-before-featured-content' );
?>

<div class="featured-content">
	<div class="featured-content__entry">

		<?php
		if ( 'reading' === get_post_type() ) {
			opening_times_partner_name( '<h2>', '</h2>' );
			opening_times_reading_issue_title( '<span>', '</span>' );
			opening_times_reading_issue_standfirst( '<div class="issue-title__standfirst">', '</div>' );
		}
		?>

	</div>
</div>

<?php do_action( 'opening-times-after-featured-content' ); ?>
