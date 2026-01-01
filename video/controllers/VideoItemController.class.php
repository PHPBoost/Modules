<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2022 10 17
 * @since       PHPBoost 6.0 - 2022 10 17
 */

class VideoItemController extends DefaultModuleController
{
	protected function get_template_to_use()
	{
		return new FileTemplate('video/VideoItemController.tpl');
	}

	public function execute(HTTPRequestCustom $request)
	{
		$this->build_view();
		$this->count_views_number($request);
		$this->check_authorizations();

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
					$this->item = VideoService::get_item($id);
				} catch (RowNotFoundException $e) {
					$error_controller = PHPBoostErrors::unexisting_page();
					DispatchManager::redirect($error_controller);
				}
			}
			else
				$this->item = new VideoItem();
		}
		return $this->item;
	}

	private function count_views_number(HTTPRequestCustom $request)
	{
		if (!$this->item->is_published())
		{
			$this->view->put('NOT_VISIBLE_MESSAGE', MessageHelper::display($this->lang['warning.element.not.visible'], MessageHelper::WARNING));
		}
		else
		{
			if ($request->get_url_referrer() && !TextHelper::strstr($request->get_url_referrer(), VideoUrlBuilder::display($this->item->get_category()->get_id(), $this->item->get_category()->get_rewrited_name(), $this->item->get_id(), $this->item->get_rewrited_title())->rel()))
			{
				$this->item->set_views_number($this->item->get_views_number() + 1);
				VideoService::update_views_number($this->item);
			}
		}
	}

	private function build_view()
	{
		$config = VideoConfig::load();
		$comments_config = CommentsConfig::load();
		$content_management_config = ContentManagementConfig::load();
		$item = $this->get_item();
		$category = $item->get_category();

		$keywords = $item->get_keywords();
		$has_keywords = count($keywords) > 0;

		$this->view->put_all(array_merge($item->get_template_vars(), array(
			'C_AUTHOR_DISPLAYED' => $config->is_author_displayed(),
			'C_ENABLED_COMMENTS' => $comments_config->module_comments_is_enabled('video'),
			'C_ENABLED_NOTATION' => $content_management_config->module_notation_is_enabled('video'),
			'C_KEYWORDS' => $has_keywords,
			'NOT_VISIBLE_MESSAGE' => MessageHelper::display($this->lang['warning.element.not.visible'], MessageHelper::WARNING),
		)));

		if ($comments_config->module_comments_is_enabled('video'))
		{
			$comments_topic = new VideoCommentsTopic($item);
			$comments_topic->set_id_in_module($item->get_id());
			$comments_topic->set_url(VideoUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $item->get_id(), $item->get_rewrited_title()));

			$this->view->put('COMMENTS', $comments_topic->display());
		}

		if ($has_keywords)
			$this->build_keywords_view($keywords);

		$this->build_player_view();
	}

	public function build_player_view()
	{
		$item = $this->get_item();
		$config = VideoConfig::load();
		$video_id = '';

		$mime_type = $config->get_mime_type_list();
		if (in_array($item->get_mime_type(), $mime_type))
		{
			$video_tpl = new FileTemplate('video/players/html5_player.tpl');
		}			
		else if ($item->get_mime_type() == 'video/host')
		{
			$video_tpl = new FileTemplate('video/players/host_player.tpl');

			$pathinfo = pathinfo($item->get_file_url()->relative());
			$dirname = $pathinfo['dirname'];
			$video_id = $pathinfo['basename'];
			$parsed_url = parse_url($item->get_file_url()->relative());

			foreach ($config->get_players() as $id => $options) {
				$platform = $options['platform'];
				$domain = $options['domain'];
				$player = $options['player'];

				if (strpos($dirname, $domain) !== false) {
					// Youtube
					$watch = 'watch?v=';
					if (strpos($video_id, $watch) !== false) {
						$video_id = substr_replace($video_id, '', 0, 8);
						list($video_id) = explode('&', $video_id);
					}

					// Odysee
					$odysee_dl_link = strpos($dirname, 'download') !== false;
					$odysee_embed_link = strpos($dirname, 'embed') !== false;
					if (strpos($platform, 'odysee') !== false) {
						if ($odysee_dl_link || $odysee_embed_link) {
							$explode = explode('/', $dirname);
							$video_id = $explode[5] . '/' . $video_id; // add video id in final url
						} else {
							$controller = new UserErrorController(
								LangLoader::get_message('warning.error', 'warning-lang'),
								LangLoader::get_message('e_bad_url_odysee', 'common', 'video')
							);
							DispatchManager::redirect($controller);
						}
					}

					// Peertube
					// if(strpos($platform, 'peertube') !== false)
					// {
					// 	$peertube_watch_link = strpos($dirname, $domain . '/w') !== false;
					// 	$peertube_embed_link = strpos($dirname, $domain . '/videos/embed') !== false;
					
					// 	if (!$peertube_embed_link && !$peertube_watch_link) {
					// 		$controller = new UserErrorController(
					// 			LangLoader::get_message('warning.error', 'warning-lang'),
					// 			LangLoader::get_message('e_bad_url_peertube', 'common', 'video')
					// 		);
					// 		DispatchManager::redirect($controller);
					// 	}
					// }

					// Twitch
					$twitch_player = strpos($dirname, 'twitch') !== false;
					$parent = pathinfo(GeneralConfig::load()->get_site_url());
					$parent = $parent['basename'];
					if ($twitch_player) {
						$video_tpl->put_all(array(
							'C_TWITCH' => true,
							'PARENT' => $parent
						));
					}

					// All
					$video_tpl->put_all(array(
						'PLAYER' => $player,
						'VIDEO_ID' => $video_id
					));
				}
			}
		}			

		$video_tpl->put_all(array(
			'FILE_URL' => Url::to_rel($item->get_file_url()),
			'MIME' => $item->get_mime_type(),
			'WIDTH' => $item->get_width(),
			'HEIGHT' => $item->get_height()
		));

		$this->view->put('VIDEO_FORMAT', $video_tpl);
	}

	private function build_keywords_view($keywords)
	{
		$nbr_keywords = count($keywords);

		$i = 1;
		foreach ($keywords as $keyword)
		{
			$this->view->assign_block_vars('keywords', array(
				'C_SEPARATOR' => $i < $nbr_keywords,
				'NAME' => $keyword->get_name(),
				'URL' => VideoUrlBuilder::display_tag($keyword->get_rewrited_name())->rel(),
			));
			$i++;
		}
	}

	private function check_authorizations()
	{
		$item = $this->get_item();

		$current_user = AppContext::get_current_user();
		$not_authorized = !CategoriesAuthorizationsService::check_authorizations($item->get_id_category())->moderation() && !CategoriesAuthorizationsService::check_authorizations($item->get_id_category())->write() && (!CategoriesAuthorizationsService::check_authorizations($item->get_id_category())->contribution() || $item->get_author_user()->get_id() != $current_user->get_id());

		switch ($item->get_publishing_state()) {
			case VideoItem::PUBLISHED:
				if (!CategoriesAuthorizationsService::check_authorizations($item->get_id_category())->read())
				{
					$error_controller = PHPBoostErrors::user_not_authorized();
					DispatchManager::redirect($error_controller);
				}
			break;
			case VideoItem::NOT_PUBLISHED:
				if ($not_authorized || ($current_user->get_id() == User::VISITOR_LEVEL))
				{
					$error_controller = PHPBoostErrors::user_not_authorized();
					DispatchManager::redirect($error_controller);
				}
			break;
			case VideoItem::DEFERRED_PUBLICATION:
				if (!$item->is_published() && ($not_authorized || ($current_user->get_id() == User::VISITOR_LEVEL)))
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
		$item = $this->get_item();
		$category = $item->get_category();
		$response = new SiteDisplayResponse($this->view);

		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($item->get_title(), ($category->get_id() != Category::ROOT_CATEGORY ? $category->get_name() . ' - ' : '') . $this->lang['video.module.title']);
		$graphical_environment->get_seo_meta_data()->set_description($item->get_real_summary());
		$graphical_environment->get_seo_meta_data()->set_canonical_url(VideoUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $item->get_id(), $item->get_rewrited_title()));

		if ($item->has_thumbnail())
			$graphical_environment->get_seo_meta_data()->set_picture_url($item->get_thumbnail());

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['video.module.title'],VideoUrlBuilder::home());

		$categories = array_reverse(CategoriesService::get_categories_manager()->get_parents($item->get_id_category(), true));
		foreach ($categories as $id => $category)
		{
			if ($category->get_id() != Category::ROOT_CATEGORY)
				$breadcrumb->add($category->get_name(), VideoUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name()));
		}
		$breadcrumb->add($item->get_title(), VideoUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $item->get_id(), $item->get_rewrited_title()));

		return $response;
	}
}
?>
