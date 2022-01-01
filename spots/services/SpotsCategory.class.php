<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 04
 * @since       PHPBoost 6.0 - 2021 08 22
*/

class SpotsCategory extends Category
{
	protected function set_additional_attributes_list()
	{
		$lang = LangLoader::get_all_langs('spots');
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
		// if(ModulesManager::is_module_installed('GoogleMaps') && ModulesManager::is_module_activated('GoogleMaps') && GoogleMapsConfig::load()->get_api_key())
		$this->add_additional_attribute('category_address', array('type' => 'string', 'length' => 65000, 'default' => "''", 'attribute_field_parameters' => array(
			'field_class'   => 'GoogleMapsFormFieldMapAddress',
			'label'         => $lang['spots.category.address'],
			'default_value' => '',
			'options' => array(
				'description' => $lang['spots.category.address.clue']
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

	public function get_category_address()
	{
		return $this->get_additional_property('category_address');
	}
}
?>
