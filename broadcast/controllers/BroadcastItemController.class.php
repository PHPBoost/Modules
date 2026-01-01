<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2023 10 02
 * @since       PHPBoost 6.0 - 2022 10 25
 */

class BroadcastItemController extends DefaultModuleController
{
    private $category;

	protected function get_template_to_use()
	{
		return new FileTemplate('broadcast/BroadcastItemController.tpl');
	}

	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();

		$this->build_view();

		return $this->generate_response();
	}

	private function get_item()
	{
		if ($this->item === null)
		{
			$id = AppContext::get_request()->get_getint('id', 0);
			if (!empty($id))
			{
				try {
					$this->item = BroadcastService::get_item($id);
				} catch (RowNotFoundException $e) {
					$error_controller = PHPBoostErrors::unexisting_page();
					DispatchManager::redirect($error_controller);
				}
			}
			else
				$this->item = new BroadcastItem();
		}
		return $this->item;
	}

	private function build_view()
	{
		$this->category = $this->item->get_category();
		$this->view->put_all(array_merge($this->item->get_array_tpl_vars(), array(
			'NOT_VISIBLE_MESSAGE' => MessageHelper::display($this->lang['warning.element.not.visible'], MessageHelper::WARNING)
		)));

		foreach (TextHelper::unserialize($this->item->get_release_days()) as $id => $options)
		{
			$this->view->assign_block_vars('days', $this->item->get_weekly_planner_vars($id));
		}
	}

	private function check_authorizations()
	{
		$this->item = $this->get_item();

		$current_user = AppContext::get_current_user();
		$not_authorized = !CategoriesAuthorizationsService::check_authorizations($this->item->get_id_category())->moderation() && !CategoriesAuthorizationsService::check_authorizations($this->item->get_id_category())->write();

		switch ($this->item->get_published()) {
			case BroadcastItem::PUBLISHED_NOW:
				if (!CategoriesAuthorizationsService::check_authorizations($this->item->get_id_category())->read())
				{
					$error_controller = PHPBoostErrors::user_not_authorized();
					DispatchManager::redirect($error_controller);
				}
			break;
			case BroadcastItem::NOT_PUBLISHED:
				if ($not_authorized || ($current_user->get_id() == User::VISITOR_LEVEL))
				{
					$error_controller = PHPBoostErrors::user_not_authorized();
					DispatchManager::redirect($error_controller);
				}
			break;
			default:
				$error_controller = PHPBoostErrors::unexisting_page();
				DispatchManager::redirect($error_controller);
			break;
		}
	}

	private function generate_response()
	{
		$response = new SiteDisplayResponse($this->view);

		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->item->get_title(), $this->lang['broadcast.module.title']);
		$graphical_environment->get_seo_meta_data()->set_description($this->item->get_content());
		$graphical_environment->get_seo_meta_data()->set_canonical_url(BroadcastUrlBuilder::display($this->category->get_id(), $this->category->get_rewrited_name(), $this->item->get_id(), $this->item->get_rewrited_title()));

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['broadcast.module.title'], BroadcastUrlBuilder::home());

		$categories = array_reverse(CategoriesService::get_categories_manager()->get_parents($this->item->get_id_category(), true));
		foreach ($categories as $id => $category)
		{
			if ($category->get_id() != Category::ROOT_CATEGORY)
				$breadcrumb->add($category->get_name(), BroadcastUrlBuilder::display_category($this->category->get_id(), $this->category->get_rewrited_name()));
		}
		$breadcrumb->add($this->item->get_title(), BroadcastUrlBuilder::display($this->category->get_id(), $this->category->get_rewrited_name(), $this->item->get_id(), $this->item->get_rewrited_title()));

		return $response;
	}
}
?>
