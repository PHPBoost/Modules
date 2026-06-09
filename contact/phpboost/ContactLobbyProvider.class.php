<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 6.1 - 2026 03 21
*/

class ContactLobbyProvider extends DefaultModuleLobbyProvider
{
	public function get_module_id(): string
	{
		return 'contact';
	}

	/**
	 * Contact has no categories and no items limit / chars limit fields.
	 */
	public function has_categories(): bool
	{
		return false;
	}

	/**
	 * Contact only needs the enable/disable toggle — no extra config fields.
	 *
	 * @param  LobbyModule $module
	 * @return AbstractFormField[]
	 */
	public function get_config_fields(LobbyModule $module): array
	{
		return [];
	}

	public function get_fields_visibility(LobbyModule $module): array
	{
		return [];
	}

	public function save(HTMLForm $form, LobbyModule $module): void
	{
		// Nothing extra to persist for contact beyond the displayed flag
	}

	/**
	 * Builds and returns the contact form view.
	 * The form processing (submit, mail sending) is performed inline here
	 * so that the lobby home controller handles it transparently.
	 */
	public function get_view(): FileTemplate
	{
		$module_id      = $this->get_module_id();
		$contact_config = ContactConfig::load();

		$view = $this->get_lobby_template('ContactLobbyProvider.tpl');
		$view->add_lang(array_merge(LangLoader::get_all_langs(), LangLoader::get_all_langs('lobby'), LangLoader::get_all_langs($module_id)));

		$view->put_all([
			'MODULE_POSITION' => LobbyConfig::load()->get_module_position_by_id($module_id),
			'C_MAP_ENABLED'   => $contact_config->is_map_enabled(),
			'C_MAP_TOP'       => $contact_config->is_map_enabled() && $contact_config->is_map_top(),
			'C_MAP_BOTTOM'    => $contact_config->is_map_enabled() && $contact_config->is_map_bottom(),
			'L_MODULE_TITLE'  => ModulesManager::get_module($module_id)->get_configuration()->get_name(),
		]);

		// Build form
		$form     = new HTMLForm('contact_lobby');
		$fieldset = new FormFieldsetHTML('send_a_mail', $contact_config->get_title());
		$form->add_fieldset($fieldset);

		foreach ($contact_config->get_fields() as $id => $properties)
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

		$submit_button = new FormButtonDefaultSubmit();
		$form->add_button($submit_button);

		if ($submit_button->has_been_submited() && $form->validate())
		{
			if ($this->send_contact_mail($form))
			{
				$msg = LangLoader::get_message('contact.message.success.email', 'common', 'contact');
				if ($contact_config->is_sender_acknowledgment_enabled())
				{
					$msg .= ' ' . LangLoader::get_message('contact.message.acknowledgment', 'common', 'contact');
				}
				$view->put('MESSAGE_HELPER', MessageHelper::display($msg, MessageHelper::SUCCESS));
				$view->put('C_MAIL_SENT', true);
			}
			else
			{
				$view->put('MESSAGE_HELPER', MessageHelper::display(
					LangLoader::get_message('contact.message.error.email', 'common', 'contact'),
					MessageHelper::ERROR, 5
				));
			}
		}

		if ($contact_config->is_map_enabled())
		{
			$map = new GoogleMapsDisplayMap($contact_config->get_map_markers());
			$view->put('MAP', $map->display());
		}

		$view->put('CONTACT_FORM', $form->display());

		return $view;
	}

    private function send_contact_mail($form)
	{
        $lang = LangLoader::get_module_langs('contact');
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
			$subject = $form->get_value('f_subject');
		else
			$subject = $form->get_value('f_subject')->get_raw_value();

		$display_message_title = false;
		if ($contact_config->is_tracking_number_enabled())
		{
			$now = new Date();

			$tracking_number = $contact_config->get_last_tracking_number();
			$tracking_number++;
			$message .= $lang['contact.tracking.number'] . ' : ' . ($contact_config->is_date_in_tracking_number_enabled() ? $now->get_year() . $now->get_month() . $now->get_day() . '-' : '') . $tracking_number . ' ';
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
					$value = ContactFieldsService::get_value($form, $field);
						$message .= $field->get_name() . ': ' . $value . '';
				} catch(Exception $e) {
					throw new Exception($e->getMessage());
				}

				$display_message_title = true;
			}
		}

		if ($display_message_title)
			$message .= LangLoader::get_message('contact.form.message', 'common', 'contact') . ':';

		$message .= $form->get_value('f_message');

		$mail = new Mail();
		$mail->set_sender(MailServiceConfig::load()->get_default_mail_sender(), $lang['contact.module.title']);
		$mail->set_reply_to($form->get_value('f_sender_mail'), $current_user->get_display_name());
		$mail->set_subject($subject);
		$mail->set_content(TextHelper::html_entity_decode($message));

		if ($recipients_field->is_displayed())
		{
			if (in_array($recipients_field->get_field_type(), ['ContactSimpleSelectField', 'ContactSimpleChoiceField']))
				$recipients_mails = explode(';', $recipients[$form->get_value('f_recipients')->get_raw_value()]['email']);
			else
			{
				$selected_recipients = $form->get_value('f_recipients');
				$recipients_mails = [];
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
			$recipient = $form->get_value('f_subject')->get_raw_value() ? $subjects[$form->get_value('f_subject')->get_raw_value()]['recipient'] : MailServiceConfig::load()->get_default_mail_sender() . ';' . Mail::SENDER_ADMIN;
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
			$acknowledgment->set_subject('[' . $lang['contact.acknowledgment.title'] . '] ' . $subject);
			$acknowledgment->set_content($lang['contact.acknowledgment.correct'] . $message);
			$acknowledgment->add_recipient($form->get_value('f_sender_mail'));

			return $mail_service->try_to_send($mail) && $mail_service->try_to_send($acknowledgment);
		}

		return $mail_service->try_to_send($mail);
	}
}
?>
