<?php
/**
 * @copyright   &copy; 2005-2021 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 16
 * @since       PHPBoost 6.0 - 2020 12 20
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class QuotesMemberItemsController extends DefaultModuleController
{
	private $member;

	protected function get_template_to_use()
	{
	   return new FileTemplate('quotes/QuotesSeveralItemsController.tpl');
	}

	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();

		$this->build_view($request);

		return $this->generate_response($request);
	}

	public function build_view(HTTPRequestCustom $request)
	{
		$authorized_categories = CategoriesService::get_authorized_categories(Category::ROOT_CATEGORY);

		$condition = 'WHERE id_category IN :authorized_categories
		AND author_user_id = :user_id
		AND approved = 1';
		$parameters = array(
			'user_id' => $this->get_member()->get_id(),
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

		$this->view->put_all(array(
			'C_ITEMS'        => $result->get_rows_count() > 0,
			'C_MEMBER_ITEMS' => true,
			'C_MY_ITEMS'     => $this->is_current_member_displayed(),
			'C_PAGINATION'   => $pagination->has_several_pages(),

			'PAGINATION'     => $pagination->display(),
			'MEMBER_NAME'    => $this->get_member()->get_display_name()
		));

		while ($row = $result->fetch())
		{
			$item = new QuotesItem();
			$item->set_properties($row);

			$this->view->assign_block_vars('items', $item->get_template_vars());
		}
		$result->dispose();
	}

	protected function get_member()
	{
		if ($this->member === null)
		{
			$this->member = UserService::get_user(AppContext::get_request()->get_getint('user_id', AppContext::get_current_user()->get_id()));
			if (!$this->member)
				DispatchManager::redirect(PHPBoostErrors::unexisting_element());
		}
		return $this->member;
	}

	protected function is_current_member_displayed()
	{
		return $this->member && $this->member->get_id() == AppContext::get_current_user()->get_id();
	}

	private function get_pagination($condition, $parameters, $page)
	{
		$items_number = QuotesService::count($condition, $parameters);

		$pagination = new ModulePagination($page, $items_number, (int)QuotesConfig::load()->get_items_per_page());
		$pagination->set_url(QuotesUrlBuilder::display_member_items($this->get_member()->get_id(), '%d'));

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
		$page_title = $this->is_current_member_displayed() ? $this->lang['quotes.my.items'] : $this->lang['quotes.member.items'] . ' ' . $this->get_member()->get_display_name();
		$response = new SiteDisplayResponse($this->view);

		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($page_title, $this->lang['quotes.module.title'], $page);
		$graphical_environment->get_seo_meta_data()->set_description(StringVars::replace_vars($this->lang['quotes.seo.description.member'], array('author' => AppContext::get_current_user()->get_display_name())), $page);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(QuotesUrlBuilder::display_member_items($this->get_member()->get_id(), $page));

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['quotes.module.title'], QuotesUrlBuilder::home());
		$breadcrumb->add($page_title, QuotesUrlBuilder::display_member_items($this->get_member()->get_id(), $page));

		return $response;
	}
}
?>
