<?php
/**
 * The Sidebar containing the widget area in the Mailing List drop-down.
 *
 * @package Opening Times
 */

if ( ! is_active_sidebar( 'sidebar-2' ) ) {
    return;
}
?>

<section id="collapse-mailing" class="collapse container-fluid" aria-hidden="true" role="tabpanel">
    <div class="site-info__container">
    	
    	<?php dynamic_sidebar( 'sidebar-2' ); ?>

    	<button type="button" class="close site-info__close" aria-label="Close">

			<?php echo opening_times_get_svg_icon( array( 'icon' => 'close', 'title' => 'close' ) ); ?>

		</button>
	</div>
</section>
