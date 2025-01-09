<?php
/**
 * @copyright   &copy; 2005-2025 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2015 04 13
 * @since       PHPBoost 4.1 - 2014 09 24
*/

class TeamspeakConfig extends AbstractConfigData
{
	const TS_IP = 'ip';
	const TS_VOICE = 'voice_port';
	const TS_QUERY = 'query_port';
	const TS_USER = 'user';
	const TS_PASS = 'pass';
	const TS_REFRESH_DELAY = 'refresh_delay';
	const CLIENTS_NUMBER_DISPLAYED = 'clients_number_displayed';
	const AUTHORIZATIONS = 'authorizations';

	public function get_ip()
	{
		return $this->get_property(self::TS_IP);
	}

	public function set_ip($ip)
	{
		$this->set_property(self::TS_IP, $ip);
	}

	public function get_query()
	{
		return $this->get_property(self::TS_QUERY);
	}

	public function set_query($port)
	{
		$this->set_property(self::TS_QUERY, $port);
	}

	public function get_voice()
	{
		return $this->get_property(self::TS_VOICE);
	}

	public function set_voice($port)
	{
		$this->set_property(self::TS_VOICE, $port);
	}

	public function get_user()
	{
		return $this->get_property(self::TS_USER);
	}

	public function set_user($user)
	{
		$this->set_property(self::TS_USER, $user);
	}

	public function get_pass()
	{
		return $this->get_property(self::TS_PASS);
	}

	public function set_pass($pass)
	{
		$this->set_property(self::TS_PASS, $pass);
	}

	public function get_refresh_delay()
	{
		return $this->get_property(self::TS_REFRESH_DELAY);
	}

	public function set_refresh_delay($delay)
	{
		$this->set_property(self::TS_REFRESH_DELAY, $delay);
	}

	public function display_clients_number()
	{
		$this->set_property(self::CLIENTS_NUMBER_DISPLAYED, true);
	}

	public function hide_clients_number()
	{
		$this->set_property(self::CLIENTS_NUMBER_DISPLAYED, false);
	}

	public function is_clients_number_displayed()
	{
		return $this->get_property(self::CLIENTS_NUMBER_DISPLAYED);
	}

	public function get_authorizations()
	{
		return $this->get_property(self::AUTHORIZATIONS);
	}

	public function set_authorizations(Array $array)
	{
		$this->set_property(self::AUTHORIZATIONS, $array);
	}

	public function get_default_values()
	{
		return array(
			self::TS_IP => '',
			self::TS_USER => '',
			self::TS_PASS => '',
			self::TS_VOICE => 9987,
			self::TS_QUERY => 10011,
			self::TS_REFRESH_DELAY => 1, // 1 minute
			self::CLIENTS_NUMBER_DISPLAYED => true,
			self::AUTHORIZATIONS => array('r-1' => 1, 'r0' => 1, 'r1' => 1)
		);
	}

	/**
	 * Returns the configuration.
	 * @return TeamspeakConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'Teamspeak', 'config');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('Teamspeak', self::load(), 'config');
	}
}
?>
