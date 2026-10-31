<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 10 31
 * @since       PHPBoost 5.0 - 2017 03 26
*/

class GoogleMapsConfig extends AbstractConfigData
{
	const API_KEY = 'api_key';
	const DEFAULT_MARKER_ADDRESS = 'default_marker_address';
	const DEFAULT_MARKER_LATITUDE = 'default_marker_latitude';
	const DEFAULT_MARKER_LONGITUDE = 'default_marker_longitude';
	const DEFAULT_ZOOM = 'default_zoom';

	public function get_api_key(): ?string
	{
		return $this->get_property(self::API_KEY);
	}

	public function set_api_key(?string $api_key): void
	{
		$this->set_property(self::API_KEY, $api_key);
	}

	public function get_default_marker_address(): ?string
	{
		return $this->get_property(self::DEFAULT_MARKER_ADDRESS);
	}

	public function set_default_marker_address(?string $default_marker_address): void
	{
		$this->set_property(self::DEFAULT_MARKER_ADDRESS, $default_marker_address);
	}

	public function get_default_marker_latitude(): float
	{
		return $this->get_property(self::DEFAULT_MARKER_LATITUDE);
	}

	public function set_default_marker_latitude(float $default_marker_latitude): void
	{
		$this->set_property(self::DEFAULT_MARKER_LATITUDE, $default_marker_latitude);
	}

	public function get_default_marker_longitude(): float
	{
		return $this->get_property(self::DEFAULT_MARKER_LONGITUDE);
	}

	public function set_default_marker_longitude(float $default_marker_longitude): void
	{
		$this->set_property(self::DEFAULT_MARKER_LONGITUDE, $default_marker_longitude);
	}

	public function get_default_zoom(): int
	{
		return $this->get_property(self::DEFAULT_ZOOM);
	}

	public function set_default_zoom(int $default_zoom): void
	{
		$this->set_property(self::DEFAULT_ZOOM, $default_zoom);
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_default_values()
	{
		return [
			self::API_KEY => '',
			self::DEFAULT_MARKER_ADDRESS => '',
			self::DEFAULT_MARKER_LATITUDE => '48.85339964950244',
			self::DEFAULT_MARKER_LONGITUDE => '2.3487655397918843',
			self::DEFAULT_ZOOM => 14
		];
	}

	/**
	 * Returns the configuration.
	 * @return GoogleMapsConfig
	 */
	public static function load(): GoogleMapsConfig
	{
		return ConfigManager::load(self::class, 'google-maps', 'config');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save(): void
	{
		ConfigManager::save('google-maps', self::load(), 'config');
	}
}
?>
