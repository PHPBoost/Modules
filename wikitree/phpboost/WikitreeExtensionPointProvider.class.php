<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Xela <xela@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 02 19
 * @since       PHPBoost 5.1 - 2017 09 11
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class WikitreeExtensionPointProvider extends ExtensionPointProvider
{
	public function __construct()
	{
		parent::__construct('wikitree');
	}

	public function css_files()
	{
		$module_css_files = new ModuleCssFiles();
		$module_css_files->adding_always_displayed_file('wikitree_mini.css');
		return $module_css_files;
	}

	public function menus()
	{
		return new ModuleMenus(array(new WikitreeModuleMiniMenu()));
	}
}
?>
