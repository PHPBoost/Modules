<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2025 01 09
 * @since       PHPBoost 6.0 - 2022 10 17
 */

class VideoMemberItemsController extends DefaultModuleController
{
	private $member;

	protected function get_template_to_use()
	{
		return new FileTemplate('video/VideoSeveralItemsController.tpl');
	}

	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();

		$this->build_view($request);

		return $this->generate_response($request);
	}

	public function build_view(HTTPRequestCustom $request)
	{
		$now = new Date();
		$comments_config = CommentsConfig::load();
		$content_management_config = ContentManagementConfig::load();
		$authorized_categories = CategoriesService::get_authorized_categories(Category::ROOT_CATEGORY, $this->config->is_summary_displayed_to_guests());

		$condition = 'WHERE id_category IN :authorized_categories
		AND author_user_id = :user_id
		AND (published = 1 OR (published = 2 AND (publishing_start_date > :timestamp_now OR (publishing_end_date != 0 AND publishing_end_date < :timestamp_now))))';
		$parameters = array(
			'user_id' => $this->get_member()->get_id(),
			'authorized_categories' => $authorized_categories,
			'timestamp_now' => $now->get_timestamp()
		);

		$page = $request->get_getint('page', 1);
		$pagination = $this->get_pagination($condition, $parameters, $page);

		$result = PersistenceContext::get_querier()->select('SELECT video.*, member.*, com.comments_number, notes.average_notes, notes.notes_number, note.note
		FROM '. VideoSetup::$video_table .' video
		LEFT JOIN '. DB_TABLE_MEMBER .' member ON member.user_id = video.author_user_id
		LEFT JOIN ' . DB_TABLE_COMMENTS_TOPIC . ' com ON com.id_in_module = video.id AND com.module_id = \'video\'
		LEFT JOIN ' . DB_TABLE_AVERAGE_NOTES . ' notes ON notes.id_in_module = video.id AND notes.module_name = \'video\'
		LEFT JOIN ' . DB_TABLE_NOTE . ' note ON note.id_in_module = video.id AND note.module_name = \'video\' AND note.user_id = :user_id
		' . $condition . '
		ORDER BY update_date
		LIMIT :number_items_per_page OFFSET :display_from', array_merge($parameters, array(
			'number_items_per_page' => $pagination->get_number_items_per_page(),
			'display_from' => $pagination->get_display_from()
		)));

		$this->view->put_all(array(
			'C_SUBCATEGORIES_DISPLAY' => $this->config->get_subcategories_display(),
			'C_MEMBER_ITEMS'          => true,
			'C_MY_ITEMS'              => $this->is_current_member_displayed(),
			'C_ITEMS'                 => $result->get_rows_count() > 0,
			'C_SEVERAL_ITEMS'         => $result->get_rows_count() > 1,
			'C_GRID_VIEW'             => $this->config->get_display_type() == VideoConfig::GRID_VIEW,
			'C_LIST_VIEW'             => $this->config->get_display_type() == VideoConfig::LIST_VIEW,
			'C_TABLE_VIEW'            => $this->config->get_display_type() == VideoConfig::TABLE_VIEW,
			'C_CATEGORY_DESCRIPTION'  => !empty($category_description),
			'CATEGORIES_PER_ROW'      => $this->config->get_categories_per_row(),
			'C_ENABLED_COMMENTS'      => $comments_config->module_comments_is_enabled('video'),
			'C_ENABLED_NOTATION'      => $content_management_config->module_notation_is_enabled('video'),
			'C_AUTHOR_DISPLAYED'      => $this->config->is_author_displayed(),
			'C_PAGINATION'            => $pagination->has_several_pages(),
			'ITEMS_PER_ROW' => $this->config->get_items_per_row(),
			'PAGINATION' 	=> $pagination->display(),
			'TABLE_COLSPAN' => 4 + (int)$comments_config->module_comments_is_enabled('video') + (int)$content_management_config->module_notation_is_enabled('video'),
			'MEMBER_NAME' 	=> $this->get_member()->get_display_name()
		));

		while ($row = $result->fetch())
		{
			$item = new VideoItem();
			$item->set_properties($row);

			$keywords = $item->get_keywords();
			$has_keywords = count($keywords) > 0;

			$this->view->assign_block_vars('items', array_merge($item->get_template_vars(), array(
				'C_KEYWORDS' => $has_keywords
			)));

			if ($has_keywords)
				$this->build_keywords_view($keywords);
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
		$items_number = VideoService::count($condition, $parameters);

		$pagination = new ModulePagination($page, $items_number, (int)VideoConfig::load()->get_items_per_page());
		$pagination->set_url(VideoUrlBuilder::display_member_items($this->get_member()->get_id(), '%d'));

		if ($pagination->current_page_is_empty() && $page > 1)
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}

		return $pagination;
	}

	private function build_keywords_view($keywords)
	{
		$nbr_keywords = count($keywords);

		$i = 1;
		foreach ($keywords as $keyword)
		{
			$this->view->assign_block_vars('items.keywords', array(
				'C_SEPARATOR' => $i < $nbr_keywords,
				'NAME' => $keyword->get_name(),
				'URL' => VideoUrlBuilder::display_tag($keyword->get_rewrited_name())->rel(),
			));
			$i++;
		}
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
		$page = $request->get_getint('page', 1);
		$page_title = $this->is_current_member_displayed() ? $this->lang['video.my.items'] : $this->lang['video.member.items'] . ' ' . $this->get_member()->get_display_name();
		$response = new SiteDisplayResponse($this->view);

		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($page_title, $this->lang['video.module.title'], $page);
		$graphical_environment->get_seo_meta_data()->set_description(StringVars::replace_vars($this->lang['video.seo.description.member'], array('author' => $this->get_member()->get_display_name())), $page);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(VideoUrlBuilder::display_member_items($this->get_member()->get_id(), $page));

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['video.module.title'], VideoUrlBuilder::home());
		$breadcrumb->add($page_title, VideoUrlBuilder::display_member_items($this->get_member()->get_id(), $page));

		return $response;
	}
}
?>
