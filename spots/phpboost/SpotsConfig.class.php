<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 09 16
 * @since       PHPBoost 6.0 - 2021 08 22
*/

class SpotsConfig extends AbstractConfigData
{
    const MODULE_NAME     = 'module_name';
    const NEW_WINDOW      = 'new_window';
	const ITEMS_PER_PAGE  = 'items_per_page';
	const ITEMS_PER_ROW   = 'items_per_row';
	const DEFAULT_CONTENT = 'default_content';

	const CATEGORIES_PER_PAGE       = 'categories_per_page';
	const CATEGORIES_PER_ROW        = 'categories_per_row';
	const ROOT_CATEGORY_DESCRIPTION = 'root_category_description';
	const DISPLAY_TYPE              = 'display_type';
	const GRID_VIEW                 = 'grid_view';
	const TABLE_VIEW                = 'table_view';
	const DEFAULT_COLOR             = 'default_color';
	const DEFAULT_INNER_ICON        = 'default_inner_icon';

	const AUTHORIZATIONS = 'authorizations';

	public function get_module_name()
	{
		return $this->get_property(self::MODULE_NAME);
	}

	public function set_module_name($value)
	{
		$this->set_property(self::MODULE_NAME, $value);
	}

	public function get_new_window()
	{
		return $this->get_property(self::NEW_WINDOW);
	}

	public function set_new_window($value)
	{
		$this->set_property(self::NEW_WINDOW, $value);
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

	public function get_display_type()
	{
		return $this->get_property(self::DISPLAY_TYPE);
	}

	public function set_display_type($value)
	{
		$this->set_property(self::DISPLAY_TYPE, $value);
	}

	public function get_default_color()
	{
		return $this->get_property(self::DEFAULT_COLOR);
	}

	public function set_default_color($value)
	{
		$this->set_property(self::DEFAULT_COLOR, $value);
	}

	public function get_default_inner_icon()
	{
		return $this->get_property(self::DEFAULT_INNER_ICON);
	}

	public function set_default_inner_icon($value)
	{
		$this->set_property(self::DEFAULT_INNER_ICON, $value);
	}

	public function get_root_category_description()
	{
		return $this->get_property(self::ROOT_CATEGORY_DESCRIPTION);
	}

	public function set_root_category_description($value)
	{
		$this->set_property(self::ROOT_CATEGORY_DESCRIPTION, $value);
	}

	public function get_default_content()
	{
		return $this->get_property(self::DEFAULT_CONTENT);
	}

	public function set_default_content($value)
	{
		$this->set_property(self::DEFAULT_CONTENT, $value);
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
	 * {@inheritdoc}
	 */
	public function get_default_values()
	{
		return array(
            self::MODULE_NAME               => LangLoader::get_message('spots.module.title', 'common', 'spots'),
            self::NEW_WINDOW                => false,
			self::ITEMS_PER_PAGE            => 16,
			self::ITEMS_PER_ROW             => 2,
			self::DEFAULT_CONTENT           => '',
			self::CATEGORIES_PER_PAGE       => 10,
			self::CATEGORIES_PER_ROW        => 2,
			self::DISPLAY_TYPE              => self::TABLE_VIEW,
			self::DEFAULT_COLOR             => '#366493',
			self::DEFAULT_INNER_ICON        => 'fa fa-circle',
			self::ROOT_CATEGORY_DESCRIPTION => LangLoader::get_message('spots.root.category.description', 'common', 'spots'),
			self::AUTHORIZATIONS            => array('r-1' => 1, 'r0' => 5, 'r1' => 13),
		);
	}

	/**
	 * Returns the configuration.
	 * @return SpotsConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'spots', 'config');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('spots', self::load(), 'config');
	}
}
?>
