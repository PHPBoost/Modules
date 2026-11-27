<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 26
 * @since       PHPBoost 4.1 - 2015 09 30
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class DatabaseConfig extends AbstractConfigData
{
	const DATABASE_TABLES_OPTIMIZATION_ENABLED = 'database_tables_optimization_enabled';
	const DATABASE_TABLES_OPTIMIZATION_DAY     = 'database_tables_optimization_day';

	public function is_database_tables_optimization_enabled(): bool
	{
		return $this->get_property(self::DATABASE_TABLES_OPTIMIZATION_ENABLED);
	}

	public function set_database_tables_optimization_enabled(bool $enabled): void
	{
		$this->set_property(self::DATABASE_TABLES_OPTIMIZATION_ENABLED, $enabled);
	}

	public function get_database_tables_optimization_day(): string
	{
		return $this->get_property(self::DATABASE_TABLES_OPTIMIZATION_DAY);
	}

	public function set_database_tables_optimization_day(string $day): void
	{
		$this->set_property(self::DATABASE_TABLES_OPTIMIZATION_DAY, $day);
	}

	public function get_default_values(): array
	{
		return [
			self::DATABASE_TABLES_OPTIMIZATION_ENABLED => true,
			self::DATABASE_TABLES_OPTIMIZATION_DAY => 0
		];
	}

	/**
	 * Returns the configuration.
	 * @return DatabaseConfig
	 */
	public static function load(): DatabaseConfig
	{
		return ConfigManager::load(self::class, 'database', 'config');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save(): void
	{
		ConfigManager::save('database', self::load(), 'config');
	}
}
?>
