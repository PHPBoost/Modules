<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 04 06
 * @since       PHPBoost 5.0 - 2016 02 18
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class QuotesWriterController extends ModuleController
{
	private $lang;
	private $view;

	private $cache;

	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();

		$this->init();

		$this->build_view($request);

		return $this->generate_response($request);
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'quotes');
		$this->view = new FileTemplate('quotes/QuotesSeveralItemsController.tpl');
		$this->view->add_lang($this->lang);
		$this->cache = QuotesCache::load();
	}

	private function build_view(HTTPRequestCustom $request)
	{
		$main_lang = LangLoader::get('main');
		$authorized_categories = $this->get_authorized_categories();
		$rewrited_writer = $request->get_getvalue('writer', '');
		$page = $request->get_getint('page', 1);

		$condition = 'WHERE rewrited_writer = :rewrited_writer AND id_category IN :authorized_categories AND approved = 1';
		$parameters = array(
			'rewrited_writer' => $rewrited_writer,
			'authorized_categories' => $authorized_categories
		);

		$pagination = $this->get_pagination($condition, $parameters, $rewrited_writer, $page);

		$result = PersistenceContext::get_querier()->select('SELECT quotes.*, member.*
		FROM '. QuotesSetup::$quotes_table .' quotes
		LEFT JOIN '. DB_TABLE_MEMBER .' member ON member.user_id = quotes.author_user_id
		' . $condition . '
		ORDER BY quotes.creation_date DESC
		LIMIT :number_items_per_page OFFSET :display_from', array_merge($parameters, array(
			'number_items_per_page' => (int)$pagination->get_number_items_per_page(),
			'display_from' => $pagination->get_display_from()
		)));

		$this->view->put_all(array(
			'C_ITEMS' => $result->get_rows_count() > 0,
			'C_WRITER_ITEMS' => $this->cache->get_writer($rewrited_writer),
			'WRITER_NAME' => $this->cache->get_writer($rewrited_writer),
			'C_PAGINATION' => $pagination->has_several_pages(),
			'PAGINATION' => $pagination->display()
		));

		while ($row = $result->fetch())
		{
			$item = new QuotesItem();
			$item->set_properties($row);

			$this->view->assign_block_vars('items', $item->get_array_tpl_vars());
		}
		$result->dispose();
	}

	private function get_authorized_categories()
	{
		$search_category_children_options = new SearchCategoryChildrensOptions();
		$search_category_children_options->add_authorizations_bits(Category::READ_AUTHORIZATIONS);
		$categories = CategoriesService::get_categories_manager()->get_children(Category::ROOT_CATEGORY, $search_category_children_options);

		return array_keys($categories);
	}

	private function get_pagination($condition, $parameters, $rewrited_writer, $page)
	{
		$items_number = QuotesService::count($condition, $parameters);

		$pagination = new ModulePagination($page, $items_number, (int)QuotesConfig::load()->get_items_per_page());
		$pagination->set_url(QuotesUrlBuilder::display_writer_items($rewrited_writer, '%d'));

		if ($pagination->current_page_is_empty() && $page > 1)
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}

		return $pagination;
	}

	private function check_authorizations()
	{
		if (!CategoriesAuthorizationsService::check_authorizations()->read())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}

	private function generate_response(HTTPRequestCustom $request)
	{
		$rewrited_writer = $request->get_getvalue('writer', '');
		$page = $request->get_getint('page', 1);

		$response = new SiteDisplayResponse($this->view);

		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->lang['module.title'], $this->cache->get_writer($rewrited_writer), $page);
		$graphical_environment->get_seo_meta_data()->set_description(StringVars::replace_vars($this->lang['quotes.seo.description.writer'], array('writer' => $this->cache->get_writer($rewrited_writer))));
		$graphical_environment->get_seo_meta_data()->set_canonical_url(QuotesUrlBuilder::display_writer_items($rewrited_writer, $page));

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['module.title'], QuotesUrlBuilder::home());
		$breadcrumb->add($this->cache->get_writer($rewrited_writer), QuotesUrlBuilder::display_writer_items($rewrited_writer, $page));

		return $response;
	}
}
?>
