<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 27
 * @since       PHPBoost 6.0 - 2023 03 05
*/

class DiscordConfig extends AbstractConfigData
{
	const DISCORD_ID = 'discord_id';
	const AUTHORIZATIONS  = 'authorizations';

	public function get_discord_id(): string
	{
		return $this->get_property(self::DISCORD_ID);
	}

	public function set_discord_id(string $discord_id): void
	{
		$this->set_property(self::DISCORD_ID, $discord_id);
	}

	public function get_authorizations(): array
	{
		return $this->get_property(self::AUTHORIZATIONS);
	}

	public function set_authorizations(array $array)
	{
		$this->set_property(self::AUTHORIZATIONS, $array);
	}

	public function get_default_values(): array
	{
		return [
			self::DISCORD_ID => '',
			self::AUTHORIZATIONS => ['r-1' => 3, 'r0' => 3, 'r1' => 7]
		];
	}

	/**
	 * Returns the configuration.
	 * @return DiscordConfig
	 */
	public static function load(): DiscordConfig
	{
		return ConfigManager::load(self::class, 'discord', 'config');
	}

	/**
	 * Saving the configuration in database.
	 */
	public static function save(): void
	{
		ConfigManager::save('discord', self::load(), 'config');
	}
}
?>
