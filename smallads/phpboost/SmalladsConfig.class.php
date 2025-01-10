<?php
/**
 * @copyright   &copy; 2005-2025 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2025 01 10
 * @since       PHPBoost 5.0 - 2016 02 02
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
 * @contributor Mipel <mipel@phpboost.com>
*/

class SmalladsConfig extends AbstractConfigData
{
	// Categories
	const ENABLED_SORT_FILTERS = 'enabled_sort_filters';
	const ENABLED_CATS_ICON = 'enabled_cats_icon';
	const ITEMS_PER_ROW = 'items_per_row';
	const ITEMS_PER_PAGE = 'items_per_page';
	const ROOT_CATEGORY_DESCRIPTION = 'root_category_description';
	const DISPLAY_TYPE = 'display_type';
	const GRID_VIEW = 'grid_view';
	const LIST_VIEW = 'list_view';
	const TABLE_VIEW = 'table_view';
	const CHARACTERS_NUMBER_TO_CUT = 'characters_number_to_cut';
	const SUMMARIES_DISPLAYED_TO_GUESTS = 'summaries_displayed_to_guests';
	const AUTHORIZATIONS = 'authorizations';
    const DEFAULT_CONTENTS = 'default_content';

	// Items
	const CURRENCY = 'currency';
	const SMALLAD_TYPES = 'smallad_types';
	const MAX_WEEKS_NUMBER_DISPLAYED = 'max_weeks_number_displayed';
	const MAX_WEEKS_NUMBER = 'max_weeks_number';
	const DISPLAY_DELAY_BEFORE_DELETE = 'display_delay_before_delete';
	const CONTACT_LEVEL = 'contact_level';
	const DISPLAY_EMAIL_ENABLED = 'display_email_enabled';
	const DISPLAY_PM_ENABLED = 'display_pm_enabled';
	const DISPLAY_PHONE_ENABLED = 'display_phone_enabled';
	const ENABLED_ITEMS_SUGGESTIONS = 'enabled_items_suggestions';
	const SUGGESTED_ITEMS_NB = 'suggested_items_nb';
	const ENABLED_NAVIGATION_LINKS = 'enabled_navigation_links';
	const BRANDS = 'brands';
	const LOCATION = 'location';
	const DEFERRED_OPERATIONS = 'deferred_operations';

	// Mini Menu
	const MINI_MENU_ITEMS_NB = 'mini_menu_items_nb';
	const MINI_MENU_ANIMATION_SPEED = 'mini_menu_animation_speed';
	const MINI_MENU_AUTOPLAY = 'mini_menu_autoplay';
	const MINI_MENU_AUTOPLAY_SPEED = 'mini_menu_autoplay_speed';
	const MINI_MENU_AUTOPLAY_HOVER = 'mini_menu_autoplay_hover';

	// Usage terms
	const USAGE_TERMS_ENABLED = 'usage_terms_enabled';
	const USAGE_TERMS = 'usage_terms';

	// categories

	public function enable_sort_filters()
	{
		$this->set_property(self::ENABLED_SORT_FILTERS, true);
	}

	public function disable_sort_filters() {
		$this->set_property(self::ENABLED_SORT_FILTERS, false);
	}

	public function are_sort_filters_enabled()
	{
		return $this->get_property(self::ENABLED_SORT_FILTERS);
	}

	public function get_items_per_page()
	{
		return $this->get_property(self::ITEMS_PER_PAGE);
	}

	public function set_items_per_page($number)
	{
		$this->set_property(self::ITEMS_PER_PAGE, $number);
	}

	public function get_items_per_row()
	{
		return $this->get_property(self::ITEMS_PER_ROW);
	}

	public function set_items_per_row($number)
	{
		$this->set_property(self::ITEMS_PER_ROW, $number);
	}

	public function get_characters_number_to_cut()
	{
		return $this->get_property(self::CHARACTERS_NUMBER_TO_CUT);
	}

	public function set_characters_number_to_cut($number)
	{
		$this->set_property(self::CHARACTERS_NUMBER_TO_CUT, $number);
	}

	public function enable_cats_icon()
	{
		$this->set_property(self::ENABLED_CATS_ICON, true);
	}

	public function disable_cats_icon() {
		$this->set_property(self::ENABLED_CATS_ICON, false);
	}

	public function are_cat_icons_enabled()
	{
		return $this->get_property(self::ENABLED_CATS_ICON);
	}

	public function display_summaries_to_guests()
	{
		$this->set_property(self::SUMMARIES_DISPLAYED_TO_GUESTS, true);
	}

	public function hide_summaries_to_guests()
	{
		$this->set_property(self::SUMMARIES_DISPLAYED_TO_GUESTS, false);
	}

	public function are_summaries_displayed_to_guests()
	{
		return $this->get_property(self::SUMMARIES_DISPLAYED_TO_GUESTS);
	}

	public function get_root_category_description()
	{
		return $this->get_property(self::ROOT_CATEGORY_DESCRIPTION);
	}

	public function set_root_category_description($value)
	{
		$this->set_property(self::ROOT_CATEGORY_DESCRIPTION, $value);
	}

