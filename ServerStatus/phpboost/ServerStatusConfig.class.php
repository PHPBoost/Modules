<?php
/*##################################################
 *		                   ServerStatusConfig.class.php
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

/**
 * @author Julien BRISWALTER <j1.seth@phpboost.com>
 * @desc Configuration of the Server Status module
 */
class ServerStatusConfig extends AbstractConfigData
{
	const REFRESH_DELAY = 'refresh_delay';
	const TIMEOUT = 'timeout';
	const ADDRESS_DISPLAYED = 'address_displayed';
	const SERVERS_LIST = 'servers_list';
	const CHECKED_MAPS_PICTURES = 'checked_maps_pictures';
	const AUTHORIZATIONS = 'authorizations';
	
	public function get_refresh_delay()
	{
		return $this->get_property(self::REFRESH_DELAY);
	}
	
	public function set_refresh_delay($value)
	{
		$this->set_property(self::REFRESH_DELAY, $value);
	}
	
	public function get_timeout()
	{
		return $this->get_property(self::TIMEOUT);
	}
	
	public function set_timeout($value)
	{
		$this->set_property(self::TIMEOUT, $value);
	}
	
	public function display_address()
	{
		$this->set_property(self::ADDRESS_DISPLAYED, true);
	}
	
	public function hide_address()
	{
		$this->set_property(self::ADDRESS_DISPLAYED, false);
	}
	
	public function is_address_displayed()
	{
		return $this->get_property(self::ADDRESS_DISPLAYED);
	}
	
	public function get_servers_list()
	{
		return $this->get_property(self::SERVERS_LIST);
	}
	
	public function set_servers_list(Array $array)
	{
		$this->set_property(self::SERVERS_LIST, $array);
	}
	
	public function get_checked_maps_pictures()
	{
		return $this->get_property(self::CHECKED_MAPS_PICTURES);
	}
	
	public function set_checked_maps_pictures(Array $array)
	{
		$this->set_property(self::CHECKED_MAPS_PICTURES, $array);
	}
	
	public function get_authorizations()
	{
		return $this->get_property(self::AUTHORIZATIONS);
	}
	
	public function set_authorizations(Array $array)
	{
		$this->set_property(self::AUTHORIZATIONS, $array);
	}
	
	 /**
	 * @method Get default values.
	 */
	public function get_default_values()
	{
		return array(
			self::REFRESH_DELAY => 15,
			self::TIMEOUT => 800,
			self::ADDRESS_DISPLAYED => false,
			self::SERVERS_LIST => array(),
			self::CHECKED_MAPS_PICTURES => array(),
			self::AUTHORIZATIONS => array('r-1' => 1, 'r0' => 1, 'r1' => 1)
		);
	}
	
	/**
	 * @method Load the Server Status configuration.
	 * @return ServerStatusConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'serverstatus', 'config');
	}
	
	/**
	 * Saves the Server Status configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('serverstatus', self::load(), 'config');
	}
}
?>
