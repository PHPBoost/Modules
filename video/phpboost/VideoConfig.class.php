<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 10 17
 * @since       PHPBoost 6.0 - 2022 10 17
 */

class VideoConfig extends AbstractConfigData
{
	const CATEGORIES_PER_PAGE      = 'categories_per_page';
	const CATEGORIES_PER_ROW       = 'categories_per_row';
	const ITEMS_PER_PAGE           = 'items_per_page';
	const ITEMS_PER_ROW            = 'items_per_row';
	const ITEMS_DEFAULT_SORT_FIELD = 'items_default_sort_field';
	const ITEMS_DEFAULT_SORT_MODE  = 'items_default_sort_mode';

	const DEFAULT_CONTENT = 'default_content';

	const SUMMARY_DISPLAYED_TO_GUESTS = 'summary_displayed_to_guests';
	const AUTHOR_DISPLAYED            = 'author_displayed';
	const VIEWS_NUMBER_ENABLED 		  = 'views_number_enabled';
	const ROOT_CATEGORY_DESCRIPTION   = 'root_category_description';
	const SORT_TYPE                   = 'sort_type';
	const FILES_NUMBER_IN_MENU        = 'files_number_in_menu';
	const AUTHORIZATIONS              = 'authorizations';

	const DISPLAY_TYPE = 'display_type';
	const GRID_VIEW    = 'grid_view';
	const LIST_VIEW    = 'list_view';
	const TABLE_VIEW   = 'table_view';

	const SUBCATEGORIES_DISPLAY = 'subcategories_display';

	CONST MIME_TYPE_LIST  = 'MIME_TYPE_LIST';
	const PLAYERS  = 'players';

	const DEFERRED_OPERATIONS = 'deferred_operations';

	const AUTO_CUT_CHARACTERS_NUMBER = 'auto_cut_characters_number';

	public function get_categories_per_page()
	{
		return $this->get_property(self::CATEGORIES_PER_PAGE);
	}

	public function set_categories_per_page($value)
	{
		$this->set_property(self::CATEGORIES_PER_PAGE, $value);
	}

	public function get_categories_per_row()
	{
		return $this->get_property(self::CATEGORIES_PER_ROW);
	}

	public function set_categories_per_row($value)
	{
		$this->set_property(self::CATEGORIES_PER_ROW, $value);
	}

	public function get_items_per_page()
	{
		return $this->get_property(self::ITEMS_PER_PAGE);
	}

	public function set_items_per_page($value)
	{
		$this->set_property(self::ITEMS_PER_PAGE, $value);
	}

	public function get_items_per_row()
	{
		return $this->get_property(self::ITEMS_PER_ROW);
	}

	public function set_items_per_row($value)
	{
		$this->set_property(self::ITEMS_PER_ROW, $value);
	}

	public function get_display_type()
	{
		return $this->get_property(self::DISPLAY_TYPE);
	}

	public function set_display_type($value)
	{
		$this->set_property(self::DISPLAY_TYPE, $value);
	}

	public function get_items_default_sort_field()
	{
		return $this->get_property(self::ITEMS_DEFAULT_SORT_FIELD);
	}

	public function set_items_default_sort_field($value)
	{
		$this->set_property(self::ITEMS_DEFAULT_SORT_FIELD, $value);
	}

	public function get_items_default_sort_mode()
	{
		return $this->get_property(self::ITEMS_DEFAULT_SORT_MODE);
	}

	public function set_items_default_sort_mode($value)
	{
		$this->set_property(self::ITEMS_DEFAULT_SORT_MODE, $value);
	}

	public function get_default_content()
	{
		return $this->get_property(self::DEFAULT_CONTENT);
	}

	public function set_default_content($value)
	{
		$this->set_property(self::DEFAULT_CONTENT, $value);
	}

	public function display_summary_to_guests()
	{
		$this->set_property(self::SUMMARY_DISPLAYED_TO_GUESTS, true);
	}

	public function hide_summary_to_guests()
	{
		$this->set_property(self::SUMMARY_DISPLAYED_TO_GUESTS, false);
	}

	public function is_summary_displayed_to_guests()
	{
		return $this->get_property(self::SUMMARY_DISPLAYED_TO_GUESTS);
	}

	public function display_author()
	{
		$this->set_property(self::AUTHOR_DISPLAYED, true);
	}

	public function hide_author()
	{
		$this->set_property(self::AUTHOR_DISPLAYED, false);
	}

	public function is_author_displayed()
	{
		return $this->get_property(self::AUTHOR_DISPLAYED);
	}

	public function get_enabled_views_number()
	{
		return $this->get_property(self::VIEWS_NUMBER_ENABLED);
	}

	public function set_enabled_views_number($views_number_enabled)
	{
		$this->set_property(self::VIEWS_NUMBER_ENABLED, $views_number_enabled);
	}

	public function get_root_category_description()
	{
		return $this->get_property(self::ROOT_CATEGORY_DESCRIPTION);
	}

	public function set_root_category_description($value)
	{
		$this->set_property(self::ROOT_CATEGORY_DESCRIPTION, $value);
	}

	public function get_sort_type()
	{
		return $this->get_property(self::SORT_TYPE);
	}

	public function set_sort_type($value)
	{
		$this->set_property(self::SORT_TYPE, $value);
	}

	public function is_sort_type_date()
	{
		return $this->get_property(self::SORT_TYPE) == VideoItem::SORT_DATE || $this->get_property(self::SORT_TYPE) == VideoItem::SORT_UPDATE_DATE;
	}

