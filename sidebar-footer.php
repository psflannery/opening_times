<?php
/**
 * The Sidebar containing the widget area in the Footer.
 *
 * @package Opening Times
 */

if ( ! is_active_sidebar( 'sidebar-3' ) ) {
    return;
}
?>

<div class="col-md-3">

	<?php dynamic_sidebar( 'sidebar-3' ); ?>

</div>
