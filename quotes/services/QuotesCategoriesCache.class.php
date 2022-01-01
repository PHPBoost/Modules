<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2019 11 03
 * @since       PHPBoost 5.0 - 2016 02 18
 * @contributor mipel <mipel@phpboost.com>
*/

class QuotesCategoriesCache extends CategoriesCache
{
	public function get_table_name()
	{
		return QuotesSetup::$quotes_cats_table;
	}

	public function get_table_name_containing_items()
	{
		return QuotesSetup::$quotes_table;
	}

	public function get_category_class()
	{
		return CategoriesManager::RICH_CATEGORY_CLASS;
	}

	public function get_module_identifier()
	{
		return 'quotes';
	}

	protected function get_category_elements_number($id_category)
	{
		$now = new Date();
		return QuotesService::count('WHERE id_category = :id_category AND approved = 1',
			array(
				'id_category' => $id_category
			)
		);
	}

	public function get_root_category()
	{
		$root = new RichRootCategory();
		$root->set_authorizations(QuotesConfig::load()->get_authorizations());
		$description = QuotesConfig::load()->get_root_category_description();
		if (empty($description))
			$description = StringVars::replace_vars(LangLoader::get_message('quotes.seo.description.root', 'common', 'quotes'), array('site' => GeneralConfig::load()->get_site_name()));
		$root->set_description($description);
		return $root;
	}
}
?>
