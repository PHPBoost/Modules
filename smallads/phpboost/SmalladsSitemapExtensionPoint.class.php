<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2018 08 09
 * @since   	PHPBoost 5.1 - 2018 03 15
*/

class SmalladsSitemapExtensionPoint extends SitemapCategoriesModule
{
	public function __construct()
	{
		parent::__construct(SmalladsService::get_categories_manager());
	}

	protected function get_category_url(Category $category)
	{
		return SmalladsUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name());
	}
}
?>
