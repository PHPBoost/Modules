<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2024 02 05
 * @since       PHPBoost 5.1 - 2018 03 15
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class SmalladsItemController extends DefaultModuleController
{
	private $category;

	private $email_form;

	protected function get_template_to_use()
	{
		return new FileTemplate('smallads/SmalladsItemController.tpl');
	}

	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();

		$this->check_pending_items($request);

		$this->build_view($request);

		return $this->generate_response();
	}

	private function get_item()
	{
		if ($this->item === null)
		{
			$id = AppContext::get_request()->get_getint('id', 0);
			if (!empty($id))
			{
				try
				{
					$this->item = SmalladsService::get_item($id);
				}
				catch (RowNotFoundException $e)
				{
					$error_controller = PHPBoostErrors::unexisting_page();
					DispatchManager::redirect($error_controller);
				}
			}
			else
				$this->item = new SmalladsItem();
		}
		return $this->item;
	}

	private function check_pending_items(HTTPRequestCustom $request)
	{
		if(!$this->item->is_published() && $this->item->is_archived())
		{
			$this->view->put('NOT_VISIBLE_MESSAGE', MessageHelper::display($this->lang['smallads.item.is.archived'], MessageHelper::ERROR));
		}
		else if (!$this->item->is_published())
		{
			$this->view->put('NOT_VISIBLE_MESSAGE', MessageHelper::display(LangLoader::get_message('warning.element.not.visible', 'warning-lang'), MessageHelper::WARNING));
		}
		else
		{
			if ($request->get_url_referrer() && !TextHelper::strstr($request->get_url_referrer(), SmalladsUrlBuilder::display($this->item->get_category()->get_id(), $this->item->get_category()->get_rewrited_name(), $this->item->get_id(), $this->item->get_rewrited_title())->rel()))
			{
				$this->item->set_views_number($this->item->get_views_number() + 1);
				SmalladsService::update_views_number($this->item);
			}
		}
	}

	private function build_view(HTTPRequestCustom $request)
	{
		$comments_config = CommentsConfig::load();
		$content_management_config = ContentManagementConfig::load();

		$this->category = $this->item->get_category();

		$this->build_email_form();
		$this->build_sources_view();
		$this->build_carousel_view();
		$this->build_keywords_view();
		$this->build_suggested_items($this->item);
		$this->build_navigation_links($this->item);

		$this->view->put_all(array_merge($this->item->get_template_vars(), array(
			'C_ENABLED_COMMENTS' => $comments_config->module_comments_is_enabled('smallads')
		)));

		// Comments
		if ($comments_config->module_comments_is_enabled('smallads'))
		{
			$comments_topic = new SmalladsCommentsTopic($this->item);
			$comments_topic->set_id_in_module($this->item->get_id());
			$comments_topic->set_url(SmalladsUrlBuilder::display($this->category->get_id(), $this->category->get_rewrited_name(), $this->item->get_id(), $this->item->get_rewrited_title()));

			$this->view->put('COMMENTS', $comments_topic->display());
		}

		// Email sending
		if ($this->submit_button->has_been_submited() && $this->email_form->validate())
		{
			if ($this->send_item_email())
			{
				$this->view->put('MESSAGE_HELPER', MessageHelper::display($this->lang['smallads.message.success.email'], MessageHelper::SUCCESS));
				$this->view->put('C_SMALLAD_EMAIL_SENT', true);
			}
			else
				$this->view->put('MESSAGE_HELPER', MessageHelper::display($this->lang['smallads.message.error.email'], MessageHelper::ERROR, 5));
		}

		$this->view->put('EMAIL_FORM', $this->email_form->display());
	}

	private function build_sources_view()
	{
		$sources = $this->item->get_sources();
		$nbr_sources = count($sources);
		$this->view->put('C_SOURCES', $nbr_sources > 0);

		$i = 1;
		foreach ($sources as $name => $url)
		{
			$this->view->assign_block_vars('sources', array(
				'C_SEPARATOR' => $i < $nbr_sources,
				'NAME' => $name,
				'URL' => $url,
			));
			$i++;
		}
	}

	private function build_carousel_view()
	{
		$carousel = $this->item->get_carousel();
		$nbr_pictures = count($carousel);
		$this->view->put('C_CAROUSEL', $nbr_pictures > 0);

		$i = 1;
		foreach ($carousel as $id => $options)
		{

			$this->view->assign_block_vars('carousel', array(
				'C_DESCRIPTION' => !empty($options['description']),
				'DESCRIPTION' => $options['description'],
				'U_PICTURE' => Url::to_rel($options['picture_url']),
			));
			$i++;
		}
	}

	private function build_keywords_view()
	{
		$keywords = $this->item->get_keywords();
		$nbr_keywords = count($keywords);
		$this->view->put('C_KEYWORDS', $nbr_keywords > 0);

		$i = 1;
		foreach ($keywords as $keyword)
		{
			$this->view->assign_block_vars('keywords', array(
				'C_SEPARATOR' => $i < $nbr_keywords,
				'NAME' => $keyword->get_name(),
				'URL' => SmalladsUrlBuilder::display_tag($keyword->get_rewrited_name())->rel(),
			));
			$i++;
		}
	}

	private function build_suggested_items(SmalladsItem $item)
	{
		$now = new Date();

		$result = PersistenceContext::get_querier()->select('SELECT
			id, title, id_category, rewrited_title, thumbnail_url, completed, archived, creation_date, update_date,
			(2 * FT_SEARCH_RELEVANCE(title, :search_content) + FT_SEARCH_RELEVANCE(content, :search_content) / 3) AS relevance
		FROM ' . SmalladsSetup::$smallads_table . '
		WHERE (FT_SEARCH(title, :search_content) OR FT_SEARCH(content, :search_content)) AND id <> :excluded_id
		AND (published = 1 OR (published = 2 AND publishing_start_date < :timestamp_now AND (publishing_end_date > :timestamp_now OR publishing_end_date = 0)))
		AND completed = 0 AND archived = 0
		ORDER BY relevance DESC LIMIT 0, :limit_nb', array(
			'excluded_id' => $item->get_id(),
			'search_content' => $item->get_title() .','. $item->get_content(),
			'timestamp_now' => $now->get_timestamp(),
			'limit_nb' => (int)SmalladsConfig::load()->get_suggested_items_nb()
		));

		$this->view->put('C_SUGGESTED_ITEMS', $result->get_rows_count() > 0 && SmalladsConfig::load()->get_enabled_items_suggestions());

		while ($row = $result->fetch())
		{
			$date = $row['creation_date'] <= $row['update_date'] ? $row['update_date'] : $row['creation_date'];
			$this->view->assign_block_vars('suggested', array(
				'C_COMPLETED'     => $row['completed'],
				'C_HAS_THUMBNAIL' => !empty($row['thumbnail_url']),
				'TITLE'           => $row['title'],
				'DATE'            => Date::to_format($date, Date::FORMAT_DAY_MONTH_YEAR),
				'U_THUMBNAIL'     => Url::to_rel($row['thumbnail_url']),
				'U_ITEM'          => SmalladsUrlBuilder::display($row['id_category'], CategoriesService::get_categories_manager()->get_categories_cache()->get_category($row['id_category'])->get_rewrited_name(), $row['id'], $row['rewrited_title'])->rel()
			));
		}
		$result->dispose();
	}

	private function build_navigation_links(SmalladsItem $item)
	{
		$now = new Date();
		$item_timestamp = $item->get_creation_date()->get_timestamp();

		$result = PersistenceContext::get_querier()->select('
		(SELECT id, title, id_category, rewrited_title, thumbnail_url, completed, \'PREVIOUS\' as type
		FROM '. SmalladsSetup::$smallads_table .'
		WHERE (published = 1 OR (published = 2 AND publishing_start_date < :timestamp_now AND (publishing_end_date > :timestamp_now OR publishing_end_date = 0))) AND creation_date < :item_timestamp AND id_category IN :authorized_categories ORDER BY creation_date DESC LIMIT 1 OFFSET 0)
		UNION
		(SELECT id, title, id_category, rewrited_title, thumbnail_url, completed, \'NEXT\' as type
		FROM '. SmalladsSetup::$smallads_table .'
		WHERE (published = 1 OR (published = 2 AND publishing_start_date < :timestamp_now AND (publishing_end_date > :timestamp_now OR publishing_end_date = 0))) AND creation_date > :item_timestamp AND id_category IN :authorized_categories ORDER BY creation_date ASC LIMIT 1 OFFSET 0)
		', array(
			'timestamp_now' => $now->get_timestamp(),
			'item_timestamp' => $item_timestamp,
			'authorized_categories' => array($item->get_id_category())
		));

		$this->view->put_all(array(
			'C_RELATED_LINKS' => $result->get_rows_count() > 0 && SmalladsConfig::load()->get_enabled_navigation_links(),
		));

		while ($row = $result->fetch())
		{
			$this->view->put_all(array(
				'C_'. $row['type'] .'_COMPLETED' => $row['completed'],
				'C_'. $row['type'] .'_ITEM' => true,
				'C_' . $row['type'] . '_HAS_THUMBNAIL' => !empty($row['thumbnail_url']),
				$row['type'] . '_ITEM' => $row['title'],
				'U_'. $row['type'] . '_THUMBNAIL' => Url::to_rel($row['thumbnail_url']),
				'U_'. $row['type'] .'_ITEM' => SmalladsUrlBuilder::display($row['id_category'], CategoriesService::get_categories_manager()->get_categories_cache()->get_category($row['id_category'])->get_rewrited_name(), $row['id'], $row['rewrited_title'])->rel(),
			));
		}
		$result->dispose();
	}

	private function build_email_form()
	{
		if(!empty($this->item->get_custom_author_name()))
			$author_name = $this->item->get_custom_author_name();
		else
			$author_name = $this->item->get_author_user()->get_display_name();

		$email_form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTML('send_a_mail', $this->lang['smallads.contact.author'], array('description' => $author_name));
		$email_form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldFree('email_smallad_title', $this->lang['smallads.item.interest'], $this->item->get_title()));

		$fieldset->add_field(new FormFieldTextEditor('sender_name', $this->lang['smallads.sender.name'], AppContext::get_current_user()->get_display_name(),
			array('required' => true)
		));

		$fieldset->add_field(new FormFieldMailEditor('sender_email', $this->lang['smallads.sender.email'], AppContext::get_current_user()->get_email(),
			array('required' => true)
		));

		$fieldset->add_field(new FormFieldMultiLineTextEditor('sender_message', $this->lang['smallads.sender.message'], '',
			array('required' => true)
		));

		$this->submit_button = new FormButtonDefaultSubmit();
		$email_form->add_button($this->submit_button);
		$email_form->add_button(new FormButtonReset());

		$this->email_form = $email_form;
	}

	private function send_item_email()
	{
		$item_message = '';
		$item_subject = $this->item->get_title();
		$item_sender_name = $this->email_form->get_value('sender_name');
		$item_sender_email = $this->email_form->get_value('sender_email');
		$item_message = $this->email_form->get_value('sender_message');
		$item_recipient_email = $this->item->get_custom_author_email();

		$item_email = new Mail();
		$item_email->set_sender(MailServiceConfig::load()->get_default_mail_sender(), $this->lang['smallads.module.title']);
		$item_email->set_reply_to($item_sender_email, $item_sender_name);
		$item_email->set_subject($item_subject);
		$item_email->set_content(TextHelper::html_entity_decode($item_message));
		$item_email->add_recipient($item_recipient_email);

		$send_email = AppContext::get_mail_service();

		return $send_email->try_to_send($item_email);
	}

	private function check_authorizations()
	{
		$item = $this->get_item();

		$current_user = AppContext::get_current_user();
		$not_authorized = !CategoriesAuthorizationsService::check_authorizations($item->get_id_category())->moderation() && !CategoriesAuthorizationsService::check_authorizations($item->get_id_category())->write() && (!CategoriesAuthorizationsService::check_authorizations($item->get_id_category())->contribution() || $item->get_author_user()->get_id() != $current_user->get_id());

		switch ($item->get_publishing_state())
		{
			case SmalladsItem::PUBLISHED_NOW:
				if (!CategoriesAuthorizationsService::check_authorizations($item->get_id_category())->read())
				{
					$error_controller = PHPBoostErrors::user_not_authorized();
					DispatchManager::redirect($error_controller);
				}
			break;
			case SmalladsItem::NOT_PUBLISHED:
				if ($not_authorized || ($current_user->get_id() == User::VISITOR_LEVEL))
				{
					$error_controller = PHPBoostErrors::user_not_authorized();
					DispatchManager::redirect($error_controller);
				}
			break;
			case SmalladsItem::DEFERRED_PUBLICATION:
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
		$response = new SiteDisplayResponse($this->view);

		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->item->get_title(), ($this->category->get_id() != Category::ROOT_CATEGORY ? $this->category->get_name() . ' - ' : '') . $this->lang['smallads.module.title']);
		$graphical_environment->get_seo_meta_data()->set_description($this->item->get_real_summary());
		$graphical_environment->get_seo_meta_data()->set_canonical_url(SmalladsUrlBuilder::display($this->category->get_id(), $this->category->get_rewrited_name(), $this->item->get_id(), $this->item->get_rewrited_title()));

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['smallads.module.title'], SmalladsUrlBuilder::home());

		$categories = array_reverse(CategoriesService::get_categories_manager()->get_parents($this->item->get_id_category(), true));
		foreach ($categories as $id => $category)
		{
			if ($category->get_id() != Category::ROOT_CATEGORY)
				$breadcrumb->add($category->get_name(), SmalladsUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name()));
		}
		$breadcrumb->add($this->item->get_title(), SmalladsUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $this->item->get_id(), $this->item->get_rewrited_title()));

		return $response;
	}
}
?>
