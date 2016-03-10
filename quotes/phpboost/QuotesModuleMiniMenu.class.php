<?php
/*##################################################
 *                               QuotesModuleMiniMenu.class.php
 *                            -------------------
 *   begin                : February 18, 2016
 *   copyright            : (C) 2016 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
 *
 *
 ###################################################
 *
 * This program is a free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

 /**
 * @author Julien BRISWALTER <j1.seth@phpboost.com>
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
		return !Url::is_current_url('/quotes/') && QuotesAuthorizationsService::check_authorizations()->read();
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
		$authorized_categories = QuotesService::get_authorized_categories(Category::ROOT_CATEGORY);
		
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
