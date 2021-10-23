<?php
/**
 * @copyright   &copy; 2005-2021 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Geoffrey ROGUELON <liaght@gmail.com>
 * @version     PHPBoost 6.0 - last update: 2017 06 15
 * @since       PHPBoost 3.0 - 2009 07 26
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class LastcomsConfig extends AbstractConfigData
{
	const LASTCOMS_NUMBER = 'lastcoms_number';
	const LASTCOMS_CHAR   = 'lastcoms_char';
	const AUTHORIZATIONS  = 'authorizations';

	public function get_lastcoms_number()
	{
		return $this->get_property(self::LASTCOMS_NUMBER);
	}

	public function set_lastcoms_number($lastcoms_number)
	{
		$this->set_property(self::LASTCOMS_NUMBER, $lastcoms_number);
	}

	public function get_lastcoms_char()
	{
		return $this->get_property(self::LASTCOMS_CHAR);
	}

	public function set_lastcoms_char($lastcoms_char)
	{
		$this->set_property(self::LASTCOMS_CHAR, $lastcoms_char);
	}

	/**
	 * @method Get authorizations
	 */
	public function get_authorizations()
	{
		return $this->get_property(self::AUTHORIZATIONS);
	}

	/**
	 * @method Set authorizations
	 * @params string[] $array Array of authorizations
	 */
	public function set_authorizations(Array $array)
	{
		$this->set_property(self::AUTHORIZATIONS, $array);
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_default_values()
	{
		return array(
			self::LASTCOMS_NUMBER => '10',
			self::LASTCOMS_CHAR => '25',
			self::AUTHORIZATIONS => array('r-1' => 3, 'r0' => 3, 'r1' => 7)
		);
	}

	/**
	 * Returns the configuration.
	 * @return LastcomsConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'lastcoms', 'config');
	}

	/**
	 * Saving the configuration in database.
	 */
	public static function save()
	{
		ConfigManager::save('lastcoms', self::load(), 'config');
	}
}
?>