	public function get_enabled_items_suggestions()
	{
		return $this->get_property(self::ENABLED_ITEMS_SUGGESTIONS);
	}

	public function set_enabled_items_suggestions($enabled_items_suggestions)
	{
		$this->set_property(self::ENABLED_ITEMS_SUGGESTIONS, $enabled_items_suggestions);
	}

	public function get_suggested_items_nb()
	{
		return $this->get_property(self::SUGGESTED_ITEMS_NB);
	}

	public function set_suggested_items_nb($number)
	{
		$this->set_property(self::SUGGESTED_ITEMS_NB, $number);
	}

	public function get_enabled_navigation_links()
	{
		return $this->get_property(self::ENABLED_NAVIGATION_LINKS);
	}

	public function set_enabled_navigation_links($enabled_navigation_links)
	{
		$this->set_property(self::ENABLED_NAVIGATION_LINKS, $enabled_navigation_links);
	}

	public function get_smallad_types()
	{
		return $this->get_property(self::SMALLAD_TYPES);
	}

	public function set_smallad_types(Array $smallad_types)
	{
		$this->set_property(self::SMALLAD_TYPES, $smallad_types);
	}

    public function get_default_content()
	{
		return $this->get_property(self::DEFAULT_CONTENTS);
	}

	public function set_default_content($value)
	{
		$this->set_property(self::DEFAULT_CONTENTS, $value);
	}
	public function get_authorizations()
	{
		return $this->get_property(self::AUTHORIZATIONS);
	}

	public function set_authorizations(Array $array)
	{
		$this->set_property(self::AUTHORIZATIONS, $array);
	}

	// Items
	public function get_currency()
	{
		return $this->get_property(self::CURRENCY);
	}

	public function set_currency($value)
	{
		$this->set_property(self::CURRENCY, $value);
	}

	public function get_display_type()
	{
		return $this->get_property(self::DISPLAY_TYPE);
	}

	public function set_display_type($display_type)
	{
		$this->set_property(self::DISPLAY_TYPE, $display_type);
	}

	public function display_max_weeks_number()
	{
		$this->set_property(self::MAX_WEEKS_NUMBER_DISPLAYED, true);
	}

	public function hide_max_weeks_number()
	{
		$this->set_property(self::MAX_WEEKS_NUMBER_DISPLAYED, false);
	}

	public function is_max_weeks_number_displayed()
	{
		return $this->get_property(self::MAX_WEEKS_NUMBER_DISPLAYED);
	}

	public function get_max_weeks_number()
	{
		return $this->get_property(self::MAX_WEEKS_NUMBER);
	}

	public function set_max_weeks_number($value)
	{
		$this->set_property(self::MAX_WEEKS_NUMBER, $value);
	}

	public function get_display_delay_before_delete()
	{
		return $this->get_property(self::DISPLAY_DELAY_BEFORE_DELETE);
	}

	public function set_display_delay_before_delete($delay)
	{
		$this->set_property(self::DISPLAY_DELAY_BEFORE_DELETE, $delay);
	}

	public function visitor_allowed_to_contact()
	{
		$this->set_property(self::CONTACT_LEVEL, true);
	}

	public function visitor_not_allowed_to_contact()
	{
		$this->set_property(self::CONTACT_LEVEL, false);
	}

	public function is_user_allowed()
	{
		return $this->get_property(self::CONTACT_LEVEL);
	}

	public function display_email()
	{
		$this->set_property(self::DISPLAY_EMAIL_ENABLED, true);
	}

	public function hide_email()
	{
		$this->set_property(self::DISPLAY_EMAIL_ENABLED, false);
	}

	public function is_email_displayed()
	{
		return $this->get_property(self::DISPLAY_EMAIL_ENABLED);
	}

	public function display_pm()
	{
		$this->set_property(self::DISPLAY_PM_ENABLED, true);
	}

	public function hide_pm()
	{
		$this->set_property(self::DISPLAY_PM_ENABLED, false);
	}

	public function is_pm_displayed()
	{
		return $this->get_property(self::DISPLAY_PM_ENABLED);
	}

	public function display_phone()
	{
		$this->set_property(self::DISPLAY_PHONE_ENABLED, true);
	}

	public function hide_phone()
	{
		$this->set_property(self::DISPLAY_PHONE_ENABLED, false);
	}

	public function is_phone_displayed()
	{
		return $this->get_property(self::DISPLAY_PHONE_ENABLED);
	}

	public function get_brands()
	{
		return $this->get_property(self::BRANDS);
	}

	public function set_brands(Array $brands)
	{
		$this->set_property(self::BRANDS, $brands);
	}

	public function display_location()
	{
		$this->set_property(self::LOCATION, true);
	}

	public function hide_location()
	{
		$this->set_property(self::LOCATION, false);
	}

	public function is_location_displayed()
	{
		return $this->get_property(self::LOCATION);
	}

	public function is_googlemaps_available()
	{
		return ModulesManager::is_module_installed('GoogleMaps') && ModulesManager::is_module_activated('GoogleMaps') && GoogleMapsConfig::load()->get_api_key();
	}

