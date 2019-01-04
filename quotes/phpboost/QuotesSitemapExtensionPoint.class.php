<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2018 12 24
 * @since   	PHPBoost 5.0 - 2016 02 18
 * @contributor mipel <mipel@phpboost.com>
*/

class QuotesSitemapExtensionPoint extends SitemapCategoriesModule
{
	public function __construct()
	{
		parent::__construct(QuotesService::get_categories_manager());
	}

	protected function get_category_url(Category $category)
	{
		return QuotesUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name());
	}
}
?>
