<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2016 02 17
 * @since   	PHPBoost 3.0 - 2012 11 15
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
		return $module_css_files;
	}

	public function home_page()
	{
		return new DictionaryHomePageExtensionPoint();
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
