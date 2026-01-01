<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Geoffrey ROGUELON <liaght@gmail.com>
 * @version     PHPBoost 6.0 - last update: 2017 06 15
 * @since       PHPBoost 3.0 - 2009 07 26
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class LastcomsExtensionPointProvider extends ExtensionPointProvider
{
    function __construct()
    {
        parent::__construct('lastcoms');
    }

    public function menus()
    {
	    return new ModuleMenus(array(new LastcomsModuleMiniMenu()));
    }

    public function css_files()
    {
	    $module_css_files = new ModuleCssFiles();
	    $module_css_files->adding_always_displayed_file('lastcoms_mini.css');
	    return $module_css_files;
    }

	public function url_mappings()
	{
		return new UrlMappings(array(new DispatcherUrlMapping('/lastcoms/index.php')));
	}
}
?>
