<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2018 12 24
 * @since       PHPBoost 5.0 - 2016 02 18
 * @contributor mipel <mipel@phpboost.com>
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
			GROUP BY author, rewrited_author'
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
