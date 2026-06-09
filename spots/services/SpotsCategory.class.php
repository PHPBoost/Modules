<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 6.0 - 2021 08 22
*/

class SpotsCategory extends Category
{
	public function __construct()
	{
		parent::__construct();
		$this->set_additional_property('color', '');
		$this->set_additional_property('inner_icon', '');
		$this->set_additional_property('category_address', '');
	}

	protected function set_additional_attributes_list()
	{
		$lang = LangLoader::get_all_langs('spots');
		$this->add_additional_attribute('color', ['type' => 'string', 'length' => 250, 'default' => "''", 'attribute_field_parameters' => [
			'field_class'   => 'FormFieldColorPicker',
			'label'         => LangLoader::get_message('common.color', 'common-lang'),
			'default_value' => SpotsConfig::load()->get_default_color()
			]
		]);
		$this->add_additional_attribute('inner_icon', ['type' => 'string', 'length' => 250, 'default' => "''", 'attribute_field_parameters' => [
			'field_class'   => 'FormFieldTextEditor',
			'label'         => $lang['spots.inner.icon'],
			'default_value' => '',
			'options' => [
				'description' => $lang['spots.inner.icon.clue'],
				'placeholder' => $lang['spots.inner.icon.placeholder']
			]
			]
		]);
		$this->add_additional_attribute('category_address', ['type' => 'string', 'length' => 65000, 'default' => "''", 'attribute_field_parameters' => [
			'field_class'   => 'GoogleMapsFormFieldMapAddress',
			'label'         => $lang['spots.category.address'],
			'default_value' => '',
			'options' => [
				'description' => $lang['spots.category.address.clue']
			]
			]
		]);
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

	protected function set_additional_properties(array $properties)
	{
		parent::set_additional_properties($properties);
	}
}
?>