	public function is_sort_type_views_numbers()
	{
		return $this->get_property(self::SORT_TYPE) == VideoItem::SORT_VIEWS_NUMBERS;
	}

	public function is_sort_type_notation()
	{
		return $this->get_property(self::SORT_TYPE) == VideoItem::SORT_NOTATION;
	}

	public function get_files_number_in_menu()
	{
		return $this->get_property(self::FILES_NUMBER_IN_MENU);
	}

	public function set_files_number_in_menu($value)
	{
		$this->set_property(self::FILES_NUMBER_IN_MENU, $value);
	}

	public function get_mime_type_list()
	{
		return $this->get_property(self::MIME_TYPE_LIST);
	}

	public function set_mime_type_list(array $mime_type_list)
	{
		$this->set_property(self::MIME_TYPE_LIST, $mime_type_list);
	}

	public function get_players()
	{
		return $this->get_property(self::PLAYERS);
	}

	public function set_players(array $players)
	{
		$this->set_property(self::PLAYERS, $players);
	}

	public function get_authorizations()
	{
		return $this->get_property(self::AUTHORIZATIONS);
	}

	public function set_authorizations(Array $authorizations)
	{
		$this->set_property(self::AUTHORIZATIONS, $authorizations);
	}
	
	/**
	 * get_deferred_operations
	 *
	 * @return array
	 */
	public function get_deferred_operations()
	{
		return $this->get_property(self::DEFERRED_OPERATIONS);
	}

	public function set_deferred_operations(Array $deferred_operations)
	{
		$this->set_property(self::DEFERRED_OPERATIONS, $deferred_operations);
	}

	public function get_auto_cut_characters_number()
	{
		return $this->get_property(self::AUTO_CUT_CHARACTERS_NUMBER);
	}

	public function set_auto_cut_characters_number($number)
	{
		$this->set_property(self::AUTO_CUT_CHARACTERS_NUMBER, $number);
	}

	public function get_subcategories_display()
	{
		return $this->get_property(self::SUBCATEGORIES_DISPLAY);
	}

	public function set_subcategories_display($subcategories_display)
	{
		$this->set_property(self::SUBCATEGORIES_DISPLAY, $subcategories_display);
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_default_values()
	{
		return array(
			self::CATEGORIES_PER_PAGE 		  => 10,
			self::CATEGORIES_PER_ROW 		  => 3,
			self::ITEMS_PER_PAGE 			  => 15,
			self::ITEMS_PER_ROW 			  => 2,
			self::SUBCATEGORIES_DISPLAY 				  => true,
			self::DISPLAY_TYPE 				  => self::GRID_VIEW,
			self::ITEMS_DEFAULT_SORT_FIELD 	  => VideoItem::SORT_UPDATE_DATE,
			self::ITEMS_DEFAULT_SORT_MODE  	  => VideoItem::DESC,
			self::DEFAULT_CONTENT 			  => '',
			self::SUMMARY_DISPLAYED_TO_GUESTS => false,
			self::AUTHOR_DISPLAYED 			  => true,
			self::VIEWS_NUMBER_ENABLED 		  => true,
			self::ROOT_CATEGORY_DESCRIPTION   => CategoriesService::get_default_root_category_description('video'),
			self::SORT_TYPE 				  => VideoItem::SORT_DATE,
			self::FILES_NUMBER_IN_MENU 		  => 5,
			self::AUTO_CUT_CHARACTERS_NUMBER  => 128,
			self::AUTHORIZATIONS 			  => array('r-1' => 1, 'r0' => 5, 'r1' => 13),
			self::DEFERRED_OPERATIONS 		  => array(),
			self::MIME_TYPE_LIST 		  	  => array('video/mp4', 'video/ogg', 'video/webm'),
			self::PLAYERS => array(
				array('platform' => 'youtube', 	   'domain' => 'https://www.youtube.com', 	  'player' => 'https://www.youtube.com/embed/'),
				array('platform' => 'youtube', 	   'domain' => 'https://youtu.be', 			  'player' => 'https://www.youtube.com/embed/'),
				array('platform' => 'vimeo', 	   'domain' => 'https://vimeo.com', 		  'player' => 'https://player.vimeo.com/video/'),
				array('platform' => 'vimeo', 	   'domain' => 'https://player.vimeo.com',    'player' => 'https://player.vimeo.com/video/'),
				array('platform' => 'twitch', 	   'domain' => 'https://www.twitch.tv', 	  'player' => 'https://player.twitch.tv/?video='),
				array('platform' => 'twitch', 	   'domain' => 'https://player.twitch.tv', 	  'player' => 'https://player.twitch.tv/?video='),
				array('platform' => 'dailymotion', 'domain' => 'https://www.dailymotion.com', 'player' => 'https://www.dailymotion.com/embed/video/'),
				array('platform' => 'dailymotion', 'domain' => 'https://www.dai.ly', 		  'player' => 'https://www.dailymotion.com/embed/video/'),
				array('platform' => 'odysee', 	   'domain' => 'https://odysee.com', 		  'player' => 'https://odysee.com/$/embed/'),
				array('platform' => 'peertube',    'domain' => 'https://peertube.fr', 		  'player' => 'https://peertube.fr/videos/embed/'),
				array('platform' => 'peertube',    'domain' => 'https://framatube.org', 	  'player' => 'https://framatube.org/videos/embed/')
			)
		);
	}

	/**
	 * Returns the configuration.
	 * @return VideoConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'video', 'config');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('video', self::load(), 'config');
	}
}
?>
