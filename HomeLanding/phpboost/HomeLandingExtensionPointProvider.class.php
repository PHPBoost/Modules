<?php
/**
 * @copyright   &copy; 2005-2025 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2025 03 24
 * @since       PHPBoost 5.0 - 2016 01 02
*/

class HomeLandingExtensionPointProvider extends ExtensionPointProvider
{
	public function __construct()
	{
		parent::__construct('HomeLanding');
	}

	public function css_files()
	{
		$module_css_files = new ModuleCssFiles();
		$module_css_files->adding_running_module_displayed_file('HomeLanding.css');
		return $module_css_files;
	}

	public function home_page()
	{
		return new HomeLandingHomePageExtensionPoint();
	}

	public function tree_links()
	{
		return new HomeLandingTreeLinks();
	}

	public function url_mappings()
	{
		return new UrlMappings(array(new DispatcherUrlMapping('/HomeLanding/index.php')));
	}
}
?>
