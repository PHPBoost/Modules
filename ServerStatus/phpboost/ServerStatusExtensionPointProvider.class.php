<?php
/**
 * @copyright   &copy; 2005-2021 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 01 02
 * @since       PHPBoost 4.0 - 2013 08 04
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class ServerStatusExtensionPointProvider extends ExtensionPointProvider
{
	public function __construct()
	{
		parent::__construct('ServerStatus');
	}

	public function commands()
	{
		return new CLICommandsList(array('check-servers-status' => 'CLIServerStatusCheckServersStatusCommand'));
	}

	public function css_files()
	{
		$module_css_files = new ModuleCssFiles();
		$module_css_files->adding_running_module_displayed_file('ServerStatus.css');
		$module_css_files->adding_always_displayed_file('ServerStatus_mini.css');
		return $module_css_files;
	}

	public function menus()
	{
		return new ModuleMenus(array(new ServerStatusModuleMiniMenu()));
	}

	public function tree_links()
	{
		return new ServerStatusTreeLinks();
	}

	public function url_mappings()
	{
		return new UrlMappings(array(new DispatcherUrlMapping('/ServerStatus/index.php')));
	}
}
?>
