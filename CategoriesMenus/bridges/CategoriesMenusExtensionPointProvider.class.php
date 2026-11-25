<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 25
 * @since       PHPBoost 6.0 - 2021 03 03
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class CategoriesMenusExtensionPointProvider extends ExtensionPointProvider
{
    function __construct()
    {
        parent::__construct('CategoriesMenus');
    }

    public function menus(): mixed
    {
        $menus_list = [];

        foreach (ModulesManager::get_installed_modules_map() as $module)
        {
            if ($module->get_configuration()->has_categories())
            {
                $menus_list[] = new CategoriesMenusModuleMiniMenu($module->get_id(), $module->get_configuration());
            }
        }

        return new ModuleMenus($menus_list);
    }

    public function css_files(): mixed
    {
        $module_css_files = new ModuleCssFiles();
        $module_css_files->adding_always_displayed_file('CategoriesMenus.css');
        return $module_css_files;
    }
}
?>
