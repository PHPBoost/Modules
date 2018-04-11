<?php
/*##################################################
 *                          HomeLandingUrlBuilder.class.php
 *                            -------------------
 *   begin                : January 2, 2016
 *   copyright            : (C) 2016 Sebastien Lartigue
 *   email                : babso@web33.fr
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

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
