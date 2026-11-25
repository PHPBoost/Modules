<?php
/**
 * This class is a Factory and return instance class
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 25
 * @since       PHPBoost 4.0 - 2013 07 31
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class ContactFieldsService
{
	public static function display_field(ContactField $field): mixed
	{
		$class = $field->get_instance();
		return $class->display_field($field);
	}

	/**
	 * This function returned value form fields
	 * @param object $form HTMLForm
	 * @param object $field ContactField
	 */
	public static function get_value(HTMLForm $form, ContactField $field): string
	{
		$class = $field->get_instance();
		return $class->get_value($form, $field);
	}
}
?>
