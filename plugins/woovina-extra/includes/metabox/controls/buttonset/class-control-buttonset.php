<?php
/**
 * Buttonset control class.
 *
 * @package     WooVina WordPress theme
 * @subpackage  Controls
 * @see   		https://github.com/justintadlock/butterbean
 * @license     http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

// Exit if accessed directly.
if(! defined('ABSPATH')) {
	exit;
}

/**
 * Buttonset control
 *
 * @since 1.3.0
 * @access public
 */
class WooVina_ButterBean_Control_Buttonset extends ButterBean_Control {

	/**
	 * The type of control.
	 *
	 * @since 1.3.0
	 * @access public
	 * @var    string
	 */
	public $type = 'buttonset';

	/**
	 * Get the value for the setting.
	 *
	 * @since 1.3.0
	 * @access public
	 * @param  string  $setting
	 * @return mixed
	 */
	public function get_value($setting = 'default') {

		$value  = parent::get_value($setting);
		$object = $this->get_setting($setting);

		return ! $value && $object ? $object->default : $value;
	}

}
