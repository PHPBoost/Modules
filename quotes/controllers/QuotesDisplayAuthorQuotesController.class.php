<?php
/*##################################################
 *                               QuotesDisplayAuthorQuotesController.class.php
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

class QuotesDisplayAuthorQuotesController extends ModuleController
{
	private $lang;
	private $tpl;
	
	private $cache;
	
	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();
		
		$this->init();
		
		$this->build_view($request);
		
		return $this->generate_response();
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('common', 'quotes');
		$this->tpl = new FileTemplate('quotes/QuotesDisplaySeveralQuotesController.tpl');
		$this->tpl->add_lang($this->lang);
		$this->cache = QuotesCache::load();
	}
	
	private function build_view(HTTPRequestCustom $request)
	{
		$main_lang = LangLoader::get('main');
		$authorized_categories = $this->get_authorized_categories();
		$rewrited_author = $request->get_getvalue('author', '');
		$page = $request->get_getint('page', 1);
		
		$condition = 'WHERE rewrited_author = :rewrited_author AND id_category IN :authorized_categories AND approved = 1';
		$parameters = array(
			'rewrited_author' => $rewrited_author,
			'authorized_categories' => $authorized_categories
		);
		
		$pagination = $this->get_pagination($condition, $parameters, $rewrited_author, $page);
		
		$result = PersistenceContext::get_querier()->select('SELECT quotes.*, member.*
		FROM '. QuotesSetup::$quotes_table .' quotes
		LEFT JOIN '. DB_TABLE_MEMBER .' member ON member.user_id = quotes.author_user_id
		' . $condition . '
		ORDER BY quotes.creation_date DESC
		LIMIT :number_items_per_page OFFSET :display_from', array_merge($parameters, array(
			'number_items_per_page' => (int)$pagination->get_number_items_per_page(),
			'display_from' => $pagination->get_display_from()
		)));
		
		$this->tpl->put_all(array(
			'C_RESULTS' => $result->get_rows_count() > 0,
			'C_MORE_THAN_ONE_RESULT' => $result->get_rows_count() > 1,
			'C_AUTHOR_NAME' => $this->cache->get_author($rewrited_author),
			'AUTHOR_NAME' => $this->cache->get_author($rewrited_author),
			'C_PAGINATION' => $pagination->has_several_pages(),
			'PAGINATION' => $pagination->display()
		));
		
		while ($row = $result->fetch())
		{
			$quote = new Quote();
			$quote->set_properties($row);
			
			$this->tpl->assign_block_vars('quotes', $quote->get_array_tpl_vars());
		}
		$result->dispose();
	}
	
	private function get_authorized_categories()
	{
		$search_category_children_options = new SearchCategoryChildrensOptions();
		$search_category_children_options->add_authorizations_bits(Category::READ_AUTHORIZATIONS);
		$categories = QuotesService::get_categories_manager()->get_children(Category::ROOT_CATEGORY, $search_category_children_options);
		
		return array_keys($categories);
	}
	
	private function get_pagination($condition, $parameters, $rewrited_author, $page)
	{
		$quotes_number = QuotesService::count($condition, $parameters);
		
		$pagination = new ModulePagination($page, $quotes_number, (int)QuotesConfig::load()->get_items_number_per_page());
		$pagination->set_url(QuotesUrlBuilder::display_author_quotes($rewrited_author, '%d'));
		
		if ($pagination->current_page_is_empty() && $page > 1)
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}
		
		return $pagination;
	}
	
	private function check_authorizations()
	{
		if (!QuotesAuthorizationsService::check_authorizations()->read())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}
	
	private function generate_response()
	{
		$rewrited_author = AppContext::get_request()->get_getvalue('author', '');
		$page = AppContext::get_request()->get_getint('page', 1);
		
		$response = new SiteDisplayResponse($this->tpl);
		
		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->lang['module_title']);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(QuotesUrlBuilder::display_author_quotes($rewrited_author, $page));
		
		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['module_title'], QuotesUrlBuilder::home());
		$breadcrumb->add($this->cache->get_author($rewrited_author), QuotesUrlBuilder::display_author_quotes($rewrited_author, $page));
		
		return $response;
	}
}
?>
