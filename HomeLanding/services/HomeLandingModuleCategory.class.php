<?php
/*##################################################
 *                       HomeLandingModuleCategory.class.php
 *                            -------------------
 *   begin                : May 1, 2016
 *   copyright            : (C) 2016 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

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
