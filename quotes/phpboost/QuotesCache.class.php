<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 12 19
 * @since       PHPBoost 5.0 - 2016 02 18
 * @contributor mipel <mipel@phpboost.com>
*/

class QuotesCache implements CacheData
{
	private $items = array();
	private $categories = array();
	private $writers = array();

	/**
	 * {@inheritdoc}
	 */
	public function synchronize()
	{
		$this->items = array();

		$result = PersistenceContext::get_querier()->select('SELECT id, id_category, writer, rewrited_writer, content
			FROM ' . QuotesSetup::$quotes_table . '
			WHERE approved = 1
			ORDER BY RAND()
			LIMIT 50'
		);

		while ($row = $result->fetch())
		{
			$this->categories[] = $row['id_category'];

			$this->items[$row['id_category']][] = array(
				'id' => $row['id'],
				'writer' => $row['writer'],
				'rewrited_writer' => $row['rewrited_writer'],
				'content' => $row['content']
			);
		}
		$result->dispose();

		$result = PersistenceContext::get_querier()->select('SELECT writer, rewrited_writer
			FROM ' . QuotesSetup::$quotes_table . '
			GROUP BY writer, rewrited_writer'
		);

		while ($row = $result->fetch())
		{
			$this->writers[$row['rewrited_writer']] = $row['writer'];
		}
		$result->dispose();
	}

	public function get_items()
	{
		return $this->items;
	}

	public function item_exists($id)
	{
		return array_key_exists($id, $this->items);
	}

	public function get_item($id)
	{
		if ($this->item_exists($id))
		{
			return $this->items[$id];
		}
		return null;
	}

	public function get_items_number()
	{
		return count($this->items);
	}

	public function get_items_category($id_category)
	{
		return $this->items[$id_category];
	}

	public function get_categories()
	{
		return $this->categories;
	}

	public function get_writers()
	{
		return $this->writers;
	}

	public function writer_exists($rewrited_name)
	{
		return array_key_exists($rewrited_name, $this->writers);
	}

	public function get_writer($rewrited_name)
	{
		if ($this->writer_exists($rewrited_name))
		{
			return $this->writers[$rewrited_name];
		}
		return null;
	}

	/**
	 * Loads and returns the items cached data.
	 * @return QuotesCache The cached data
	 */
	public static function load()
	{
		return CacheManager::load(__CLASS__, 'module', 'quotes');
	}

	/**
	 * Invalidates the current items cached data.
	 */
	public static function invalidate()
	{
		CacheManager::invalidate('module', 'quotes');
	}
}
?>
