<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2015 04 13
 * @since       PHPBoost 4.1 - 2014 09 24
*/

class TeamspeakUrlBuilder
{
	private static $dispatcher = '/teamspeak';

	/**
	 * @return Url
	 */
	public static function configuration()
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/config/');
	}

	/**
	 * @return Url
	 */
	public static function refresh_viewer()
	{
		return DispatchManager::get_url(self::$dispatcher, '/ajax_refresh_viewer/');
	}

	/**
	 * @return Url
	 */
    public static function home()
	{
		return DispatchManager::get_url(self::$dispatcher, '/');
	}
}
?>
