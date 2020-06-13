<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 06 08
 * @since       PHPBoost 5.0 - 2016 01 02
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class HomeLandingHomeController extends ModuleController
{
	private $view;
	private $lang;
	private $form;
	private $submit_button;

	/**
	 * @var HomeLandingConfig
	 */
	private $config;

	/**
	 * @var HomeLandingModulesList
	 */
	private $modules;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();

		$this->build_view();

		return $this->generate_response();
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'HomeLanding');
		$this->view = new FileTemplate('HomeLanding/home.tpl');
		$this->view->add_lang($this->lang);
		$this->config = HomeLandingConfig::load();
		$this->modules = HomeLandingModulesList::load();

		$columns_disabled = ThemesManager::get_theme(AppContext::get_current_user()->get_theme())->get_columns_disabled();
		$columns_disabled->set_disable_left_columns($this->config->get_left_columns());
		$columns_disabled->set_disable_right_columns($this->config->get_right_columns());
		$columns_disabled->set_disable_top_central($this->config->get_top_central());
		$columns_disabled->set_disable_bottom_central($this->config->get_bottom_central());
		$columns_disabled->set_disable_top_footer($this->config->get_top_footer());
	}

	private function build_view()
	{
		//Config HomeLanding title & edito
		$this->view->put_all(array(
			'MODULE_TITLE' => $this->config->get_module_title(),
			'C_EDITO_ENABLED' => $this->modules[HomeLandingConfig::MODULE_EDITO]->is_displayed(),
			'EDITO' => FormatingHelper::second_parse($this->config->get_edito()),
			'EDITO_POSITION' => $this->config->get_module_position_by_id(HomeLandingConfig::MODULE_EDITO),
		));

		if ($this->modules[HomeLandingConfig::MODULE_ANCHORS_MENU]->is_displayed())
			$this->view->put('ANCHORS_MENU', HomeLandingAnchorsMenu::get_anchors_menu_view());

		if ($this->modules[HomeLandingConfig::MODULE_CAROUSEL]->is_displayed())
			$this->view->put('CAROUSEL', HomeLandingCarousel::get_carousel_view());

		if ($this->modules[HomeLandingConfig::MODULE_LASTCOMS]->is_displayed())
			$this->view->put('LASTCOMS', HomeLandingLastcoms::get_lastcoms_view());

		if ($this->modules[HomeLandingConfig::MODULE_ARTICLES]->is_displayed() && CategoriesAuthorizationsService::check_authorizations(Category::ROOT_CATEGORY, HomeLandingConfig::MODULE_ARTICLES)->read())
			$this->view->put('ARTICLES', HomeLandingDisplayItems::build_view(HomeLandingConfig::MODULE_ARTICLES));

		if ($this->modules[HomeLandingConfig::MODULE_ARTICLES_CATEGORY]->is_displayed() && CategoriesAuthorizationsService::check_authorizations($this->modules[HomeLandingConfig::MODULE_ARTICLES_CATEGORY]->get_id_category(), HomeLandingConfig::MODULE_ARTICLES)->read())
			$this->view->put('ARTICLES_CAT', HomeLandingDisplayItems::build_view(HomeLandingConfig::MODULE_ARTICLES, HomeLandingConfig::MODULE_ARTICLES_CATEGORY));

		if ($this->modules[HomeLandingConfig::MODULE_CONTACT]->is_displayed() && ContactAuthorizationsService::check_authorizations()->read())
			$this->build_contact_view();

		if ($this->modules[HomeLandingConfig::MODULE_CALENDAR]->is_displayed() && CategoriesAuthorizationsService::check_authorizations(Category::ROOT_CATEGORY, HomeLandingConfig::MODULE_CALENDAR)->read())
			$this->view->put('CALENDAR', HomeLandingCalendar::get_calendar_view());

		if ($this->modules[HomeLandingConfig::MODULE_DOWNLOAD]->is_displayed() && CategoriesAuthorizationsService::check_authorizations(Category::ROOT_CATEGORY, HomeLandingConfig::MODULE_DOWNLOAD)->read())
			$this->view->put('DOWNLOAD', HomeLandingDownload::get_download_view());

		if ($this->modules[HomeLandingConfig::MODULE_DOWNLOAD_CATEGORY]->is_displayed() && CategoriesAuthorizationsService::check_authorizations($this->modules[HomeLandingConfig::MODULE_DOWNLOAD_CATEGORY]->get_id_category(), HomeLandingConfig::MODULE_DOWNLOAD)->read())
			$this->view->put('DOWNLOAD_CAT', HomeLandingDownload::get_download_cat_view());

		if ($this->modules[HomeLandingConfig::MODULE_FORUM]->is_displayed() && ForumAuthorizationsService::check_authorizations()->read())
			$this->view->put('FORUM', HomeLandingForum::get_forum_view());

		if ($this->modules[HomeLandingConfig::MODULE_GALLERY]->is_displayed() && CategoriesAuthorizationsService::check_authorizations(Category::ROOT_CATEGORY, HomeLandingConfig::MODULE_GALLERY)->read())
			$this->view->put('GALLERY', HomeLandingGallery::get_gallery_view());

		if ($this->modules[HomeLandingConfig::MODULE_GUESTBOOK]->is_displayed() && GuestbookAuthorizationsService::check_authorizations()->read())
			$this->view->put('GUESTBOOK', HomeLandingGuestbook::get_guestbook_view());

		if ($this->modules[HomeLandingConfig::MODULE_MEDIA]->is_displayed() && CategoriesAuthorizationsService::check_authorizations(Category::ROOT_CATEGORY, HomeLandingConfig::MODULE_MEDIA)->read())
			$this->view->put('MEDIA', HomeLandingMedia::get_media_view());

		if ($this->modules[HomeLandingConfig::MODULE_NEWS]->is_displayed() && CategoriesAuthorizationsService::check_authorizations(Category::ROOT_CATEGORY, HomeLandingConfig::MODULE_NEWS)->read())
			$this->view->put('NEWS', HomeLandingNews::get_news_view());

		if ($this->modules[HomeLandingConfig::MODULE_NEWS_CATEGORY]->is_displayed() && CategoriesAuthorizationsService::check_authorizations($this->modules[HomeLandingConfig::MODULE_NEWS_CATEGORY]->get_id_category(), HomeLandingConfig::MODULE_NEWS)->read())
			$this->view->put('NEWS_CAT', HomeLandingNews::get_news_cat_view());

		if ($this->modules[HomeLandingConfig::MODULE_RSS]->is_displayed())
		 	$this->view->put('RSS', HomeLandingRss::get_rss_view());

		if ($this->modules[HomeLandingConfig::MODULE_WEB]->is_displayed() && CategoriesAuthorizationsService::check_authorizations(Category::ROOT_CATEGORY, HomeLandingConfig::MODULE_WEB)->read())
			$this->view->put('WEB', HomeLandingWeb::get_web_view());

		if ($this->modules[HomeLandingConfig::MODULE_WEB_CATEGORY]->is_displayed() && CategoriesAuthorizationsService::check_authorizations($this->modules[HomeLandingConfig::MODULE_WEB_CATEGORY]->get_id_category(), HomeLandingConfig::MODULE_WEB)->read())
			$this->view->put('WEB_CAT', HomeLandingWeb::get_web_cat_view());
	}

	//Contact
	private function build_contact_view()
	{
		$view = new FileTemplate('HomeLanding/pagecontent/contact.tpl');
		$contact_config = ContactConfig::load();
		$view->put_all(array(
			'CONTACT_POSITION' => $this->config->get_module_position_by_id(HomeLandingConfig::MODULE_CONTACT),
			'C_MAP_ENABLED' => $contact_config->is_map_enabled(),
			'C_MAP_TOP' => $contact_config->is_map_enabled() && $contact_config->is_map_top(),
			'C_MAP_BOTTOM' => $contact_config->is_map_enabled() && $contact_config->is_map_bottom(),
		));

		$this->build_contact_form();

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			if ($this->send_contact_mail())
			{
				$view->put('MSG', MessageHelper::display($this->lang['send.email.success'] . (ContactConfig::load()->is_sender_acknowledgment_enabled() ? ' ' . $this->lang['send.email.acknowledgment'] : ''), MessageHelper::SUCCESS));
				$view->put('C_MAIL_SENT', true);
			}
			else
				$view->put('MSG', MessageHelper::display($this->lang['send.email.error'], MessageHelper::ERROR, 5));
		}

		if ($contact_config->is_map_enabled()) {
			$this->build_map_view();
			$displayed_map = $this->map->display();
		} else {
			$displayed_map = '';
		}

		$view->put_all(array(
			'CONTACT_FORM' => $this->form->display(),
			'MAP' => $displayed_map
		));

		$this->view->put('CONTACT', $view);
	}

	public function build_map_view()
	{
		$contact_config = ContactConfig::load();
		$map = new GoogleMapsDisplayMap($contact_config->get_map_markers());
		$this->map = $map;
	}

	private function build_contact_form()
	{
		$contact_config = ContactConfig::load();
		$form = new HTMLForm(__CLASS__, '', false);

		$fieldset = new FormFieldsetHTML('contact', $contact_config->get_title());
		$form->add_fieldset($fieldset);

		foreach($contact_config->get_fields() as $id => $properties)
		{
			$field = new ContactField();
			$field->set_properties($properties);

			if ($field->is_displayed() && $field->is_authorized())
			{
				if ($field->get_field_name() == 'f_sender_mail')
					$field->set_default_value(AppContext::get_current_user()->get_email());
				$field->set_fieldset($fieldset);
				ContactFieldsService::display_field($field);
			}
		}

		$fieldset->add_field(new FormFieldCaptcha('apply_form_captcha'));

		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());

		$this->form = $form;
	}

	private function send_contact_mail()
	{
		$contact_config = ContactConfig::load();
		$message = '';
		$current_user = AppContext::get_current_user();

		$fields = $contact_config->get_fields();
		$recipients_field_id = $contact_config->get_field_id_by_name('f_recipients');
		$recipients_field = new ContactField();
		$recipients_field->set_properties($fields[$recipients_field_id]);
		$recipients = $recipients_field->get_possible_values();
		$recipients['admins']['email'] = implode(';', MailServiceConfig::load()->get_administrators_mails());

		$subject_field_id = $contact_config->get_field_id_by_name('f_subject');
		$subject_field = new ContactField();
		$subject_field->set_properties($fields[$subject_field_id]);
		$subjects = $subject_field->get_possible_values();

		if ($subject_field->get_field_type() == 'ContactShortTextField')
			$subject = $this->form->get_value('f_subject');
		else
			$subject = $this->form->get_value('f_subject')->get_raw_value();

		$display_message_title = false;
		if ($contact_config->is_tracking_number_enabled())
		{
			$now = new Date();

			$tracking_number = $contact_config->get_last_tracking_number();
			$tracking_number++;
			$message .= $this->lang['send.email.tracking.number'] . ' : ' . ($contact_config->is_date_in_tracking_number_enabled() ? $now->get_year() . $now->get_month() . $now->get_day() . '-' : '') . $tracking_number . ' ';
			$contact_config->set_last_tracking_number($tracking_number);
			ContactConfig::save();

			$subject = '[' . $tracking_number . '] ' . $subject;

			$display_message_title = true;
		}

		foreach($contact_config->get_fields() as $id => $properties)
		{
			$field = new ContactField();
			$field->set_properties($properties);

			if ($field->is_displayed() && $field->is_authorized() && $field->is_deletable())
			{
				try{
					$value = ContactFieldsService::get_value($this->form, $field);
						$message .= $field->get_name() . ': ' . $value . '';
				} catch(Exception $e) {
					throw new Exception($e->getMessage());
				}

				$display_message_title = true;
			}
		}

		if ($display_message_title)
			$message .= $this->lang['contact.form.message'] . ':';

		$message .= $this->form->get_value('f_message');

		$mail = new Mail();
		$mail->set_sender(MailServiceConfig::load()->get_default_mail_sender(), $this->lang['module.title']);
		$mail->set_reply_to($this->form->get_value('f_sender_mail'), $current_user->get_display_name());
		$mail->set_subject($subject);
		$mail->set_content(TextHelper::html_entity_decode($message));

		if ($recipients_field->is_displayed())
		{
			if (in_array($recipients_field->get_field_type(), array('ContactSimpleSelectField', 'ContactSimpleChoiceField')))
				$recipients_mails = explode(';', $recipients[$this->form->get_value('f_recipients')->get_raw_value()]['email']);
			else
			{
				$selected_recipients = $this->form->get_value('f_recipients');
				$recipients_mails = array();
				foreach ($selected_recipients as $recipient)
				{
					$mails = explode(';', $recipients[$recipient->get_id()]['email']);
					foreach ($mails as $m)
					{
						$recipients_mails[] = $m;
					}
				}
			}

			foreach ($recipients_mails as $mail_address)
			{
				$mail->add_recipient($mail_address);
			}
		}
		else if ($subject_field->get_field_type() != 'ContactShortTextField')
		{
			$recipient = $this->form->get_value('f_subject')->get_raw_value() ? $subjects[$this->form->get_value('f_subject')->get_raw_value()]['recipient'] : MailServiceConfig::load()->get_default_mail_sender() . ';' . Mail::SENDER_ADMIN;
			$recipients_mails = explode(';', $recipients[$recipient]['email']);
			foreach ($recipients_mails as $mail_address)
			{
				$mail->add_recipient($mail_address);
			}
		}
		else
		{
			$recipients_mails = explode(';', $recipients['admins']['email']);
			foreach ($recipients_mails as $mail_address)
			{
				$mail->add_recipient($mail_address);
			}
		}

		$mail_service = AppContext::get_mail_service();

		if ($contact_config->is_sender_acknowledgment_enabled())
		{
			$acknowledgment = new Mail();
			$acknowledgment->set_sender(MailServiceConfig::load()->get_default_mail_sender(), Mail::SENDER_ADMIN);
			$acknowledgment->set_subject('[' . $this->lang['send.email.acknowledgment.title'] . '] ' . $subject);
			$acknowledgment->set_content($this->lang['send.email.acknowledgment.correct'] . $message);
			$acknowledgment->add_recipient($this->form->get_value('f_sender_mail'));

			return $mail_service->try_to_send($mail) && $mail_service->try_to_send($acknowledgment);
		}

		return $mail_service->try_to_send($mail);
	}

	//Generation
	private function generate_response()
	{
		$response = new SiteDisplayResponse($this->view);
		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->config->get_module_title());
		$graphical_environment->get_seo_meta_data()->set_description(GeneralConfig::load()->get_site_description());
		$graphical_environment->get_seo_meta_data()->set_canonical_url(HomeLandingUrlBuilder::home());

		$graphical_environment->get_seo_meta_data()->set_picture_url(new Url(PATH_TO_ROOT.'/templates/' . AppContext::get_current_user()->get_theme() . '/theme/images/logo.png'));

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->config->get_module_title(), HomeLandingUrlBuilder::home());

		return $response;
	}

	public static function get_view()
	{
		$object = new self();
		$object->init();
		$object->build_view();
		return $object->view;
	}
}
?>