	public function get_deferred_operations()
	{
		return $this->get_property(self::DEFERRED_OPERATIONS);
	}

	public function set_deferred_operations(Array $deferred_operations)
	{
		$this->set_property(self::DEFERRED_OPERATIONS, $deferred_operations);
	}

	// Mini Menu
	public function get_mini_menu_items_nb()
	{
		return $this->get_property(self::MINI_MENU_ITEMS_NB);
	}

	public function set_mini_menu_items_nb($number)
	{
		$this->set_property(self::MINI_MENU_ITEMS_NB, $number);
	}

	public function get_mini_menu_animation_speed()
	{
		return $this->get_property(self::MINI_MENU_ANIMATION_SPEED);
	}

	public function set_mini_menu_animation_speed($number)
	{
		$this->set_property(self::MINI_MENU_ANIMATION_SPEED, $number);
	}

	public function play_mini_menu_autoplay()
	{
		$this->set_property(self::MINI_MENU_AUTOPLAY, true);
	}

	public function stop_mini_menu_autoplay()
	{
		$this->set_property(self::MINI_MENU_AUTOPLAY, false);
	}

	public function is_slideshow_autoplayed()
	{
		return $this->get_property(self::MINI_MENU_AUTOPLAY);
	}

	public function get_mini_menu_autoplay_speed()
	{
		return $this->get_property(self::MINI_MENU_AUTOPLAY_SPEED);
	}

	public function set_mini_menu_autoplay_speed($number)
	{
		$this->set_property(self::MINI_MENU_AUTOPLAY_SPEED, $number);
	}

	public function play_mini_menu_autoplay_hover()
	{
		$this->set_property(self::MINI_MENU_AUTOPLAY_HOVER, true);
	}

	public function stop_mini_menu_autoplay_hover()
	{
		$this->set_property(self::MINI_MENU_AUTOPLAY_HOVER, false);
	}

	public function is_slideshow_hover_enabled()
	{
		return $this->get_property(self::MINI_MENU_AUTOPLAY_HOVER);
	}

	// Usage terms
	public function display_usage_terms()
	{
		$this->set_property(self::USAGE_TERMS_ENABLED, true);
	}

	public function hide_usage_terms()
	{
		$this->set_property(self::USAGE_TERMS_ENABLED, false);
	}

	public function are_usage_terms_displayed()
	{
		return $this->get_property(self::USAGE_TERMS_ENABLED);
	}

	public function get_usage_terms()
	{
		return $this->get_property(self::USAGE_TERMS);
	}

	public function set_usage_terms($value)
	{
		$this->set_property(self::USAGE_TERMS, $value);
	}

	public function get_default_values()
	{
		$lang = LangLoader::get_all_langs('smallads');
		return [
			// Categories
			self::ENABLED_SORT_FILTERS => true,
			self::ENABLED_CATS_ICON => false,
			self::ITEMS_PER_ROW => 2,
			self::ITEMS_PER_PAGE => 10,
			self::DISPLAY_TYPE => self::GRID_VIEW,
			self::CHARACTERS_NUMBER_TO_CUT => 128,
			self::SUMMARIES_DISPLAYED_TO_GUESTS => false,
			self::ROOT_CATEGORY_DESCRIPTION => $lang['smallads.root.category.description'],
            self::DEFAULT_CONTENTS => '',
			self::AUTHORIZATIONS => ['r-1' => 1, 'r0' => 5, 'r1' => 21],

			// Items
			self::CURRENCY => '€',
			self::SMALLAD_TYPES => [$lang['smallads.default.type']],
			self::MAX_WEEKS_NUMBER_DISPLAYED => true,
			self::MAX_WEEKS_NUMBER => 12,
			self::DISPLAY_DELAY_BEFORE_DELETE => 2,
			self::CONTACT_LEVEL => true,
			self::DISPLAY_EMAIL_ENABLED => true,
			self::DISPLAY_PM_ENABLED => true,
			self::DISPLAY_PHONE_ENABLED => true,
			self::ENABLED_ITEMS_SUGGESTIONS => false,
			self::SUGGESTED_ITEMS_NB => 4,
			self::ENABLED_NAVIGATION_LINKS => false,
			self::BRANDS => [],
			self::LOCATION => true,
			self::DEFERRED_OPERATIONS => [],

			// Mini Menu
			self::MINI_MENU_ITEMS_NB => 5,
			self::MINI_MENU_ANIMATION_SPEED => '1000',
			self::MINI_MENU_AUTOPLAY => true,
			self::MINI_MENU_AUTOPLAY_SPEED => '3000',
			self::MINI_MENU_AUTOPLAY_HOVER => true,

			// Usage Terms
			self::USAGE_TERMS_ENABLED => false,
			self::USAGE_TERMS => LangLoader::get_message('smallads.default.usage.terms', 'install', 'smallads'),
        ];
	}

	/**
	 * Returns the configuration.
	 * @return SmalladsConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'smallads', 'config');
	}

	/**
	 * Saves the configuration in the database
	 */
	public static function save()
	{
		ConfigManager::save('smallads', self::load(), 'config');
	}
}
?>
