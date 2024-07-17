<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2024 07 17
 * @since       PHPBoost 6.0 - 2023 01 17
*/

class TagcloudConfig extends AbstractConfigData
{
	const AUTHORIZATIONS = 'authorizations';

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
		return [
			self::AUTHORIZATIONS => ['r-1' => 3, 'r0' => 3, 'r1' => 7]
        ];
	}

	/**
	 * Returns the configuration.
	 * @return TagcloudConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'tagcloud', 'config');
	}

	/**
	 * Saving the configuration in database.
	 */
	public static function save()
	{
		ConfigManager::save('tagcloud', self::load(), 'config');
	}
}
?>
