<?php
/**
 * @copyright   &copy; 2005-2025 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 10 25
 * @since       PHPBoost 5.0 - 2016 01 02
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class HomeLandingConfig extends AbstractConfigData
{
	const MODULE_TITLE = 'module_title';

	const LEFT_COLUMNS = 'left_columns';
	const RIGHT_COLUMNS = 'right_columns';
	const TOP_CENTRAL = 'top_central';
	const BOTTOM_CENTRAL = 'bottom_central';
	const TOP_FOOTER = 'top_footer';

	const MODULES_LIST = 'modules_list';

	const CAROUSEL = 'carousel';
	const CAROUSEL_SPEED = 'carousel_speed';
	const CAROUSEL_TIME = 'carousel_time';
	const CAROUSEL_NUMBER = 'carousel_number';
	const CAROUSEL_AUTO = 'carousel_auto';
	const CAROUSEL_HOVER = 'carousel_hover';
	const CAROUSEL_TRUE = 'true';
	const CAROUSEL_FALSE = 'false';

	const ANCHORS_MENU = 'anchors_menu';
	const EDITO = 'edito';
	const PINNED_NEWS_TITLE = 'pinned_news_title';

	const STICKY_TEXT = 'sticky_text';
	const STICKY_TITLE = 'sticky_title';

	const ELEMENTS_NUMBER_DISPLAYED = 5;
	const CHARACTERS_NUMBER_DISPLAYED = 128;

	// Modules
	const MODULE_CAROUSEL = 'carousel';
	const MODULE_ANCHORS_MENU = 'anchors_menu';
	const MODULE_EDITO = 'edito';
	const MODULE_LASTCOMS = 'lastcoms';
	const MODULE_ARTICLES = 'articles';
	const MODULE_ARTICLES_CATEGORY = 'articles_category';
	const MODULE_CALENDAR = 'calendar';
	const MODULE_CONTACT = 'contact';
	const MODULE_DOWNLOAD = 'download';
	const MODULE_DOWNLOAD_CATEGORY = 'download_category';
	const MODULE_FLUX = 'flux';
	const MODULE_FORUM = 'forum';
	const MODULE_GALLERY = 'gallery';
	const MODULE_GUESTBOOK = 'guestbook';
	const MODULE_MEDIA = 'media';
	const MODULE_NEWS = 'news';
	const MODULE_NEWS_CATEGORY = 'news_category';
	const MODULE_PINNED_NEWS = 'pinned_news';
	const MODULE_RECIPE = 'recipe';
	const MODULE_RECIPE_CATEGORY = 'recipe_category';
	const MODULE_SMALLADS = 'smallads';
	const MODULE_SMALLADS_CATEGORY = 'smallads_category';
	const MODULE_VIDEO = 'video';
	const MODULE_VIDEO_CATEGORY = 'video_category';
	const MODULE_WEB = 'web';
	const MODULE_WEB_CATEGORY = 'web_category';

	// Additional modules

	// Module Title
	public function get_module_title()
	{
		return $this->get_property(self::MODULE_TITLE);
	}

	public function set_module_title($module_title)
	{
		$this->set_property(self::MODULE_TITLE, $module_title);
	}

	// Menus Management
	public function get_left_columns()
	{
		return $this->get_property(self::LEFT_COLUMNS);
	}

	public function set_left_columns($left_columns)
	{
		$this->set_property(self::LEFT_COLUMNS, $left_columns);
	}

	public function get_right_columns()
	{
		return $this->get_property(self::RIGHT_COLUMNS);
	}

	public function set_right_columns($right_columns)
	{
		$this->set_property(self::RIGHT_COLUMNS, $right_columns);
	}

	public function get_top_central()
	{
		return $this->get_property(self::TOP_CENTRAL);
	}

	public function set_top_central($top_central)
	{
		$this->set_property(self::TOP_CENTRAL, $top_central);
	}

	public function get_bottom_central()
	{
		return $this->get_property(self::BOTTOM_CENTRAL);
	}

	public function set_bottom_central($bottom_central)
	{
		$this->set_property(self::BOTTOM_CENTRAL, $bottom_central);
	}

	public function get_top_footer()
	{
		return $this->get_property(self::TOP_FOOTER);
	}

	public function set_top_footer($top_footer)
	{
		$this->set_property(self::TOP_FOOTER, $top_footer);
	}

	// Modules list
	public function get_modules()
	{
		return $this->get_property(self::MODULES_LIST);
	}

	public function set_modules(Array $array)
	{
		$this->set_property(self::MODULES_LIST, $array);
	}

	public function get_module_position_by_id($module_id)
	{
		$position = null;
		foreach (self::get_modules() as $key => $module)
		{
			if ($module['module_id'] == $module_id)
				$position = $key;
		}
		return $position;
	}

	public function get_module_id_category($module_id)
	{
		$id_category = Category::ROOT_CATEGORY;
		foreach (self::get_modules() as $key => $module)
		{
			if ($module['module_id'] == $module_id && $module['id_category'])
				$id_category = $module['id_category'];
		}
		return $id_category;
	}

	// Anchors menu
	public function get_anchors_menu()
	{
		return $this->get_property(self::ANCHORS_MENU);
	}

	public function set_anchors_menu($anchors_menu)
	{
		$this->set_property(self::ANCHORS_MENU, $anchors_menu);
	}

	// Carousel
	public function get_carousel()
	{
		return $this->get_property(self::CAROUSEL);
	}

	public function set_carousel($carousel)
	{
		$this->set_property(self::CAROUSEL, $carousel);
	}

	public function get_carousel_speed()
	{
		return $this->get_property(self::CAROUSEL_SPEED);
	}

	public function set_carousel_speed($speed)
	{
		$this->set_property(self::CAROUSEL_SPEED, $speed);
	}

	public function get_carousel_time()
	{
		return $this->get_property(self::CAROUSEL_TIME);
	}

	public function set_carousel_time($time)
	{
		$this->set_property(self::CAROUSEL_TIME, $time);
	}

	public function get_carousel_number()
	{
		return $this->get_property(self::CAROUSEL_NUMBER);
	}

	public function set_carousel_number($number)
	{
		$this->set_property(self::CAROUSEL_NUMBER, $number);
	}

	public function get_carousel_auto()
	{
		return $this->get_property(self::CAROUSEL_AUTO);
	}

	public function set_carousel_auto($auto)
	{
		$this->set_property(self::CAROUSEL_AUTO, $auto);
	}

	public function get_carousel_hover()
	{
		return $this->get_property(self::CAROUSEL_HOVER);
	}

	public function set_carousel_hover($hover)
	{
		$this->set_property(self::CAROUSEL_HOVER, $hover);
	}

	// Edito
	public function get_edito()
	{
		return $this->get_property(self::EDITO);
	}

	public function set_edito($edito)
	{
		$this->set_property(self::EDITO, $edito);
	}

	// Pinned news title
	public function get_pinned_news_title()
	{
		return $this->get_property(self::PINNED_NEWS_TITLE);
	}

	public function set_pinned_news_title($edito)
	{
		$this->set_property(self::PINNED_NEWS_TITLE, $edito);
	}

	public function get_sticky_text()
    {
        return $this->get_property(self::STICKY_TEXT);
    }

	public function set_sticky_text($value)
    {
        $this->set_property(self::STICKY_TEXT, $value);
    }

	public function get_sticky_title()
    {
        return $this->get_property(self::STICKY_TITLE);
    }

	public function set_sticky_title($value)
    {
        $this->set_property(self::STICKY_TITLE, $value);
    }

	// Modules list
	private function init_modules_array()
	{
		$modules = array();

		$module = new HomeLandingModule();
		$module->set_module_id(self::MODULE_ANCHORS_MENU);
		$module->hide();
		$modules[1] = $module->get_properties();

		$module = new HomeLandingModule();
		$module->set_module_id(self::MODULE_CAROUSEL);
		$module->hide();
		$modules[2] = $module->get_properties();

		$module = new HomeLandingModule();
		$module->set_module_id(self::MODULE_EDITO);
		$module->display();
		$modules[] = $module->get_properties();

		$module = new HomeLandingModule();
		$module->set_module_id(self::MODULE_LASTCOMS);
		$module->set_characters_number_displayed(30);
		$module->hide();
		$modules[] = $module->get_properties();

		$module = new HomeLandingModule();
		$module->set_module_id(self::MODULE_ARTICLES);
		$module->set_phpboost_module_id(self::MODULE_ARTICLES);
		$module->hide();
		$modules[] = $module->get_properties();

		$module = new HomeLandingModuleCategory();
		$module->set_module_id(self::MODULE_ARTICLES_CATEGORY);
		$module->set_phpboost_module_id(self::MODULE_ARTICLES);
		$module->hide();
		$modules[] = $module->get_properties();

		$module = new HomeLandingModule();
		$module->set_module_id(self::MODULE_CALENDAR);
		$module->set_phpboost_module_id(self::MODULE_CALENDAR);
		$module->hide();
		$modules[] = $module->get_properties();

		$module = new HomeLandingModule();
		$module->set_module_id(self::MODULE_CONTACT);
		$module->set_phpboost_module_id(self::MODULE_CONTACT);
		$module->hide();
		$modules[] = $module->get_properties();

		$module = new HomeLandingModule();
		$module->set_module_id(self::MODULE_DOWNLOAD);
		$module->set_phpboost_module_id(self::MODULE_DOWNLOAD);
		$module->hide();
		$modules[] = $module->get_properties();

		$module = new HomeLandingModuleCategory();
		$module->set_module_id(self::MODULE_DOWNLOAD_CATEGORY);
		$module->set_phpboost_module_id(self::MODULE_DOWNLOAD);
		$module->hide();
		$modules[] = $module->get_properties();

		$module = new HomeLandingModule();
		$module->set_module_id(self::MODULE_RECIPE);
		$module->set_phpboost_module_id(self::MODULE_RECIPE);
		$module->hide();
		$modules[] = $module->get_properties();

		$module = new HomeLandingModuleCategory();
		$module->set_module_id(self::MODULE_RECIPE_CATEGORY);
		$module->set_phpboost_module_id(self::MODULE_RECIPE);
		$module->hide();
		$modules[] = $module->get_properties();

		$module = new HomeLandingModule();
		$module->set_module_id(self::MODULE_FLUX);
		$module->set_phpboost_module_id(self::MODULE_FLUX);
		$module->hide();
		$modules[] = $module->get_properties();

		$module = new HomeLandingModule();
		$module->set_module_id(self::MODULE_FORUM);
		$module->set_phpboost_module_id(self::MODULE_FORUM);
		$module->hide();
		$modules[] = $module->get_properties();

		$module = new HomeLandingModule();
		$module->set_module_id(self::MODULE_GALLERY);
		$module->set_phpboost_module_id(self::MODULE_GALLERY);
		$module->hide();
		$modules[] = $module->get_properties();

		$module = new HomeLandingModule();
		$module->set_module_id(self::MODULE_GUESTBOOK);
		$module->set_phpboost_module_id(self::MODULE_GUESTBOOK);
		$module->hide();
		$modules[] = $module->get_properties();

		$module = new HomeLandingModule();
		$module->set_module_id(self::MODULE_MEDIA);
		$module->set_phpboost_module_id(self::MODULE_MEDIA);
		$module->hide();
		$modules[] = $module->get_properties();

		$module = new HomeLandingModule();
		$module->set_module_id(self::MODULE_NEWS);
		$module->set_phpboost_module_id(self::MODULE_NEWS);
		$module->hide();
		$modules[] = $module->get_properties();

		$module = new HomeLandingModuleCategory();
		$module->set_module_id(self::MODULE_NEWS_CATEGORY);
		$module->set_phpboost_module_id(self::MODULE_NEWS);
		$module->hide();
		$modules[] = $module->get_properties();

		$module = new HomeLandingModule();
		$module->set_module_id(self::MODULE_PINNED_NEWS);
		$module->set_phpboost_module_id(self::MODULE_NEWS);
		$module->hide();
		$modules[] = $module->get_properties();

		$module = new HomeLandingModule();
		$module->set_module_id(self::MODULE_RECIPE);
		$module->set_phpboost_module_id(self::MODULE_RECIPE);
		$module->hide();
		$modules[] = $module->get_properties();

		$module = new HomeLandingModuleCategory();
		$module->set_module_id(self::MODULE_RECIPE_CATEGORY);
		$module->set_phpboost_module_id(self::MODULE_RECIPE);
		$module->hide();
		$modules[] = $module->get_properties();

		$module = new HomeLandingModule();
		$module->set_module_id(self::MODULE_SMALLADS);
		$module->set_phpboost_module_id(self::MODULE_SMALLADS);
		$module->hide();
		$modules[] = $module->get_properties();

		$module = new HomeLandingModuleCategory();
		$module->set_module_id(self::MODULE_SMALLADS_CATEGORY);
		$module->set_phpboost_module_id(self::MODULE_SMALLADS);
		$module->hide();
		$modules[] = $module->get_properties();

		$module = new HomeLandingModule();
		$module->set_module_id(self::MODULE_VIDEO);
		$module->set_phpboost_module_id(self::MODULE_VIDEO);
		$module->hide();
		$modules[] = $module->get_properties();

		$module = new HomeLandingModuleCategory();
		$module->set_module_id(self::MODULE_VIDEO_CATEGORY);
		$module->set_phpboost_module_id(self::MODULE_VIDEO);
		$module->hide();
		$modules[] = $module->get_properties();

		$module = new HomeLandingModule();
		$module->set_module_id(self::MODULE_WEB);
		$module->set_phpboost_module_id(self::MODULE_WEB);
		$module->hide();
		$modules[] = $module->get_properties();

		$module = new HomeLandingModuleCategory();
		$module->set_module_id(self::MODULE_WEB_CATEGORY);
		$module->set_phpboost_module_id(self::MODULE_WEB);
		$module->hide();
		$modules[] = $module->get_properties();

		// Files autoload for adding new modules
		$init_directory = new Folder(PATH_TO_ROOT . '/HomeLanding/additional/init/');
		$init_files = $init_directory->get_files();
		foreach ($init_files as $init_file)
		{
			require_once($init_file->get_path());
		}

		return $modules;
	}

	//Default values
	public function get_default_values()
	{
		return array(
			self::MODULE_TITLE => LangLoader::get_message('homelanding.title', 'common', 'HomeLanding'),

			self::LEFT_COLUMNS   => true,
			self::RIGHT_COLUMNS  => true,
			self::TOP_CENTRAL    => true,
			self::BOTTOM_CENTRAL => true,
			self::TOP_FOOTER     => true,

			self::MODULES_LIST => self::init_modules_array(),

			self::ANCHORS_MENU => false,

			self::CAROUSEL        => array(),
			self::CAROUSEL_SPEED  => 200,
			self::CAROUSEL_TIME   => 5000,
			self::CAROUSEL_NUMBER => 4,
			self::CAROUSEL_AUTO   => self::CAROUSEL_TRUE,
			self::CAROUSEL_HOVER  => self::CAROUSEL_TRUE,

			self::EDITO             => LangLoader::get_message('homelanding.edito.description', 'common', 'HomeLanding'),
			self::PINNED_NEWS_TITLE => LangLoader::get_message('homelanding.module.pinned_news', 'common', 'HomeLanding'),

			self::STICKY_TEXT   => LangLoader::get_message('homelanding.sticky.content', 'sticky', 'HomeLanding'),
			self::STICKY_TITLE  => LangLoader::get_message('homelanding.sticky', 'sticky', 'HomeLanding'),
		);
	}

	/**
	 * Returns the configuration.
	 * @return HomeLandingConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'homelanding', 'config');
	}

	/**
	 * Saving the configuration in database.
	 */
	public static function save()
	{
		ConfigManager::save('homelanding', self::load(), 'config');
	}
}
?>
