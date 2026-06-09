<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 4.0 - 2013 02 25
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class CalendarCategory extends Category
{
	protected function set_additional_attributes_list()
	{
		$this->add_additional_attribute('color', ['type' => 'string', 'length' => 250, 'default' => "''", 'attribute_field_parameters' => [
			'field_class'   => 'FormFieldColorPicker',
			'label'         => LangLoader::get_message('calendar.category.color', 'common', 'calendar'),
			'default_value' => CalendarConfig::load()->get_event_color()
			]
		]);
	}

	public function get_color()
	{
		return $this->get_additional_property('color');
	}
}
?>
