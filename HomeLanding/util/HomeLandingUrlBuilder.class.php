<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2018 12 29
 * @since   	PHPBoost 5.0 - 2016 01 02
*/

class HomeLandingUrlBuilder
{
	private static $dispatcher = '/HomeLanding';

	/**
	 * @return Url
	 */
	public static function configuration($anchor = null)
	{
		$anchor = $anchor !== null ? '#AdminHomeLandingConfigController_admin_' . $anchor : '';
		return DispatchManager::get_url(self::$dispatcher, '/admin/config/' . $anchor);
	}

	/**
	 * @return Url
	 */
	public static function positions()
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/positions/');
	}

	/**
	 * @return Url
	 */
	public static function change_display()
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/positions/change_display/');
	}

    /**
     * @return Url
     */
    public static function sticky_manage()
    {
        return DispatchManager::get_url(self::$dispatcher, '/admin/sticky/');
    }

    /**
     * @return Url
     */
    public static function sticky()
    {
        return DispatchManager::get_url(self::$dispatcher, '/sticky/');
    }

    /**
     * @return Url
     */
    public static function documentation()
    {
        return DispatchManager::get_url(self::$dispatcher, '/documentation/');
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
