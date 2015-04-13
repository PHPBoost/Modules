<?php
/*##################################################
 *		                  GoogleAnalyticsConfig.class.php
 *                            -------------------
 *   begin                : December 20, 2012
 *   copyright            : (C) 2012 Kévin MASSY
 *   email                : kevin.massy@phpboost.com
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
 * This class contains the configuration of the GoogleAnalytics module.
 * @author Kévin MASSY <kevin.massy@phpboost.com>
 *
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