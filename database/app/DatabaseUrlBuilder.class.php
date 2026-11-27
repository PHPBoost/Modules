<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 26
 * @since       PHPBoost 4.1 - 2015 09 30
 * @author      xela <xela@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class DatabaseUrlBuilder
{
	private static $dispatcher = '/database';

	public static function configuration(): Url
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/config');
	}

	public static function database_management(): Url
	{
		return new Url('/database/admin_database.php');
	}

	public static function db_sql_queries(): Url
	{
		return new Url('/database/admin_database.php?query=1');
	}

	public static function home(): Url
	{
		return DispatchManager::get_url(self::$dispatcher, '/');
	}

	public static function documentation(): Url
	{
		return new Url (ModulesManager::get_module('database')->get_configuration()->get_documentation());
	}
}
?>
