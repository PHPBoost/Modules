<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2016 02 17
 * @since       PHPBoost 4.1 - 2016 02 15
*/

class DictionaryConfig extends AbstractConfigData
{
	const ITEMS_NUMBER_PER_PAGE = 'items_number_per_page';
	const FORBIDDEN_TAGS = 'forbidden_tags';
	const AUTHORIZATIONS = 'authorizations';

	public function get_items_number_per_page()
	{
		return $this->get_property(self::ITEMS_NUMBER_PER_PAGE);
	}

	public function set_items_number_per_page($value)
	{
		$this->set_property(self::ITEMS_NUMBER_PER_PAGE, $value);
	}

	public function get_forbidden_tags()
	{
		return $this->get_property(self::FORBIDDEN_TAGS);
	}

	public function set_forbidden_tags(Array $array)
	{
		$this->set_property(self::FORBIDDEN_TAGS, $array);
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
			self::ITEMS_NUMBER_PER_PAGE => 15,
			self::FORBIDDEN_TAGS => array('swf', 'movie', 'sound', 'code', 'math', 'mail', 'html', 'feed', 'youtube'),
			self::AUTHORIZATIONS => array('r-1' => 1, 'r0' => 5, 'r1' => 13)
		);
	}

	/**
	 * Returns the configuration.
	 * @return DictionaryConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'dictionary', 'config');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('dictionary', self::load(), 'config');
	}
}
?>
