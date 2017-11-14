<?php
/**
 * @package Opening Times
 */
?>

<div class="btn-group" role="group" aria-label="Navigation">
	<button type="button" class="btn btn-outline-secondary btn-nav btn-prev" disabled><?php echo opening_times_get_svg_icon( array( 'icon' => 'arrow-back', 'title' => 'previous' ) ); ?></button>
	<button type="button" class="btn btn-outline-secondary btn-nav btn-next"><?php echo opening_times_get_svg_icon( array( 'icon' => 'arrow-forward', 'title' => 'next' ) ); ?></button>
</div>
