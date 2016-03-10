<?php
/*##################################################
 *                               QuotesCache.class.php
 *                            -------------------
 *   begin                : February 18, 2016
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

class QuotesCache implements CacheData
{
	private $quotes = array();
	private $categories = array();
	private $authors = array();
	
	/**
	 * {@inheritdoc}
	 */
	public function synchronize()
	{
		$this->quotes = array();
		
		$result = PersistenceContext::get_querier()->select('SELECT id, id_category, author, rewrited_author, quote
			FROM ' . QuotesSetup::$quotes_table . '
			WHERE approved = 1
			ORDER BY RAND()
			LIMIT 50'
		);
		
		while ($row = $result->fetch())
		{
			$this->categories[] = $row['id_category'];
			
			$this->quotes[$row['id_category']][] = array(
				'id' => $row['id'],
				'author' => $row['author'],
				'rewrited_author' => $row['rewrited_author'],
				'quote' => $row['quote']
			);
		}
		$result->dispose();
		
		$result = PersistenceContext::get_querier()->select('SELECT author, rewrited_author
			FROM ' . QuotesSetup::$quotes_table . '
			GROUP BY rewrited_author'
		);
		
		while ($row = $result->fetch())
		{
			$this->authors[$row['rewrited_author']] = $row['author'];
		}
		$result->dispose();
	}
	
	public function get_quotes()
	{
		return $this->quotes;
	}
	
	public function quotes_exists($id)
	{
		return array_key_exists($id, $this->quotes);
	}
	
	public function get_quotes_item($id)
	{
		if ($this->quotes_exists($id))
		{
			return $this->quotes[$id];
		}
		return null;
	}
	
	public function get_number_quotes()
	{
		return count($this->quotes);
	}
	
	public function get_category_quotes($id_category)
	{
		return $this->quotes[$id_category];
	}
	
	public function get_categories()
	{
		return $this->categories;
	}
	
	public function get_authors()
	{
		return $this->authors;
	}
	
	public function author_exists($rewrited_name)
	{
		return array_key_exists($rewrited_name, $this->authors);
	}
	
	public function get_author($rewrited_name)
	{
		if ($this->author_exists($rewrited_name))
		{
			return $this->authors[$rewrited_name];
		}
		return null;
	}
	
	/**
	 * Loads and returns the quotes cached data.
	 * @return QuotesCache The cached data
	 */
	public static function load()
	{
		return CacheManager::load(__CLASS__, 'module', 'quotes');
	}
	
	/**
	 * Invalidates the current quotes cached data.
	 */
	public static function invalidate()
	{
		CacheManager::invalidate('module', 'quotes');
	}
}
?>
