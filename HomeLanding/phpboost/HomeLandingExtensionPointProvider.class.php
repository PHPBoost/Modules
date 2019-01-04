<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2018 12 29
 * @since   	PHPBoost 5.0 - 2016 01 02
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
		$module_css_files->adding_running_module_displayed_file('HomeSlider.css');
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
