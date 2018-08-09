<?php
/*##################################################
 *		       SmalladsDisplayItemController.class.php
 *                            -------------------
 *   begin                : March 15, 2018
 *   copyright            : (C) 2018 Sebastien LARTIGUE
 *   email                : babsolune@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

/**
 * @author Sebastien LARTIGUE <babsolune@phpboost.com>
 */

class SmalladsDisplayItemController extends ModuleController
{
	private $lang;
	private $county_lang;
	private $tpl;
	private $smallad;
	private $category;

	private $email_form;
	private $submit_button;

	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();

		$this->init();

		$this->check_pending_smallad($request);

		$this->build_view($request);

		return $this->generate_response();
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'smallads');
		$this->county_lang = LangLoader::get('counties', 'smallads');
		$this->tpl = new FileTemplate('smallads/SmalladsDisplayItemController.tpl');
		$this->tpl->add_lang($this->lang);
		$this->tpl->add_lang($this->county_lang);
	}

	private function get_smallad()
	{
		if ($this->smallad === null)
		{
			$id = AppContext::get_request()->get_getint('id', 0);
			if (!empty($id))
			{
				try
				{
					$this->smallad = SmalladsService::get_smallad('WHERE smallads.id=:id', array('id' => $id));
				}
				catch (RowNotFoundException $e)
				{
					$error_controller = PHPBoostErrors::unexisting_page();
   					DispatchManager::redirect($error_controller);
				}
			}
			else
				$this->smallad = new Smallad();
		}
		return $this->smallad;
	}

	private function check_pending_smallad(HTTPRequestCustom $request)
	{
		if (!$this->smallad->is_published())
		{
			$this->tpl->put('NOT_VISIBLE_MESSAGE', MessageHelper::display(LangLoader::get_message('element.not_visible', 'status-messages-common'), MessageHelper::WARNING));
		}
		else
		{
			if ($request->get_url_referrer() && !TextHelper::strstr($request->get_url_referrer(), SmalladsUrlBuilder::display_item($this->smallad->get_category()->get_id(), $this->smallad->get_category()->get_rewrited_name(), $this->smallad->get_id(), $this->smallad->get_rewrited_title())->rel()))
			{
				$this->smallad->set_views_number($this->smallad->get_views_number() + 1);
				SmalladsService::update_views_number($this->smallad);
			}
		}
	}

	private function build_view(HTTPRequestCustom $request)
	{
		$comments_config = CommentsConfig::load();
		$content_management_config = ContentManagementConfig::load();

		$this->category = $this->smallad->get_category();

		$this->build_email_form();
		$this->build_sources_view();
		$this->build_carousel_view();
		$this->build_keywords_view();
		$this->build_suggested_items($this->smallad);
		$this->build_navigation_links($this->smallad);

		$this->tpl->put_all(array_merge($this->smallad->get_array_tpl_vars(), array(
			'C_COMMENTS_ENABLED' => $comments_config->module_comments_is_enabled('smallads'),
			'CONTENTS'           => FormatingHelper::second_parse($this->smallad->get_contents()),
			'U_EDIT_ITEM'     	 => SmalladsUrlBuilder::edit_item($this->smallad->get_id())->rel()
		)));

		//Affichage commentaires
		if ($comments_config->module_comments_is_enabled('smallads'))
		{
			$comments_topic = new SmalladsCommentsTopic($this->smallad);
			$comments_topic->set_id_in_module($this->smallad->get_id());
			$comments_topic->set_url(SmalladsUrlBuilder::display_item($this->category->get_id(), $this->category->get_rewrited_name(), $this->smallad->get_id(), $this->smallad->get_rewrited_title()));

			$this->tpl->put('COMMENTS', $comments_topic->display());
		}

		// Envoi d'email
		if ($this->submit_button->has_been_submited() && $this->email_form->validate())
		{
			if ($this->send_smallad_email())
			{
				$this->tpl->put('MSG', MessageHelper::display($this->lang['smallads.message.success.email'], MessageHelper::SUCCESS));
				$this->tpl->put('C_SMALLAD_EMAIL_SENT', true);
			}
			else
				$this->tpl->put('MSG', MessageHelper::display($this->lang['smallads.message.error.email'], MessageHelper::ERROR, 5));
		}

		$this->tpl->put('EMAIL_FORM', $this->email_form->display());
	}

	private function build_sources_view()
	{
		$sources = $this->smallad->get_sources();
		$nbr_sources = count($sources);
		$this->tpl->put('C_SOURCES', $nbr_sources > 0);

		$i = 1;
		foreach ($sources as $name => $url)
		{
			$this->tpl->assign_block_vars('sources', array(
				'C_SEPARATOR' => $i < $nbr_sources,
				'NAME' => $name,
				'URL' => $url,
			));
			$i++;
		}
	}

	private function build_carousel_view()
	{
		$carousel = $this->smallad->get_carousel();
		$nbr_pictures = count($carousel);
		$this->tpl->put('C_CAROUSEL', $nbr_pictures > 0);

		$i = 1;
		foreach ($carousel as $id => $options)
		{
			if(filter_var($options['picture_url'], FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED))
				$ptr = false;
			else
				$ptr = true;

			$this->tpl->assign_block_vars('carousel', array(
				'C_PTR' => $ptr,
				'DESCRIPTION' => $options['description'],
				'U_PICTURE' => $options['picture_url'],
			));
			$i++;
		}
	}

	private function build_keywords_view()
	{
		$keywords = $this->smallad->get_keywords();
		$nbr_keywords = count($keywords);
		$this->tpl->put('C_KEYWORDS', $nbr_keywords > 0);

		$i = 1;
		foreach ($keywords as $keyword)
		{
			$this->tpl->assign_block_vars('keywords', array(
				'C_SEPARATOR' => $i < $nbr_keywords,
				'NAME' => $keyword->get_name(),
				'URL' => SmalladsUrlBuilder::display_tag($keyword->get_rewrited_name())->rel(),
			));
			$i++;
		}
	}

	private function build_suggested_items(Smallad $smallad)
	{
		$now = new Date();

		$result = PersistenceContext::get_querier()->select('
		SELECT id, title, id_category, rewrited_title, thumbnail_url, completed,
		(2 * FT_SEARCH_RELEVANCE(title, :search_content) + FT_SEARCH_RELEVANCE(contents, :search_content) / 3) AS relevance
		FROM ' . SmalladsSetup::$smallads_table . '
		WHERE (FT_SEARCH(title, :search_content) OR FT_SEARCH(contents, :search_content)) AND id <> :excluded_id
		AND (published = 1 OR (published = 2 AND publication_start_date < :timestamp_now AND (publication_end_date > :timestamp_now OR publication_end_date = 0)))
		ORDER BY relevance DESC LIMIT 0, :limit_nb', array(
			'excluded_id' => $smallad->get_id(),
			'search_content' => $smallad->get_title() .','. $smallad->get_contents(),
			'timestamp_now' => $now->get_timestamp(),
			'limit_nb' => (int) SmalladsConfig::load()->get_suggested_items_nb()
		));

		$this->tpl->put_all(array(
			'C_SUGGESTED_ITEMS' => $result->get_rows_count() > 0 && SmalladsConfig::load()->get_enabled_items_suggestions(),
			'SUGGESTED_COLUMNS' => SmalladsConfig::load()->get_displayed_cols_number_per_line()
		));

		while ($row = $result->fetch())
		{
			if(filter_var($row['thumbnail_url'], FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED))
				$ptr = false;
			else
				$ptr = true;

			$this->tpl->assign_block_vars('suggested_items', array(
				'C_PTR' => $ptr,
				'C_COMPLETED' => $row['completed'],
				'C_HAS_THUMBNAIL' => !empty($row['thumbnail_url']),
				'TITLE' => $row['title'],
				'THUMBNAIL' => $row['thumbnail_url'],
				'U_ITEM' => SmalladsUrlBuilder::display_item($row['id_category'], SmalladsService::get_categories_manager()->get_categories_cache()->get_category($row['id_category'])->get_rewrited_name(), $row['id'], $row['rewrited_title'])->rel()
			));
		}
		$result->dispose();
	}

	private function build_navigation_links(Smallad $smallad)
	{
		$now = new Date();
		$timestamp_smallad = $smallad->get_creation_date()->get_timestamp();

		$result = PersistenceContext::get_querier()->select('
		(SELECT id, title, id_category, rewrited_title, thumbnail_url, completed, \'PREVIOUS\' as type
		FROM '. SmalladsSetup::$smallads_table .'
		WHERE (published = 1 OR (published = 2 AND publication_start_date < :timestamp_now AND (publication_end_date > :timestamp_now OR publication_end_date = 0))) AND creation_date < :timestamp_smallad AND id_category IN :authorized_categories ORDER BY creation_date DESC LIMIT 1 OFFSET 0)
		UNION
		(SELECT id, title, id_category, rewrited_title, thumbnail_url, completed, \'NEXT\' as type
		FROM '. SmalladsSetup::$smallads_table .'
		WHERE (published = 1 OR (published = 2 AND publication_start_date < :timestamp_now AND (publication_end_date > :timestamp_now OR publication_end_date = 0))) AND creation_date > :timestamp_smallad AND id_category IN :authorized_categories ORDER BY creation_date ASC LIMIT 1 OFFSET 0)
		', array(
			'timestamp_now' => $now->get_timestamp(),
			'timestamp_smallad' => $timestamp_smallad,
			'authorized_categories' => array($smallad->get_id_category())
		));

		$this->tpl->put_all(array(
			'C_NAVIGATION_LINKS' => $result->get_rows_count() > 0 && SmalladsConfig::load()->get_enabled_navigation_links(),
		));

		while ($row = $result->fetch())
		{
			if(filter_var($row['thumbnail_url'], FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED))
				$ptr = false;
			else
				$ptr = true;

			$this->tpl->put_all(array(
				'C_'. $row['type'] .'_COMPLETED' => $row['completed'],
				'C_'. $row['type'] .'_ITEM' => true,
				'C_' . $row['type'] . '_PTR' => $ptr,
				'C_' . $row['type'] . '_HAS_THUMBNAIL' => !empty($row['thumbnail_url']),
				$row['type'] . '_ITEM_TITLE' => $row['title'],
				$row['type'] . '_THUMBNAIL' => $row['thumbnail_url'],
				'U_'. $row['type'] .'_ITEM' => SmalladsUrlBuilder::display_item($row['id_category'], SmalladsService::get_categories_manager()->get_categories_cache()->get_category($row['id_category'])->get_rewrited_name(), $row['id'], $row['rewrited_title'])->rel(),
			));
		}
		$result->dispose();
	}

	private function build_email_form()
	{
		if(!empty($this->smallad->get_custom_author_name()))
			$author_name = $this->smallad->get_custom_author_name();
		else
			$author_name = $user->get_display_name();

		$email_form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTML('send_a_mail', $this->lang['email.smallad.contact'], array('description' => $author_name));
		$email_form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldFree('email_smallad_title', $this->lang['email.smallad.title'], $this->smallad->get_title()));

		$fieldset->add_field(new FormFieldTextEditor('sender_name', $this->lang['email.sender.name'], AppContext::get_current_user()->get_display_name(),
			array('required' => true)
		));

		$fieldset->add_field(new FormFieldMailEditor('sender_email', $this->lang['email.sender.email'], AppContext::get_current_user()->get_email(),
			array('required' => true)
		));

		$fieldset->add_field(new FormFieldMultiLineTextEditor('sender_message', $this->lang['email.sender.message'], '',
			array('required' => true)
		));

		$this->submit_button = new FormButtonDefaultSubmit();
		$email_form->add_button($this->submit_button);
		$email_form->add_button(new FormButtonReset());

		$this->email_form = $email_form;
	}

	private function send_smallad_email()
	{
		$smallad_message = '';
		$smallad_subject = $this->smallad->get_title();
		$smallad_sender_name = $this->email_form->get_value('sender_name');
		$smallad_sender_email = $this->email_form->get_value('sender_email');
		$smallad_message = $this->email_form->get_value('sender_message');
		$smallad_recipient_email = $this->smallad->get_custom_author_email();

		$smallad_email = new Mail();
		$smallad_email->set_sender(MailServiceConfig::load()->get_default_mail_sender(), $this->lang['smallads.module.title']);
		$smallad_email->set_reply_to($smallad_sender_email, $smallad_sender_name);
		$smallad_email->set_subject($smallad_subject);
		$smallad_email->set_content(TextHelper::html_entity_decode($smallad_message));
		$smallad_email->add_recipient($smallad_recipient_email);

		$send_email = AppContext::get_mail_service();

		return $send_email->try_to_send($smallad_email);
	}

	private function check_authorizations()
	{
		$smallad = $this->get_smallad();

		$current_user = AppContext::get_current_user();
		$not_authorized = !SmalladsAuthorizationsService::check_authorizations($smallad->get_id_category())->moderation() && !SmalladsAuthorizationsService::check_authorizations($smallad->get_id_category())->write() && (!SmalladsAuthorizationsService::check_authorizations($smallad->get_id_category())->contribution() || $smallad->get_author_user()->get_id() != $current_user->get_id());

		switch ($smallad->get_publication_state())
		{
			case Smallad::PUBLISHED_NOW:
				if (!SmalladsAuthorizationsService::check_authorizations($smallad->get_id_category())->read())
				{
					$error_controller = PHPBoostErrors::user_not_authorized();
		   			DispatchManager::redirect($error_controller);
				}
			break;
			case Smallad::NOT_PUBLISHED:
				if ($not_authorized || ($current_user->get_id() == User::VISITOR_LEVEL))
				{
					$error_controller = PHPBoostErrors::user_not_authorized();
		   			DispatchManager::redirect($error_controller);
				}
			break;
			case Smallad::PUBLICATION_DATE:
				if (!$smallad->is_published() && ($not_authorized || ($current_user->get_id() == User::VISITOR_LEVEL)))
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
		$response = new SiteDisplayResponse($this->tpl);

		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->smallad->get_title(), $this->lang['smallads.module.title']);
		$graphical_environment->get_seo_meta_data()->set_description($this->smallad->get_description());
		$graphical_environment->get_seo_meta_data()->set_canonical_url(SmalladsUrlBuilder::display_item($this->category->get_id(), $this->category->get_rewrited_name(), $this->smallad->get_id(), $this->smallad->get_rewrited_title(), AppContext::get_request()->get_getint('page', 1)));

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['smallads.module.title'], SmalladsUrlBuilder::home());

		$categories = array_reverse(SmalladsService::get_categories_manager()->get_parents($this->smallad->get_id_category(), true));
		foreach ($categories as $id => $category)
		{
			if ($category->get_id() != Category::ROOT_CATEGORY)
				$breadcrumb->add($category->get_name(), SmalladsUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name()));
		}
		$breadcrumb->add($this->smallad->get_title(), SmalladsUrlBuilder::display_item($category->get_id(), $category->get_rewrited_name(), $this->smallad->get_id(), $this->smallad->get_rewrited_title()));

		return $response;
	}
}
?>
