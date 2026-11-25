<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 25
 * @since       PHPBoost 4.0 - 2013 03 01
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class ContactUrlBuilder
{
	private static string $dispatcher = '/contact';

	public static function configuration(): Url
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/config');
	}

	public static function check_field_name(): Url
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/fields/check_name/');
	}

	public static function add_field(): Url
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/fields/add/');
	}

	public static function edit_field(int $id): Url
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/fields/'. $id .'/edit/');
	}

	public static function delete_field(): Url
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/fields/delete/');
	}

	public static function change_display(): Url
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/fields/change_display/');
	}

	public static function manage_fields(): Url
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/fields/');
	}

	public static function home(): Url
	{
		return DispatchManager::get_url(self::$dispatcher, '/');
	}
}
?>
