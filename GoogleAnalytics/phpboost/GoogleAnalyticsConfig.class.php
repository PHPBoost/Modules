<?php
/**
 * This class contains the configuration of the GoogleAnalytics module.
 * @copyright   &copy; 2005-2025 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2016 10 24
 * @since       PHPBoost 3.0 - 2012 12 20
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class GoogleAnalyticsConfig extends AbstractConfigData
{
	const IDENTIFIER = 'identifier';

	public function get_identifier()
	{
		return $this->get_property(self::IDENTIFIER);
	}

	public function set_identifier($identifier)
	{
		$this->set_property(self::IDENTIFIER, $identifier);
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_default_values()
	{
		return array(
			self::IDENTIFIER => ''
		);
	}

	/**
	 * Returns the configuration.
	 * @return GoogleAnalyticsConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'GoogleAnalytics', 'config');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('GoogleAnalytics', self::load(), 'config');
	}
}
?>
