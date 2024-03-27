<?php
/**
 * @copyright   &copy; 2005-2024 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 01 02
 * @since       PHPBoost 3.0 - 2012 11 15
 * @contributor xela <xela@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class DictionaryExtensionPointProvider extends ExtensionPointProvider
{
	public function __construct() //Constructeur de la classe
	{
		parent::__construct('dictionary');
	}

	 /**
	 * @method Get css files
	 */
	public function css_files()
	{
		$module_css_files = new ModuleCssFiles();
		$module_css_files->adding_running_module_displayed_file('dictionary.css');
		$module_css_files->adding_always_displayed_file('dictionary_mini.css');
		return $module_css_files;
	}

	public function home_page()
	{
		return new DefaultHomePageDisplay($this->get_id(), DictionaryHomeController::get_view());
	}

	public function menus()
	{
		return new ModuleMenus(array(new DictionaryModuleMiniMenu()));
	}

	public function search()
	{
		return new DictionarySearchable();
	}

	public function tree_links()
	{
		return new DictionaryTreeLinks();
	}

	public function url_mappings()
	{
		return new UrlMappings(array(new DispatcherUrlMapping('/dictionary/index.php')));
	}
}
?>
