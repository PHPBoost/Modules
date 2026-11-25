<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 25
 * @since       PHPBoost 4.0 - 2013 07 31
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

interface ContactFieldType
{
	public function display_field(ContactField $field): void;

	public function get_value(HTMLForm $form, ContactField $field): mixed;

	public function constraint(mixed $value): mixed;

	public function set_disable_fields_configuration(array $names): void;

	public function get_disable_fields_configuration(): array;

	public function set_name(string $name): void;

	public function get_name(): string;
}
?>
