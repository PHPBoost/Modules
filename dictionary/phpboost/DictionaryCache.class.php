<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 06 16
 * @since       PHPBoost 4.1 - 2016 02 15
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class DictionaryCache implements CacheData
{
	private $dictionary_words = array();

	/**
	 * {@inheritdoc}
	 */
	public function synchronize()
	{
		$this->dictionary_words = array();

		$result = PersistenceContext::get_querier()->select('
			SELECT id, word, cat, description, approved
			FROM ' . DictionarySetup::$dictionary_table . '
			WHERE approved = 1
			ORDER BY RAND()
			LIMIT 20'
		);

		while ($row = $result->fetch())
		{
			$this->dictionary_words[$row['id']] = $row;
		}
		$result->dispose();
	}

	public function get_dictionary_words()
	{
		return $this->dictionary_words;
	}

	public function dictionary_word_exists($id)
	{
		return array_key_exists($id, $this->dictionary_words);
	}

	public function get_dictionary_word($id)
	{
		if ($this->dictionary_word_exists($id))
		{
			return $this->dictionary_words[$id];
		}
		return null;
	}

	/**
	 * Loads and returns the dictionary cached data.
	 * @return DictionaryCache The cached data
	 */
	public static function load()
	{
		return CacheManager::load(__CLASS__, 'dictionary', 'minimenu');
	}

	/**
	 * Invalidates the current dictionary cached data.
	 */
	public static function invalidate()
	{
		CacheManager::invalidate('dictionary', 'minimenu');
	}
}
?>
