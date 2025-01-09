<?php
/**
 * @copyright   &copy; 2005-2025 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 01 02
 * @since       PHPBoost 4.1 - 2014 09 24
 * @contributor xela <xela@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class TeamspeakExtensionPointProvider extends ExtensionPointProvider
{
	public function __construct()
	{
		parent::__construct('teamspeak');
	}

	public function css_files()
	{
		$module_css_files = new ModuleCssFiles();
		$module_css_files->adding_running_module_displayed_file('teamspeak.css');
		$module_css_files->adding_always_displayed_file('teamspeak_mini.css');
		return $module_css_files;
	}

	public function home_page()
	{
		return new DefaultHomePageDisplay($this->get_id(), TeamspeakHomeController::get_view());
	}

	public function menus()
	{
		return new ModuleMenus(array(new TeamspeakModuleMiniMenu()));
	}

	public function tree_links()
	{
		return new TeamspeakTreeLinks();
	}

	public function url_mappings()
	{
		return new UrlMappings(array(new DispatcherUrlMapping('/teamspeak/index.php')));
	}
}
?>
