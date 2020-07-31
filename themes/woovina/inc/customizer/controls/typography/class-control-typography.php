<?php
/**
 * Customizer Control: woovina-typography.
 *
 * @package     WooVina WordPress theme
 * @subpackage  Controls
 * @since       1.0
 */

// Exit if accessed directly.
if(! defined('ABSPATH')) {
	exit;
}

/**
 * Typography control
 */
class WooVina_Customizer_Typography_Control extends WP_Customize_Control {

	/**
	 * The control type.
	 *
	 * @access public
	 * @var string
	 */
	public $type = 'woovina-typography';

	/**
	 * Enqueue control related scripts/styles.
	 *
	 * @access public
	 */
	public function enqueue() {
		wp_enqueue_script('woovina-select2', WOOVINA_INC_DIR_URI . 'customizer/controls/select2.min.js', array('jquery'), false, true);
		wp_enqueue_style('select2', WOOVINA_INC_DIR_URI . 'customizer/controls/select2.min.css', null);
		wp_enqueue_script('woovina-typography-js', WOOVINA_INC_DIR_URI . 'customizer/assets/min/js/typography.min.js', array('jquery', 'select2'), false, true);
		wp_enqueue_style('woovina-typography', WOOVINA_INC_DIR_URI . 'customizer/assets/min/css/typography.min.css', null);
	}

	/**
	 * Render the control's content.
	 * Allows the content to be overriden without having to rewrite the wrapper in $this->render().
	 *
	 * @access protected
	 */
	protected function render_content() {
		$this_val = $this->value(); ?>
		<label>
			<?php if(! empty($this->label)) : ?>
				<span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
			<?php endif; ?>
			<?php if(! empty($this->description)) : ?>
				<span class="description customize-control-description"><?php echo wp_kses_post($this->description); ?></span>
			<?php endif; ?>

			<select class="woovina-typography-select" <?php $this->link(); ?>>
				<option value="" <?php if(! $this_val) echo 'selected="selected"'; ?>><?php esc_html_e('Default', 'woovina'); ?></option>
				<?php
				// Add custom fonts from child themes
				if(function_exists('woovina_add_custom_fonts')) {
					$fonts = woovina_add_custom_fonts();
					if($fonts && is_array($fonts)) { ?>
						<optgroup label="<?php esc_html_e('Custom Fonts', 'woovina'); ?>">
							<?php foreach ($fonts as $font) { ?>
								<option value="<?php echo esc_html($font); ?>" <?php if($font == $this_val) echo 'selected="selected"'; ?>><?php echo esc_html($font); ?></option>
							<?php } ?>
						</optgroup>
					<?php }
				}

				// Get Standard font options
				if($std_fonts = woovina_standard_fonts()) { ?>
					<optgroup label="<?php esc_html_e('Standard Fonts', 'woovina'); ?>">
						<?php
						// Loop through font options and add to select
						foreach ($std_fonts as $font) { ?>
							<option value="<?php echo esc_html($font); ?>" <?php selected($font, $this_val); ?>><?php echo esc_html($font); ?></option>
						<?php } ?>
					</optgroup>
				<?php }

				// Google font options
				if($google_fonts = woovina_google_fonts_array()) { ?>
					<optgroup label="<?php esc_html_e('Google Fonts', 'woovina'); ?>">
						<?php
						// Loop through font options and add to select
						foreach ($google_fonts as $font) { ?>
							<option value="<?php echo esc_html($font); ?>" <?php selected($font, $this_val); ?>><?php echo esc_html($font); ?></option>
						<?php } ?>
					</optgroup>
				<?php } ?>
			</select>

		</label>

		<?php
	}
}
