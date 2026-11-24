<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 24
 * @since       PHPBoost 3.0 - 2010 08 10
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Arnaud GENET <elenwii@phpboost.com>
 * @author      Mipel <mipel@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 *
*/

class CalendarConfig extends AbstractConfigData
{
	const ITEMS_PER_PAGE = 'items_per_page';
	const ITEMS_PER_ROW  = 'items_per_row';
	const EVENT_COLOR    = 'event_color';
	const BIRTHDAY_COLOR = 'birthday_color';
	const MEMBERS_BIRTHDAY_ENABLED = 'members_birthday_enabled';

    const DEFAULT_CONTENT = 'default_content';

	const AUTHORIZATIONS = 'authorizations';

	const CHARACTERS_NUMBER_TO_CUT = 'characters_number_to_cut';

	const DISPLAY_TYPE      = 'display_type';
	const GRID_VIEW         = 'grid_view';
	const LIST_VIEW         = 'list_view';
	const TABLE_VIEW        = 'table_view';
	const FULL_ITEM_DISPLAY = 'full_item_display';

	public function get_items_per_page(): int
	{
		return $this->get_property(self::ITEMS_PER_PAGE);
	}

	public function set_items_per_page(int $value): void
	{
		$this->set_property(self::ITEMS_PER_PAGE, $value);
	}

	public function get_items_per_row(): int
	{
		return $this->get_property(self::ITEMS_PER_ROW);
	}

	public function set_items_per_row(int $value): void
	{
		$this->set_property(self::ITEMS_PER_ROW, $value);
	}

	public function display_full_item(): void
	{
		$this->set_property(self::FULL_ITEM_DISPLAY, true);
	}

	public function display_condensed_item(): void
	{
		$this->set_property(self::FULL_ITEM_DISPLAY, false);
	}

	public function is_full_item_displayed(): bool
	{
		return $this->get_property(self::FULL_ITEM_DISPLAY);
	}

	public function get_display_type(): string
	{
		return $this->get_property(self::DISPLAY_TYPE);
	}

	public function set_display_type(string $value): void
	{
		$this->set_property(self::DISPLAY_TYPE, $value);
	}

	public function get_characters_number_to_cut(): int
	{
		return $this->get_property(self::CHARACTERS_NUMBER_TO_CUT);
	}

	public function set_characters_number_to_cut(int $number): void
	{
		$this->set_property(self::CHARACTERS_NUMBER_TO_CUT, $number);
	}

    public function get_default_content(): string
	{
		return $this->get_property(self::DEFAULT_CONTENT);
	}

	public function set_default_content(string $value): void
	{
		$this->set_property(self::DEFAULT_CONTENT, $value);
	}

	public function enable_members_birthday(): void
	{
		$this->set_property(self::MEMBERS_BIRTHDAY_ENABLED, true);
	}

	public function disable_members_birthday(): void
	{
		$this->set_property(self::MEMBERS_BIRTHDAY_ENABLED, false);
	}

	public function is_members_birthday_enabled(): bool
	{
		return $this->get_property(self::MEMBERS_BIRTHDAY_ENABLED);
	}

	public function get_event_color(): string
	{
		return $this->get_property(self::EVENT_COLOR);
	}

	public function set_event_color(string $value): void
	{
		$this->set_property(self::EVENT_COLOR, $value);
	}

	public function get_birthday_color(): string
	{
		return $this->get_property(self::BIRTHDAY_COLOR);
	}

	public function set_birthday_color(string $value): void
	{
		$this->set_property(self::BIRTHDAY_COLOR, $value);
	}

	public function is_googlemaps_available(): bool
	{
		return ModulesManager::is_module_installed('GoogleMaps') && ModulesManager::is_module_activated('GoogleMaps') && GoogleMapsConfig::load()->get_api_key();
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
			self::ITEMS_PER_PAGE    => 15,
			self::ITEMS_PER_ROW     => 2,
			self::FULL_ITEM_DISPLAY => false,
			self::MEMBERS_BIRTHDAY_ENABLED => false,
			self::CHARACTERS_NUMBER_TO_CUT => 128,
            self::DISPLAY_TYPE    => self::LIST_VIEW,
            self::DEFAULT_CONTENT => '',
			self::EVENT_COLOR     => '#81A4C8',
			self::BIRTHDAY_COLOR  => '#8F8ACF',
			self::AUTHORIZATIONS  => ['r-1' => 1, 'r0' => 5, 'r1' => 21]
		];
	}

	public static function load(): CalendarConfig
	{
		return ConfigManager::load(self::class, 'calendar', 'config');
	}

	public static function save(): void
	{
		ConfigManager::save('calendar', self::load(), 'config');
	}
}
?>
