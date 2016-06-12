<?php
/*##################################################
 *                       HomeLandingModule.class.php
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

class HomeLandingModule
{
	private $displayed = false;
	private $module_id;
	private $phpboost_module_id;
	private $elements_number_displayed = HomeLandingConfig::ELEMENTS_NUMBER_DISPLAYED;
	private $characters_number_displayed = HomeLandingConfig::CHARACTERS_NUMBER_DISPLAYED;
	
	public function get_name()
	{
		return LangLoader::get_message('module.' . $this->get_module_id(), 'common', 'HomeLanding');
	}
	
	public function display()
	{
		$this->displayed = true;
	}
	
	public function hide()
	{
		$this->displayed = false;
	}
	
	public function is_displayed()
	{
		return $this->phpboost_module_id ? (ModulesManager::is_module_installed($this->phpboost_module_id) && ModulesManager::is_module_activated($this->phpboost_module_id) && $this->displayed) : $this->displayed;
	}
	
	public function set_module_id($module_id)
	{
		$this->module_id = $module_id;
	}
	
	public function get_module_id()
	{
		return $this->module_id;
	}
	
	public function set_phpboost_module_id($phpboost_module_id)
	{
		$this->phpboost_module_id = $phpboost_module_id;
	}
	
	public function get_phpboost_module_id()
	{
		return $this->phpboost_module_id;
	}
	
	public function get_config_module_id()
	{
		return $this->phpboost_module_id ? $this->phpboost_module_id : $this->module_id;
	}
	
	public function set_elements_number_displayed($elements_number_displayed)
	{
		$this->elements_number_displayed = $elements_number_displayed;
	}
	
	public function get_elements_number_displayed()
	{
		return $this->elements_number_displayed;
	}
	
	public function set_characters_number_displayed($characters_number_displayed)
	{
		$this->characters_number_displayed = $characters_number_displayed;
	}
	
	public function get_characters_number_displayed()
	{
		return $this->characters_number_displayed;
	}
	
	public function get_properties()
	{
		return array(
			'displayed' => (int)$this->is_displayed(),
			'module_id' => $this->get_module_id(),
			'phpboost_module_id' => $this->get_phpboost_module_id(),
			'elements_number_displayed' => (int)$this->get_elements_number_displayed(),
			'characters_number_displayed' => (int)$this->get_characters_number_displayed()
		);
	}
	
	public function set_properties(array $properties)
	{
		$this->displayed = (bool)$properties['displayed'];
		$this->module_id = $properties['module_id'];
		$this->phpboost_module_id = $properties['phpboost_module_id'];
		$this->elements_number_displayed = (int)$properties['elements_number_displayed'];
		$this->characters_number_displayed = (int)$properties['characters_number_displayed'];
	}
}
?>
