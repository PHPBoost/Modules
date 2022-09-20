<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 09 20
 * @since       PHPBoost 6.0 - 2021 08 22
*/

class SpotsPendingItemsController extends DefaultModuleController
{

	protected function get_template_to_use()
	{
		return new FileTemplate('spots/SpotsSeveralItemsController.tpl');
	}

	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();

		$this->build_view($request);

		return $this->generate_response();
	}

	public function build_view(HTTPRequestCustom $request)
	{
		$now = new Date();
		$config = SpotsConfig::load();
		$authorized_categories = CategoriesService::get_authorized_categories(Category::ROOT_CATEGORY);

		$condition = 'WHERE id_category IN :authorized_categories
		' . (!CategoriesAuthorizationsService::check_authorizations()->moderation() ? ' AND author_user_id = :user_id' : '') . '
		AND published = 0';
		$parameters = array(
			'user_id' => AppContext::get_current_user()->get_id(),
			'authorized_categories' => $authorized_categories,
			'timestamp_now' => $now->get_timestamp()
		);

		$page = AppContext::get_request()->get_getint('page', 1);
		$pagination = $this->get_pagination($condition, $parameters, $page);

		$result = PersistenceContext::get_querier()->select('SELECT spots.*, member.*
		FROM '. SpotsSetup::$spots_table .' spots
		LEFT JOIN '. DB_TABLE_MEMBER .' member ON member.user_id = spots.author_user_id
		' . $condition . '
		ORDER BY spots.creation_date DESC
		LIMIT :number_items_per_page OFFSET :display_from', array_merge($parameters, array(
			'number_items_per_page' => $pagination->get_number_items_per_page(),
			'display_from' => $pagination->get_display_from()
		)));

		$number_columns_display_per_line = $config->get_items_per_row();

		$this->view->put_all(array(
			'C_ITEMS'         => $result->get_rows_count() > 0,
			'C_SEVERAL_ITEMS' => $result->get_rows_count() > 1,
			'C_PENDING'       => true,
			'C_CONTROLS'      => CategoriesAuthorizationsService::check_authorizations()->moderation(),
			'C_GRID_VIEW'     => $config->get_display_type() == SpotsConfig::GRID_VIEW,
			'C_TABLE_VIEW'    => $config->get_display_type() == SpotsConfig::TABLE_VIEW,
			'C_PAGINATION'    => $pagination->has_several_pages(),

			'ITEMS_PER_ROW'   => $number_columns_display_per_line,
			'PAGINATION'      => $pagination->display()
		));

		while ($row = $result->fetch())
		{
			$item = new SpotsItem();
			$item->set_properties($row);

			$this->view->assign_block_vars('self_items', array_merge($item->get_template_vars()));
		}
		$result->dispose();
	}

	private function get_pagination($condition, $parameters, $page)
	{
		$items_number = SpotsService::count($condition, $parameters);

		$pagination = new ModulePagination($page, $items_number, (int)SpotsConfig::load()->get_items_per_page());
		$pagination->set_url(SpotsUrlBuilder::display_pending('%d'));

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

	private function generate_response()
	{
		$response = new SiteDisplayResponse($this->view);

		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->lang['spots.pending.items'], $this->config->get_module_name());
		$graphical_environment->get_seo_meta_data()->set_description($this->lang['spots.seo.description.pending']);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(SpotsUrlBuilder::display_pending(AppContext::get_request()->get_getint('page', 1)));

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->config->get_module_name(), SpotsUrlBuilder::home());
		$breadcrumb->add($this->lang['spots.pending.items'], SpotsUrlBuilder::display_pending());

		return $response;
	}
}
?>
