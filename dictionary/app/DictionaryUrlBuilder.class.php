<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 27
 * @since       PHPBoost 4.1 - 2016 02 15
*/

class DictionaryUrlBuilder
{
	private static string $dispatcher = '/dictionary';

	public static function configuration(): Url
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/config');
	}

	public static function home(): Url
	{
		return DispatchManager::get_url(self::$dispatcher, '/');
	}
}
?>
