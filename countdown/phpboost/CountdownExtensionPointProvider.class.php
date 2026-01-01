<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2017 11 11
 * @since       PHPBoost 4.1 - 2014 12 12
*/

class CountdownExtensionPointProvider extends ExtensionPointProvider
{
    function __construct()
    {
        parent::__construct('countdown');
    }

    public function menus()
    {
        return new ModuleMenus(array(new CountdownModuleMiniMenu()));
    }

    public function css_files()
    {
        $module_css_files = new ModuleCssFiles();
        $module_css_files->adding_always_displayed_file('countdown_mini.css');
        return $module_css_files;
    }

    public function js_files()
    {
        $module_js_files = new ModuleJsFiles();
        $module_js_files->adding_always_displayed_file('countdown.js');
        return $module_js_files;
    }

	public function url_mappings()
	{
		return new UrlMappings(array(new DispatcherUrlMapping('/countdown/index.php')));
	}
}
?>
