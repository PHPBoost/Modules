<?php
/*##################################################
 *		             BirthdayConfig.class.php
 *                            -------------------
 *   begin                : August 27, 2013
 *   copyright            : (C) 2013 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Comments Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Comments Public License for more details.
 *
 * You should have received a copy of the GNU Comments Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

/**
 * @author Julien BRISWALTER <j1.seth@phpboost.com>
 */
class BirthdayConfig extends AbstractConfigData
{
	const MEMBERS_AGE_DISPLAYED = 'members_age_displayed';
	const PM_FOR_MEMBERS_BIRTHDAY_ENABLED = 'pm_for_members_birthday_enabled';
	const PM_FOR_MEMBERS_BIRTHDAY_TITLE = 'pm_for_members_birthday_title';
	const PM_FOR_MEMBERS_BIRTHDAY_CONTENT = 'pm_for_members_birthday_content';
	
	const AUTHORIZATIONS = 'authorizations';
	
	public function display_members_age()
	{
		$this->set_property(self::MEMBERS_AGE_DISPLAYED, true);
	}
	
	public function hide_members_age()
	{
		$this->set_property(self::MEMBERS_AGE_DISPLAYED, false);
	}
	
	public function is_members_age_displayed()
	{
		return $this->get_property(self::MEMBERS_AGE_DISPLAYED);
	}
	
	public function enable_pm_for_members_birthday()
	{
		$this->set_property(self::PM_FOR_MEMBERS_BIRTHDAY_ENABLED, true);
	}
	
	public function disable_pm_for_members_birthday()
	{
		$this->set_property(self::PM_FOR_MEMBERS_BIRTHDAY_ENABLED, false);
	}
	
	public function is_pm_for_members_birthday_enabled()
	{
		return $this->get_property(self::PM_FOR_MEMBERS_BIRTHDAY_ENABLED);
	}
	
	public function get_pm_for_members_birthday_title()
	{
		return $this->get_property(self::PM_FOR_MEMBERS_BIRTHDAY_TITLE);
	}
	
	public function set_pm_for_members_birthday_title($value)
	{
	$this->set_property(self::PM_FOR_MEMBERS_BIRTHDAY_TITLE, $value);
	}
	
	public function get_pm_for_members_birthday_content()
	{
		return $this->get_property(self::PM_FOR_MEMBERS_BIRTHDAY_CONTENT);
	}
	
	public function set_pm_for_members_birthday_content($value)
	{
		$this->set_property(self::PM_FOR_MEMBERS_BIRTHDAY_CONTENT, $value);
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
	public function set_authorizations(Array $authorizations)
	{
		$this->set_property(self::AUTHORIZATIONS, $authorizations);
	}
	
	/**
	 * @method Get default values.
	 */
	public function get_default_values()
	{
		return array(
			self::MEMBERS_AGE_DISPLAYED => true,
			self::PM_FOR_MEMBERS_BIRTHDAY_ENABLED => false,
			self::PM_FOR_MEMBERS_BIRTHDAY_TITLE => LangLoader::get_message('birthday.config.pm_for_members_birthday.default_title', 'config', 'birthday'),
			self::PM_FOR_MEMBERS_BIRTHDAY_CONTENT => LangLoader::get_message('birthday.config.pm_for_members_birthday.default_content', 'config', 'birthday'),
			self::AUTHORIZATIONS => array('r-1' => 1, 'r0' => 1, 'r1' => 1)
		);
	}
	
	/**
	 * @method Load the birthday configuration.
	 * @return BirthdayConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'birthday', 'config');
	}
	
	/**
	 * @method Saves the birthday configuration in the database. It becomes persistent.
	 */
	public static function save()
	{
		ConfigManager::save('birthday', self::load(), 'config');
	}
}
?>
