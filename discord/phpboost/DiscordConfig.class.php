<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2023 03 06
 * @since       PHPBoost 6.0 - 2023 03 05
*/

class DiscordConfig extends AbstractConfigData
{
	const DISCORD_ID = 'discord_id';
	const AUTHORIZATIONS  = 'authorizations';

	public function get_discord_id()
	{
		return $this->get_property(self::DISCORD_ID);
	}

	public function set_discord_id($discord_id)
	{
		$this->set_property(self::DISCORD_ID, $discord_id);
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
			self::DISCORD_ID => '',
			self::AUTHORIZATIONS => array('r-1' => 3, 'r0' => 3, 'r1' => 7)
		);
	}

	/**
	 * Returns the configuration.
	 * @return DiscordConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'discord', 'config');
	}

	/**
	 * Saving the configuration in database.
	 */
	public static function save()
	{
		ConfigManager::save('discord', self::load(), 'config');
	}
}
?>
