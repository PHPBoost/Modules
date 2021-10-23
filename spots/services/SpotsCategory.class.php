<?php
/**
 * @copyright   &copy; 2005-2021 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 09 16
 * @since       PHPBoost 6.0 - 2021 08 22
*/

class SpotsCategory extends Category
{
	protected function set_additional_attributes_list()
	{
		$lang = LangLoader::get('common', 'spots');
		$this->add_additional_attribute('color', array('type' => 'string', 'length' => 250, 'default' => "''", 'attribute_field_parameters' => array(
			'field_class'   => 'FormFieldColorPicker',
			'label'         => LangLoader::get_message('common.color', 'common-lang'),
			'default_value' => SpotsConfig::load()->get_default_color()
			)
		));

		$this->add_additional_attribute('inner_icon', array('type' => 'string', 'length' => 250, 'default' => "''", 'attribute_field_parameters' => array(
			'field_class'   => 'FormFieldTextEditor',
			'label'         => $lang['spots.inner.icon'],
			'default_value' => '',
			'options' => array(
				'description' => $lang['spots.inner.icon.clue'],
				'placeholder' => $lang['spots.inner.icon.placeholder']
			)
			)
		));
	}

	public function get_color()
	{
		return $this->get_additional_property('color');
	}

	public function get_inner_icon()
	{
		return $this->get_additional_property('inner_icon');
	}
}
?>
