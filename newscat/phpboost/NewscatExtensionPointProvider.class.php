<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 10 13
 * @since       PHPBoost 5.2 - 2018 11 27
*/

class NewscatExtensionPointProvider extends ExtensionPointProvider
{
    function __construct()
    {
        parent::__construct('newscat');
    }

	public function menus()
	{
		return new ModuleMenus(array(
			new NewscatModuleMiniMenu()
		));
	}

	public function css_files()
	{
		$module_css_files = new ModuleCssFiles();
		$module_css_files->adding_always_displayed_file('newscat.css');
		return $module_css_files;
	}

}
?>
