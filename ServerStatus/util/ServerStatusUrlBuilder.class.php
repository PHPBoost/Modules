<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2016 02 11
 * @since       PHPBoost 4.0 - 2013 08 04
*/

class ServerStatusUrlBuilder
{
	private static $dispatcher = '/ServerStatus';

	/**
	 * @return Url
	 */
	public static function configuration()
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/config');
	}

	public static function add_server()
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/servers/add/');
	}

	public static function edit_server($id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/servers/'. $id .'/edit/');
	}

	public static function delete_server()
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/servers/delete/');
	}

	public static function change_display()
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/servers/change_display/');
	}

	public static function servers_management()
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/servers/');
	}

	public static function home($param = '')
	{
		return DispatchManager::get_url(self::$dispatcher, '/' . $param);
	}
}
?>
