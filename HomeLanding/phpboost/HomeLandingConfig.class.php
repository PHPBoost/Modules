<?php
/*##################################################
 *		             HomeLandingConfig.class.php
 *                            -------------------
 *   begin                : January 2, 2016
 *   copyright            : (C) 2016 Sebastien Lartigue
 *   email                : babso@web33.fr
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
	const CAROUSEL_NAV = 'carousel_nav';
	const CAROUSEL_HOVER = 'carousel_hover';
	const CAROUSEL_TRUE = 'true';
	const CAROUSEL_FALSE = 'false';
	const CAROUSEL_MINI = 'carousel_mini';
	const CAROUSEL_DOT = 'dot';
	const CAROUSEL_IMG = 'img';

	const ONEPAGE_MENU = 'onepage_menu';
	const EDITO = 'edito';

	const RSS_SITE_NAME = 'rss_site_name';
	const RSS_SITE_URL = 'rss_site_url';
	const RSS_XML_URL = 'rss_xml_url';

	const ELEMENTS_NUMBER_DISPLAYED = 5;
	const CHARACTERS_NUMBER_DISPLAYED = 128;
	const MODULE_CAROUSEL = 'carousel';
	const MODULE_ONEPAGE_MENU = 'onepage_menu';
	const MODULE_EDITO = 'edito';
	const MODULE_LASTCOMS = 'lastcoms';
	const MODULE_ARTICLES = 'articles';
	const MODULE_ARTICLES_CATEGORY = 'articles_category';
	const MODULE_CALENDAR = 'calendar';
	const MODULE_CONTACT = 'contact';
	const MODULE_DOWNLOAD = 'download';
	const MODULE_DOWNLOAD_CATEGORY = 'download_category';
	const MODULE_FORUM = 'forum';
	const MODULE_GALLERY = 'gallery';
	const MODULE_GUESTBOOK = 'guestbook';
	const MODULE_MEDIA = 'media';
	const MODULE_NEWS = 'news';
	const MODULE_NEWS_CATEGORY = 'news_category';
	const MODULE_RSS = 'rss';
	const MODULE_WEB = 'web';
	const MODULE_WEB_CATEGORY = 'web_category';

	const STICKY_TEXT = 'sticky_text';
	const STICKY_TITLE = 'sticky_title';

	//Config

	//Module Title
	public function get_module_title()
	{
		return $this->get_property(self::MODULE_TITLE);
	}

	public function set_module_title($module_title)
	{
		$this->set_property(self::MODULE_TITLE, $module_title);
	}

	//Menus Management
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

	// One page menu
	public function get_onepage_menu()
	{
		return $this->get_property(self::ONEPAGE_MENU);
	}

	public function set_onepage_menu($onepage_menu)
	{
		$this->set_property(self::ONEPAGE_MENU, $onepage_menu);
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

	public function get_carousel_nav()
	{
		return $this->get_property(self::CAROUSEL_NAV);
	}

	public function set_carousel_nav($nav)
	{
		$this->set_property(self::CAROUSEL_NAV, $nav);
	}

	public function get_carousel_hover()
	{
		return $this->get_property(self::CAROUSEL_HOVER);
	}

	public function set_carousel_hover($hover)
	{
		$this->set_property(self::CAROUSEL_HOVER, $hover);
	}

	public function get_carousel_mini()
	{
		return $this->get_property(self::CAROUSEL_MINI);
	}

	public function set_carousel_mini($mini)
	{
		$this->set_property(self::CAROUSEL_MINI, $mini);
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


	//External Rss
	public function get_rss_site_name()
	{
		return $this->get_property(self::RSS_SITE_NAME);
	}

	public function set_rss_site_name($site_name)
	{
		$this->set_property(self::RSS_SITE_NAME, $site_name);
	}

	public function get_rss_site_url()
	{
		return $this->get_property(self::RSS_SITE_URL);
	}

	public function set_rss_site_url($site_url)
	{
		$this->set_property(self::RSS_SITE_URL, $site_url);
	}

	public function get_rss_xml_url()
	{
		return $this->get_property(self::RSS_XML_URL);
	}

	public function set_rss_xml_url($xml_url)
	{
		$this->set_property(self::RSS_XML_URL, $xml_url);
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

		$lang = LangLoader::get('config', 'HomeLanding');

		$module = new HomeLandingModule();
		$module->set_module_id(self::MODULE_ONEPAGE_MENU);
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
		$module->set_module_id(self::MODULE_RSS);
		$module->set_elements_number_displayed(10);
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

		return $modules;
	}

	//Default values
	public function get_default_values()
	{
		return array(
			self::MODULE_TITLE => LangLoader::get_message('title', 'config', 'HomeLanding'),

			self::LEFT_COLUMNS => true,
			self::RIGHT_COLUMNS => true,
			self::TOP_CENTRAL => true,
			self::BOTTOM_CENTRAL => true,
			self::TOP_FOOTER => true,

			self::MODULES_LIST => self::init_modules_array(),

			self::ONEPAGE_MENU => false,

			self::CAROUSEL => array(),
			self::CAROUSEL_SPEED => 200,
			self::CAROUSEL_TIME => 5000,
			self::CAROUSEL_NAV => 1,
			self::CAROUSEL_HOVER => 1,
			self::CAROUSEL_MINI => self::CAROUSEL_DOT,

			self::EDITO => LangLoader::get_message('module.edito.description', 'config', 'HomeLanding'),

			self::RSS_SITE_NAME => '',
			self::RSS_SITE_URL => '',
			self::RSS_XML_URL => '',
			self::STICKY_TEXT => LangLoader::get_message('homelanding.sticky.content', 'sticky', 'HomeLanding'),
			self::STICKY_TITLE => LangLoader::get_message('homelanding.sticky', 'sticky', 'HomeLanding'),
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
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('homelanding', self::load(), 'config');
	}
}
?>
