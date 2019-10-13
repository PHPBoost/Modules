<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version   	PHPBoost 5.3 - last update: 2019 10 13
 * @since   	PHPBoost 5.2 - 2018 11 27
*/

class NewscatConfig extends AbstractConfigData
{
	const ONLY_NEWS_MODULE = 'only_news_module';
	const MODULE_NAME = 'module_name';

	public function get_only_news_module()
	{
		return $this->get_property(self::ONLY_NEWS_MODULE);
	}

	public function set_only_news_module($only_news_module)
	{
		$this->set_property(self::ONLY_NEWS_MODULE, $only_news_module);
	}

	public function get_module_name()
	{
		return $this->get_property(self::MODULE_NAME);
	}

	public function set_module_name($module_name)
	{
		$this->set_property(self::MODULE_NAME, $module_name);
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_default_values()
	{
		return array(
			self::ONLY_NEWS_MODULE => false,
			self::MODULE_NAME => LangLoader::get_message('newscat.module.title', 'common', 'newscat'),
		);
	}

	/**
	 * Returns the configuration.
	 * @return GoogleMapsConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'newscat', 'config');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('newscat', self::load(), 'config');
	}
}
?>
