<?php
/*##################################################
 *		                   LastcomsConfig.class.php
 *                            -------------------
 *   begin                             : July 26, 2009
 *   copyright                         : (C) 2009 ROGUELON Geoffrey
 *   email                             : liaght@gmail.com
 *   Adapted for Phpboost since 4.1 by : babsolune - babsolune@phpboost.com
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

class LastcomsConfig extends AbstractConfigData
{
	const LASTCOMS_NUMBER = 'lastcoms_number';
	const LASTCOMS_CHAR = 'lastcoms_char';
	const AUTHORIZATIONS = 'authorizations';

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
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('lastcoms', self::load(), 'config');
	}
}
?>
