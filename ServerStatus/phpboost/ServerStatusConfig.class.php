<?php
/**
 * Configuration of the Server Status module
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2025 01 10
 * @since       PHPBoost 4.0 - 2013 08 04
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
		return [
			self::REFRESH_DELAY => 15,
			self::TIMEOUT => 800,
			self::ADDRESS_DISPLAYED => false,
			self::SERVERS_LIST => [],
			self::CHECKED_MAPS_PICTURES => [],
			self::AUTHORIZATIONS => ['r-1' => 1, 'r0' => 1, 'r1' => 1]
        ];
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
