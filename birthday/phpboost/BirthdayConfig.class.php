<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 02 03
 * @since       PHPBoost 4.0 - 2013 08 27
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class BirthdayConfig extends AbstractConfigData
{
	const COMING_NEXT = 'coming_next';
	const MEMBERS_AGE_DISPLAYED = 'members_age_displayed';
	const PM_FOR_MEMBERS_BIRTHDAY_ENABLED = 'pm_for_members_birthday_enabled';
	const PM_FOR_MEMBERS_BIRTHDAY_TITLE = 'pm_for_members_birthday_title';
	const PM_FOR_MEMBERS_BIRTHDAY_CONTENT = 'pm_for_members_birthday_content';

	const AUTHORIZATIONS = 'authorizations';

	public function get_coming_next()
	{
		return $this->get_property(self::COMING_NEXT);
	}

	public function set_coming_next($value)
	{
	$this->set_property(self::COMING_NEXT, $value);
	}

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
			self::COMING_NEXT => 7,
			self::MEMBERS_AGE_DISPLAYED => true,
			self::PM_FOR_MEMBERS_BIRTHDAY_ENABLED => false,
			self::PM_FOR_MEMBERS_BIRTHDAY_TITLE => LangLoader::get_message('birthday.config.pm.for.members.birthday.default.title', 'common', 'birthday'),
			self::PM_FOR_MEMBERS_BIRTHDAY_CONTENT => LangLoader::get_message('birthday.config.pm.for.members.birthday.default.content', 'common', 'birthday'),
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
