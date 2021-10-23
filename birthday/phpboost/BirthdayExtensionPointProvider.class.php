<?php
/**
 * @copyright   &copy; 2005-2021 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 01 02
 * @since       PHPBoost 4.0 - 2013 08 27
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class BirthdayExtensionPointProvider extends ExtensionPointProvider
{
	function __construct()
	{
		parent::__construct('birthday');
	}

	public function css_files()
	{
		$module_css_files = new ModuleCssFiles();
		$module_css_files->adding_always_displayed_file('birthday_mini.css');
		return $module_css_files;
	}

	public function menus()
	{
		return new ModuleMenus(array(new BirthdayModuleMiniMenu()));
	}

	public function scheduled_jobs()
	{
		return new BirthdayScheduledJobs();
	}

	public function url_mappings()
	{
		return new UrlMappings(array(new DispatcherUrlMapping('/birthday/index.php')));
	}
}
?>
