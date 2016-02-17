<?php
/*##################################################
 *                               DictionaryCache.class.php
 *                            -------------------
 *   begin                : February 15, 2016
 *   copyright            : (C) 2016 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
 *
 *
 ###################################################
 *
 * This program is a free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

 /**
 * @author Julien BRISWALTER <j1.seth@phpboost.com>
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
			SELECT id, word, cat, description
			FROM ' . DictionarySetup::$dictionary_table . '
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
