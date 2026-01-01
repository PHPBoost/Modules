<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2022 12 14
 * @since       PHPBoost 6.0 - 2022 10 25
 */

class BroadcastCategoryController extends DefaultModuleController
{
	private $category;

	protected function get_template_to_use()
	{
		return new FileTemplate('broadcast/BroadcastSeveralItemsController.tpl');
	}

	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();

		$this->build_view($request);

		return $this->generate_response($request);
	}

	private function build_view()
	{
		$this->view->put_all(array(
			'C_CATEGORY' 	   	   => true,
			'C_CATEGORY_THUMBNAIL' => !$this->get_category()->get_id() == Category::ROOT_CATEGORY && !empty($this->get_category()->get_thumbnail()->rel()),
			'C_ROOT_CATEGORY'  	   => $this->get_category()->get_id() == Category::ROOT_CATEGORY,
			'C_CALENDAR_VIEW'  	   => $this->config->get_display_type() == BroadcastConfig::CALENDAR_VIEW,
			'C_ACCORDION_VIEW' 	   => $this->config->get_display_type() == BroadcastConfig::ACCORDION_VIEW,

			'CATEGORY_ID' 		 => $this->get_category()->get_id(),
			'CATEGORY_NAME' 	 => $this->get_category()->get_name(),
			'CATEGORY_PARENT_ID' => $this->get_category()->get_id_parent(),
			'CATEGORY_SUB_ORDER' => $this->get_category()->get_order(),
			'MODULE_NAME' 		 => BroadcastConfig::load()->get_broadcast_name(),

			'U_EDIT_CATEGORY' 	   => $this->get_category()->get_id() == Category::ROOT_CATEGORY ? BroadcastUrlBuilder::configuration()->rel() : CategoriesUrlBuilder::edit($this->get_category()->get_id(), 'broadcast')->rel(),
			'U_CATEGORY_THUMBNAIL' => $this->get_category()->get_thumbnail()->rel()
		));

		$this->build_day_view('monday');
		$this->build_day_view('tuesday');
		$this->build_day_view('wednesday');
		$this->build_day_view('thursday');
		$this->build_day_view('friday');
		$this->build_day_view('saturday');
		$this->build_day_view('sunday');
	}

	private function build_day_view($day)
	{
		${$day . '_tpl'} = new FileTemplate('broadcast/BroadcastDayProgramsController.tpl');
		${$day . '_tpl'}->add_lang($this->lang);

		$authorized_categories = CategoriesService::get_authorized_categories($this->get_category()->get_id(), '', 'broadcast');
		$condition = 'WHERE id_category IN :authorized_categories AND published = 1';
		$parameters = array('authorized_categories' => $authorized_categories);

		$result = PersistenceContext::get_querier()->select('SELECT broadcast.*, member.*,
		time (from_unixtime(start_time)) AS hour
		FROM ' . BroadcastSetup::$broadcast_table . ' broadcast
		LEFT JOIN  ' . DB_TABLE_MEMBER . ' member ON member.user_id = broadcast.author_user_id
		' . $condition . '
		ORDER BY hour ASC', $parameters);

		${$day . '_tpl'}->put_all(array(
			'C_ACCORDION_VIEW' => $this->config->get_display_type() == BroadcastConfig::ACCORDION_VIEW,
			'C_TABLE_VIEW' 	   => $this->config->get_display_type() == BroadcastConfig::TABLE_VIEW,
			'C_CALENDAR_VIEW'  => $this->config->get_display_type() == BroadcastConfig::CALENDAR_VIEW,
			'C_ITEMS' 		   => $result->get_rows_count() > 0,
			'C_CONTROLS' 	   => CategoriesAuthorizationsService::check_authorizations($this->get_category()->get_id())->moderation(),

			'DAY_ID' => $day,
			'DAY'    => $this->lang['date.' . $day]
		));

		while ($row = $result->fetch())
		{
			$item = new BroadcastItem();
			$item->set_properties($row);

			$days_id = array();
			if (!empty($item->get_release_days()))
			{
				foreach (TextHelper::unserialize($item->get_release_days()) as $id => $options) {
					$days_id[] = $options->get_id();
				}

				${$day . '_tpl'}->assign_block_vars('items', array_merge($item->get_array_tpl_vars(), array(
					'C_SELECTED_DAY' => in_array($day, $days_id)
				)));
			}
		}
		$result->dispose();

		$this->view->put(TextHelper::strtoupper($day . '_PRG'), ${$day . '_tpl'});
	}

	private function get_category()
	{
		if ($this->category === null)
		{
			$id = AppContext::get_request()->get_getint('id_category', 0);
			if (!empty($id))
			{
				try {
					$this->category = CategoriesService::get_categories_manager('broadcast')->get_categories_cache()->get_category($id);
				} catch (CategoryNotFoundException $e) {
					$error_controller = PHPBoostErrors::unexisting_page();
					DispatchManager::redirect($error_controller);
				}
			}
			else
			{
				$this->category = CategoriesService::get_categories_manager()->get_categories_cache()->get_category(Category::ROOT_CATEGORY);
			}
		}
		return $this->category;
	}

	private function check_authorizations()
	{
		if (AppContext::get_current_user()->is_guest())
		{
			if (!CategoriesAuthorizationsService::check_authorizations($this->get_category()->get_id())->read())
			{
				$error_controller = PHPBoostErrors::user_not_authorized();
				DispatchManager::redirect($error_controller);
			}
		}
		else
		{
			if (!CategoriesAuthorizationsService::check_authorizations($this->get_category()->get_id())->read())
			{
				$error_controller = PHPBoostErrors::user_not_authorized();
				DispatchManager::redirect($error_controller);
			}
		}
	}

	private function generate_response(HTTPRequestCustom $request)
	{
		$response = new SiteDisplayResponse($this->view);

		$graphical_environment = $response->get_graphical_environment();
		$page = $request->get_getint('page', 1);

		if ($this->get_category()->get_id() != Category::ROOT_CATEGORY)
			$graphical_environment->set_page_title($this->get_category()->get_name(), $this->lang['broadcast.module.title'], $page);
		else
			$graphical_environment->set_page_title($this->lang['broadcast.module.title'], '', $page);

		$graphical_environment->get_seo_meta_data()->set_description($this->get_category()->get_description());
		$graphical_environment->get_seo_meta_data()->set_canonical_url(BroadcastUrlBuilder::display_category($this->get_category()->get_id(), $this->get_category()->get_rewrited_name(), AppContext::get_request()->get_getint('page', 1)));

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['broadcast.module.title'], BroadcastUrlBuilder::home());

		$categories = array_reverse(CategoriesService::get_categories_manager()->get_parents($this->get_category()->get_id(), true));
		foreach ($categories as $id => $category)
		{
			if ($category->get_id() != Category::ROOT_CATEGORY)
				$breadcrumb->add($category->get_name(), BroadcastUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name()));
		}

		return $response;
	}

	public static function get_view()
	{
		$object = new self('broadcast');
		$object->check_authorizations();
		$object->build_view(AppContext::get_request());
		return $object->view;
	}
}
?>
