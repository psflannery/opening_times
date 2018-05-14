<?php
/**
 * The template used for displaying the Speed Read theme switcher.
 *
 * @package Opening Times
 */

?>

<div class="dropup theme-toggle" role="navigation">
	<button id="theme-toggle" class="btn dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		<?php esc_html_e( 'Switch View', 'opening_times' ); ?>
	</button>
	<div class="dropdown-menu dropdown-menu-right" aria-labelledby="theme-toggle">
		<button class="btn dropdown-item active" type="button" data-theme="animated" aria-label="animated" disabled="true">
			<?php esc_html_e( 'Slides', 'opening_times' ); ?>
		</button>
		<button class="btn dropdown-item" type="button" data-theme="speed" aria-label="speed">
			<?php esc_html_e( 'Speed Read', 'opening_times' ); ?>
		</button>
		<button class="btn dropdown-item" type="button" data-theme="normal" aria-label="normal">
			<?php esc_html_e( 'Default', 'opening_times' ); ?>
		</button>
	</div>
</div>
