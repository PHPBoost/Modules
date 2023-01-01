<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 03 03
 * @since       PHPBoost 6.0 - 2021 03 03
*/

class CategoriesMenusExtensionPointProvider extends ExtensionPointProvider
{
    function __construct()
    {
        parent::__construct('CategoriesMenus');
    }

	public function menus()
	{
		$menus_list = array();
		
		foreach (ModulesManager::get_installed_modules_map() as $module)
		{
			if ($module->get_configuration()->has_categories())
				$menus_list[] = new CategoriesMenusModuleMiniMenu($module->get_id(), $module->get_configuration());
		}
		
		return new ModuleMenus($menus_list);
	}

	public function css_files()
	{
		$module_css_files = new ModuleCssFiles();
		$module_css_files->adding_always_displayed_file('CategoriesMenus.css');
		return $module_css_files;
	}
}
?>
