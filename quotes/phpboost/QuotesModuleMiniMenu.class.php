<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2019 11 03
 * @since   	PHPBoost 5.0 - 2016 02 18
 * @contributor mipel <mipel@phpboost.com>
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
		return LangLoader::get_message('module_title', 'common', 'quotes');
	}

	public function is_displayed()
	{
		return !Url::is_current_url('/quotes/') && CategoriesAuthorizationsService::check_authorizations(Category::ROOT_CATEGORY, 'quotes')->read();
	}

	public function get_menu_content()
	{
		//Create file template
		$tpl = new FileTemplate('quotes/QuotesModuleMiniMenu.tpl');

		//Assign the lang file to the tpl
		$tpl->add_lang(LangLoader::get('common', 'quotes'));

		//Load module cache
		$quotes_cache = QuotesCache::load();

		//Get authorized categories for the current user
		$authorized_categories = CategoriesService::get_authorized_categories(Category::ROOT_CATEGORY, true, 'quotes');

		$categories = array_intersect($quotes_cache->get_categories(), $authorized_categories);

		if (!empty($categories))
		{
			$id_category = $categories[array_rand($categories)];
			$category_quotes = $quotes_cache->get_category_quotes($id_category);
			$random_quote = $category_quotes[array_rand($category_quotes)];

			if (!empty($random_quote))
			{
				$tpl->put_all(array(
					'C_QUOTE' => $random_quote,
					'QUOTE' => strip_tags(FormatingHelper::second_parse($random_quote['quote'])),
					'AUTHOR' => $random_quote['author'],
					'U_AUTHOR_LINK' => QuotesUrlBuilder::display_author_quotes($random_quote['rewrited_author'])->rel()
				));
			}
		}

		$tpl->put('U_MODULE_HOME_PAGE', QuotesUrlBuilder::home()->rel());

		return $tpl->render();
	}
}
?>
