<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 12 27
 * @since       PHPBoost 4.1 - 2014 09 24
 * @contributor xela <xela@phpboost.com>
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
		$module_css_files->adding_always_displayed_file('teamspeak.css');
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
