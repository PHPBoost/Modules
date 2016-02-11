<?php
/*##################################################
 *                       ServerStatusUrlBuilder.class.php
 *                            -------------------
 *   begin                : August 4, 2013
 *   copyright            : (C) 2013 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
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
