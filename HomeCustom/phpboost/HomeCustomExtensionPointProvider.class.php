<?php
/**
 * @copyright   &copy; 2005-2024 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2016 09 18
 * @since       PHPBoost 3.0 - 2012 08 25
*/

class HomeCustomExtensionPointProvider extends ExtensionPointProvider
{
    public function __construct()
    {
        parent::__construct('HomeCustom');
    }

	public function home_page()
	{
		return new HomeCustomHomePageExtensionPoint();
	}

	public function css_files()
	{
		$module_css_files = new ModuleCssFiles();
		$module_css_files->adding_running_module_displayed_file('HomeCustom.css');
		return $module_css_files;
	}
}
?>
