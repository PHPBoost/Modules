<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 11
 * @since       PHPBoost 4.0 - 2013 08 27
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class BirthdayConfig extends AbstractConfigData
{
	const COMING_NEXT = 'coming_next';
	const MEMBERS_AGE_DISPLAYED = 'members_age_displayed';
	const PM_FOR_MEMBERS_BIRTHDAY_ENABLED = 'pm_for_members_birthday_enabled';
	const PM_FOR_MEMBERS_BIRTHDAY_TITLE = 'pm_for_members_birthday_title';
	const PM_FOR_MEMBERS_BIRTHDAY_CONTENT = 'pm_for_members_birthday_content';

	const AUTHORIZATIONS = 'authorizations';

	public function get_coming_next(): mixed
	{
		return $this->get_property(self::COMING_NEXT);
	}

	public function set_coming_next(mixed $value): void
	{
	$this->set_property(self::COMING_NEXT, $value);
	}

	public function display_members_age(): void
	{
		$this->set_property(self::MEMBERS_AGE_DISPLAYED, true);
	}

	public function hide_members_age(): void
	{
		$this->set_property(self::MEMBERS_AGE_DISPLAYED, false);
	}

	public function is_members_age_displayed(): bool
	{
		return $this->get_property(self::MEMBERS_AGE_DISPLAYED);
	}

	public function enable_pm_for_members_birthday(): void
	{
		$this->set_property(self::PM_FOR_MEMBERS_BIRTHDAY_ENABLED, true);
	}

	public function disable_pm_for_members_birthday(): void
	{
		$this->set_property(self::PM_FOR_MEMBERS_BIRTHDAY_ENABLED, false);
	}

	public function is_pm_for_members_birthday_enabled(): bool
	{
		return $this->get_property(self::PM_FOR_MEMBERS_BIRTHDAY_ENABLED);
	}

	public function get_pm_for_members_birthday_title(): string
	{
		return $this->get_property(self::PM_FOR_MEMBERS_BIRTHDAY_TITLE);
	}

	public function set_pm_for_members_birthday_title(string $value): void
	{
	$this->set_property(self::PM_FOR_MEMBERS_BIRTHDAY_TITLE, $value);
	}


	public function get_pm_for_members_birthday_content(): string
	{
		return $this->get_property(self::PM_FOR_MEMBERS_BIRTHDAY_CONTENT);
	}

	public function set_pm_for_members_birthday_content(string $value): void
	{
		$this->set_property(self::PM_FOR_MEMBERS_BIRTHDAY_CONTENT, $value);
	}

	public function get_authorizations(): array
	{
		return $this->get_property(self::AUTHORIZATIONS);
	}

	public function set_authorizations(array $authorizations): void
	{
		$this->set_property(self::AUTHORIZATIONS, $authorizations);
	}

	public function get_default_values(): array
	{
		return [
			self::COMING_NEXT => 7,
			self::MEMBERS_AGE_DISPLAYED => true,
			self::PM_FOR_MEMBERS_BIRTHDAY_ENABLED => false,
			self::PM_FOR_MEMBERS_BIRTHDAY_TITLE => LangLoader::get_message('birthday.config.pm.for.members.birthday.default.title', 'common', 'birthday'),
			self::PM_FOR_MEMBERS_BIRTHDAY_CONTENT => LangLoader::get_message('birthday.config.pm.for.members.birthday.default.content', 'common', 'birthday'),
			self::AUTHORIZATIONS => ['r-1' => 1, 'r0' => 1, 'r1' => 1]
		];
	}

	public static function load(): BirthdayConfig
	{
		return ConfigManager::load(self::class, 'birthday', 'config');
	}

	public static function save(): void
	{
		ConfigManager::save('birthday', self::load(), 'config');
	}
}
?>
