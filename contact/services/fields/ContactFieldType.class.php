<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 4.0 - 2013 07 31
*/

interface ContactFieldType
{
	/**
	 * This function displayed field for form
	 * @param instance of ContactField $field.
	 */
	public function display_field(ContactField $field);

	/**
	 * This function returned value form fields
	 * @param instance of HTMLForm $form and instance of ContactField $field.
	 */
	public function get_value(HTMLForm $form, ContactField $field);

	/**
	 * Return instanciat constraint depending integer type regex.
	 * @return integer
	 */
	public function constraint($value);

	public function set_disable_fields_configuration(array $names);

	/**
	 * @return Array
	 */
	public function get_disable_fields_configuration();

	public function set_name($name);

	/**
	 * @return String
	 */
	public function get_name();
}
?>
