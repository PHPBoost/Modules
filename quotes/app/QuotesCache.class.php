<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 15
 * @since       PHPBoost 5.0 - 2016 02 18
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @author      mipel <mipel@phpboost.com>
*/

class QuotesCache implements CacheData
{
	private array $items = [];
	private array $categories = [];
	private array $writers = [];

	public function synchronize(): void
    {
		$this->items = [];

		$result = PersistenceContext::get_querier()->select('
            SELECT id, id_category, writer, rewrited_writer, content
			FROM ' . QuotesSetup::$quotes_table . '
			WHERE approved = 1
			ORDER BY RAND()
			LIMIT 50'
		);

		while ($row = $result->fetch())
		{
			$this->categories[] = $row['id_category'];

			$this->items[$row['id_category']][] = [
				'id' => $row['id'],
				'writer' => $row['writer'],
				'rewrited_writer' => $row['rewrited_writer'],
				'content' => $row['content']
			];
		}
		$result->dispose();

		$result = PersistenceContext::get_querier()->select('
            SELECT writer, rewrited_writer
			FROM ' . QuotesSetup::$quotes_table . '
			GROUP BY writer, rewrited_writer'
		);

		while ($row = $result->fetch())
		{
			$this->writers[$row['rewrited_writer']] = $row['writer'];
		}
		$result->dispose();
	}

	public function get_items(): array
	{
		return $this->items;
	}

	public function item_exists(int $id): bool
	{
		return array_key_exists($id, $this->items);
	}

	public function get_item(int $id): mixed
	{
		if ($this->item_exists($id))
		{
			return $this->items[$id];
		}
		return null;
	}

	public function get_items_number(): int
	{
		return count($this->items);
	}

	public function get_items_category(int $id_category): mixed
	{
		return $this->items[$id_category];
	}

	public function get_categories(): array
	{
		return $this->categories;
	}

	public function get_writers(): array
	{
		return $this->writers;
	}

	public function writer_exists(string $rewrited_name): bool
	{
		return array_key_exists($rewrited_name, $this->writers);
	}

	public function get_writer(string $rewrited_name): mixed
	{
		if ($this->writer_exists($rewrited_name))
		{
			return $this->writers[$rewrited_name];
		}
		return null;
	}

	/** Loads and returns the items cached data. */
	public static function load(): QuotesCache
	{
		return CacheManager::load(self::class, 'module', 'quotes');
	}

	/** Invalidates the current items cached data. */
	public static function invalidate(): void
	{
		CacheManager::invalidate('module', 'quotes');
	}
}
?>
