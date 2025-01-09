<?php
/**
 * @copyright   &copy; 2005-2025 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 14
 * @since       PHPBoost 5.0 - 2016 05 01
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class HomeLandingModule
{
	private $displayed = false;
	private $module_id;
	private $phpboost_module_id;
	private $elements_number_displayed = HomeLandingConfig::ELEMENTS_NUMBER_DISPLAYED;
	private $characters_number_displayed = HomeLandingConfig::CHARACTERS_NUMBER_DISPLAYED;

	public function get_name()
	{
		$lang = LangLoader::get_all_langs('HomeLanding');
		if ($this->get_module_id() == $this->get_phpboost_module_id())
			return ModulesManager::get_module($this->get_module_id())->get_configuration()->get_name();
		else
			return $lang['homelanding.module.' . $this->get_module_id()];
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

	public function is_active()
	{
		return ModulesManager::is_module_installed($this->phpboost_module_id) && ModulesManager::is_module_activated($this->phpboost_module_id);
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
