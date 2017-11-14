<?php
/**
 * The Sidebar containing the widget area in the News drop-down.
 *
 * @package Opening Times
 */
?>

<section id="collapse-news" class="collapse container-fluid" aria-hidden="true" role="tabpanel">
	<div class="site-info__container">

		<?php 
			dynamic_sidebar( 'sidebar-4' );
			get_template_part( 'template-parts/loops/loop-latest-news' );
		?>

		<button type="button" class="close site-info__close" aria-label="Close">

			<?php echo opening_times_get_svg_icon( array( 'icon' => 'close', 'title' => 'close' ) ); ?>

		</button>
    </div>
</section>