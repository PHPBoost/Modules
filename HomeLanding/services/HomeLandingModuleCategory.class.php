<?php
/**
 * @copyright   &copy; 2005-2025 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2016 06 12
 * @since       PHPBoost 5.0 - 2016 05 01
*/

class HomeLandingModuleCategory extends HomeLandingModule
{
	private $id_category = Category::ROOT_CATEGORY;
	private $subcategories_content_displayed = false;

	public function set_id_category($id_category)
	{
		$this->id_category = $id_category;
	}

	public function get_id_category()
	{
		return $this->id_category;
	}

	public function display_subcategories_content()
	{
		$this->subcategories_content_displayed = true;
	}

	public function hide_subcategories_content()
	{
		$this->subcategories_content_displayed = false;
	}

	public function is_subcategories_content_displayed()
	{
		return $this->subcategories_content_displayed;
	}

	public function get_properties()
	{
		return array_merge(parent::get_properties(), array(
			'id_category' => $this->get_id_category(),
			'subcategories_content_displayed' => (int)$this->is_subcategories_content_displayed()
		));
	}

	public function set_properties(array $properties)
	{
		parent::set_properties($properties);
		$this->id_category = $properties['id_category'];
		$this->subcategories_content_displayed = (bool)$properties['subcategories_content_displayed'];
	}
}
?>
