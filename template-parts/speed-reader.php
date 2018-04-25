<?php
/**
 * The template part for displaying the speed reader.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Opening Times
 */
?>

<div id="spritz" class="spritz">
	<div id="spritz_word" class="spritz-word"></div>
	<div class="settings">
		<div class="controls settings-controls">
			<div class="interaction">
				<a id="spritz_pause" class="pause" href="#" title="Play/Pause">
					<?php 
						opening_times_do_svg_icon( array(
							'icon'  => 'play',
							'title' => 'Play',
						) );
						opening_times_do_svg_icon( array(
							'icon'  => 'pause',
							'title' => 'Pause',
						) );
					?>
				</a>
			</div>
			<div class="speed">
				<input id="spritz_wpm" class="wpm" type="range" value="300" step="50" min="50" max="600" name="wpm"></input>
				<output for="wpm"></output>
			</div>
		</div>
	</div>
	<div id="spritz_progress" class="progress-bar"></div>
</div>
