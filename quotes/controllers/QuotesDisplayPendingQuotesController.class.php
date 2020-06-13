<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2019 11 03
 * @since       PHPBoost 5.0 - 2016 02 18
 * @contributor mipel <mipel@phpboost.com>
*/

class QuotesDisplayPendingQuotesController extends ModuleController
{
	private $tpl;
	private $lang;

	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();

		$this->init();

		$this->build_view($request);

		return $this->generate_response($request);
	}

	public function init()
	{
		$this->lang = LangLoader::get('common', 'quotes');
		$this->tpl = new FileTemplate('quotes/QuotesDisplaySeveralQuotesController.tpl');
		$this->tpl->add_lang($this->lang);
	}

	public function build_view(HTTPRequestCustom $request)
	{
		$authorized_categories = CategoriesService::get_authorized_categories(Category::ROOT_CATEGORY);

		$condition = 'WHERE id_category IN :authorized_categories
		' . (!CategoriesAuthorizationsService::check_authorizations()->moderation() ? ' AND author_user_id = :user_id' : '') . '
		AND approved = 0';
		$parameters = array(
			'user_id' => AppContext::get_current_user()->get_id(),
			'authorized_categories' => $authorized_categories
		);

		$page = $request->get_getint('page', 1);
		$pagination = $this->get_pagination($condition, $parameters, $page);

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
			'C_PENDING' => true,
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

	private function get_pagination($condition, $parameters, $page)
	{
		$quotes_number = QuotesService::count($condition, $parameters);

		$pagination = new ModulePagination($page, $quotes_number, (int)QuotesConfig::load()->get_items_number_per_page());
		$pagination->set_url(QuotesUrlBuilder::display_pending('%d'));

		if ($pagination->current_page_is_empty() && $page > 1)
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}

		return $pagination;
	}

	private function check_authorizations()
	{
		if (!(CategoriesAuthorizationsService::check_authorizations()->write() || CategoriesAuthorizationsService::check_authorizations()->contribution() || CategoriesAuthorizationsService::check_authorizations()->moderation()))
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}

	private function generate_response(HTTPRequestCustom $request)
	{
		$page = $request->get_getint('page', 1);
		$response = new SiteDisplayResponse($this->tpl);

		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->lang['quotes.pending'], $this->lang['module_title'], $page);
		$graphical_environment->get_seo_meta_data()->set_description($this->lang['quotes.seo.description.pending'], $page);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(QuotesUrlBuilder::display_pending($page));

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['module_title'], QuotesUrlBuilder::home());
		$breadcrumb->add($this->lang['quotes.pending'], QuotesUrlBuilder::display_pending($page));

		return $response;
	}
}
?>
