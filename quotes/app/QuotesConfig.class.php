<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 11
 * @since       PHPBoost 5.0 - 2016 02 18
 * @author      mipel <mipel@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class QuotesConfig extends AbstractConfigData
{
	const ITEMS_PER_PAGE      = 'items_per_page';
	const CATEGORIES_PER_PAGE = 'categories_per_page';
	const CATEGORIES_PER_ROW  = 'categories_per_row';
	const ROOT_CATEGORY_DESCRIPTION  = 'root_category_description';
	const AUTHORIZATIONS             = 'authorizations';

	public function get_items_per_page(): int
	{
		return $this->get_property(self::ITEMS_PER_PAGE);
	}

	public function set_items_per_page(int $value): void
	{
		$this->set_property(self::ITEMS_PER_PAGE, $value);
	}

	public function get_categories_per_page(): int
	{
		return $this->get_property(self::CATEGORIES_PER_PAGE);
	}

	public function set_categories_per_page(int $value): void
	{
		$this->set_property(self::CATEGORIES_PER_PAGE, $value);
	}

	public function get_categories_per_row(): int
	{
		return $this->get_property(self::CATEGORIES_PER_ROW);
	}

	public function set_categories_per_row(int $value): void
	{
		$this->set_property(self::CATEGORIES_PER_ROW, $value);
	}

	public function get_root_category_description(): string
	{
		return $this->get_property(self::ROOT_CATEGORY_DESCRIPTION);
	}

	public function set_root_category_description(string $value): void
	{
		$this->set_property(self::ROOT_CATEGORY_DESCRIPTION, $value);
	}

	public function get_authorizations(): array
	{
		return $this->get_property(self::AUTHORIZATIONS);
	}

	public function set_authorizations(array $authorizations): void
	{
		$this->set_property(self::AUTHORIZATIONS, $authorizations);
	}

	public function get_default_values(): array
	{
		return [
			self::ITEMS_PER_PAGE => 15,
			self::CATEGORIES_PER_PAGE => 10,
			self::CATEGORIES_PER_ROW => 3,
			self::ROOT_CATEGORY_DESCRIPTION => CategoriesService::get_default_root_category_description('quotes', 0, 5),
			self::AUTHORIZATIONS => ['r-1' => 1, 'r0' => 5, 'r1' => 13]
		];
	}

	public static function load(): QuotesConfig
	{
		return ConfigManager::load(self::class, 'quotes', 'config');
	}

	public static function save(): void
	{
		ConfigManager::save('quotes', self::load(), 'config');
	}
}
?>
