<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 06
 * @since       PHPBoost 5.0 - 2016 02 18
 * @author      mipel <mipel@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class QuotesCategoriesCache extends CategoriesCache
{
	public function get_table_name(): string
	{
		return QuotesSetup::$quotes_cats_table;
	}

	public function get_table_name_containing_items(): string
	{
		return QuotesSetup::$quotes_table;
	}

	public function get_category_class(): mixed
	{
		return CategoriesManager::RICH_CATEGORY_CLASS;
	}

	public function get_module_identifier(): string
	{
		return 'quotes';
	}

	protected function get_category_elements_number(int $id_category): int
	{
		return QuotesService::count('WHERE id_category = :id_category AND approved = 1',
			[
				'id_category' => $id_category
			]
		);
	}

	public function get_root_category(): RichRootCategory
	{
		$root = new RichRootCategory();
		$root->set_authorizations(QuotesConfig::load()->get_authorizations());
		$description = QuotesConfig::load()->get_root_category_description();
		if (empty($description))
        {
			$description = StringVars::replace_vars(LangLoader::get_message('quotes.seo.description.root', 'common', 'quotes'), ['site' => GeneralConfig::load()->get_site_name()]);
        }
		$root->set_description($description);
		return $root;
	}
}
?>
