<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2017 04 24
 * @since       PHPBoost 5.0 - 2017 01 30
*/

class WikiStatusExtensionPointProvider extends ExtensionPointProvider
{
	public function __construct()
	{
		parent::__construct('WikiStatus');
	}

	public function menus()
	{
		return new ModuleMenus(array(new WikiStatusModuleMiniMenu()));
	}

	public function css_files()
	{
		$module_css_files = new ModuleCssFiles();
		$module_css_files->adding_always_displayed_file('wikistatus.css');
		return $module_css_files;
	}
}
?>
