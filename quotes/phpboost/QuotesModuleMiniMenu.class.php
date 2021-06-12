<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 06 12
 * @since       PHPBoost 5.0 - 2016 02 18
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class QuotesModuleMiniMenu extends ModuleMiniMenu
{
	public function get_default_block()
	{
		return self::BLOCK_POSITION__NOT_ENABLED;
	}

	public function get_menu_id()
	{
		return 'module-mini-quotes';
	}

	public function get_menu_title()
	{
		return LangLoader::get_message('quotes.module.title', 'common', 'quotes');
	}

	public function is_displayed()
	{
		return !Url::is_current_url('/quotes/') && CategoriesAuthorizationsService::check_authorizations(Category::ROOT_CATEGORY, 'quotes')->read();
	}

	public function get_menu_content()
	{
		//Create file template
		$view = new FileTemplate('quotes/QuotesModuleMiniMenu.tpl');

		//Assign the lang file to the tpl
		$view->add_lang(array_merge(LangLoader::get('common', 'quotes'), LangLoader::get('common-lang')));

		//Load module cache
		$quotes_cache = QuotesCache::load();

		//Get authorized categories for the current user
		$authorized_categories = CategoriesService::get_authorized_categories(Category::ROOT_CATEGORY, true, 'quotes');

		$categories = array_intersect($quotes_cache->get_categories(), $authorized_categories);

		if (!empty($categories))
		{
			$id_category = $categories[array_rand($categories)];
			$category_items = $quotes_cache->get_items_category($id_category);
			$random_item = $category_items[array_rand($category_items)];

			if (!empty($random_item))
			{
				$view->put_all(array(
					'C_ITEMS'     => $random_item,
					'CONTENT'     => strip_tags(FormatingHelper::second_parse($random_item['content'])),
					'WRITER_NAME' => $random_item['writer'],
					'U_WRITER'    => QuotesUrlBuilder::display_writer_items($random_item['rewrited_writer'])->rel()
				));
			}
		}

		$view->put('U_MODULE_HOME_PAGE', QuotesUrlBuilder::home()->rel());

		return $view->render();
	}
}
?>
