<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 2.0 - 2008 07 07
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Arnaud GENET <elenwii@phpboost.com>
*/

class DatabaseExtensionPointProvider extends ExtensionPointProvider
{
	function __construct()
	{
		parent::__construct('database');
	}

	public function commands()
	{
		return new CLICommandsList(['dump' => 'CLIDumpCommand', 'restoredb' => 'CLIRestoreDBCommand']);
	}

	public function css_files()
	{
		$module_css_files = new ModuleCssFiles();
		$module_css_files->adding_running_module_displayed_file('database.css');
		return $module_css_files;
	}

	public function tree_links()
	{
		return new DatabaseTreeLinks();
	}

	public function url_mappings()
	{
		return new UrlMappings([new DispatcherUrlMapping('/database/index.php')]);
	}
}
?>
