<?php
/**
 * @copyright   &copy; 2005-2024 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 10 25
 * @since       PHPBoost 6.0 - 2022 10 25
 */

class AdminBroadcastConfigController extends DefaultAdminModuleController
{
	public function execute(HTTPRequestCustom $request)
	{
		$this->build_form();

		$this->view = new StringTemplate('# INCLUDE MESSAGE_HELPER # # INCLUDE CONTENT #');
		$this->view->add_lang($this->lang);

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			$this->form->get_field_by_id('broadcast_url')->set_hidden($this->config->get_player_type() === BroadcastConfig::BROADCAST_WIDGET);
			$this->form->get_field_by_id('broadcast_widget')->set_hidden($this->config->get_player_type() === BroadcastConfig::BROADCAST_URL);
			$this->view->put('MESSAGE_HELPER', MessageHelper::display($this->lang['warning.success.config'], MessageHelper::SUCCESS, 5));
		}

		$this->view->put('CONTENT', $this->form->display());

		return new DefaultAdminDisplayResponse($this->view);
	}

	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTML('mini_configuration', $this->lang['broadcast.mini.config']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldTextEditor('broadcast_name', $this->lang['broadcast.name'], $this->config->get_broadcast_name(),
			array('class' => 'top-field third-field')
		));

		$fieldset->add_field(new FormFieldUploadPictureFile('broadcast_logo', $this->lang['broadcast.img'], $this->config->get_broadcast_logo()->relative(),
			array('class' => 'top-field third-field')
		));
		
		$fieldset->add_field(new FormFieldSpacer('broadcast_details', ''));

		$fieldset->add_field(new FormFieldNumberEditor('player_width', $this->lang['broadcast.width'], $this->config->get_player_width(),
			array('min' => '0')
		));

		$fieldset->add_field(new FormFieldNumberEditor('player_height', $this->lang['broadcast.height'], $this->config->get_player_height(),
			array('min' => '0')
		));

		$fieldset->add_field(new FormFieldSimpleSelectChoice('player_type', $this->lang['broadcast.type'], $this->config->get_player_type(),
			array(
				new FormFieldSelectChoiceOption($this->lang['broadcast.type.url'], BroadcastConfig::BROADCAST_URL, array('data_option_icon' => 'far fa-file-audio')),
				new FormFieldSelectChoiceOption($this->lang['broadcast.type.widget'], BroadcastConfig::BROADCAST_WIDGET, array('data_option_icon' => 'fa fa-code')),
				new FormFieldSelectChoiceOption($this->lang['broadcast.type.combo'], BroadcastConfig::BROADCAST_COMBO, array('data_option_icon' => 'fa fa-code')),
			),
			array(
				'select_to_list' => true,
				'events' => array('change' => '
					if (HTMLForms.getField("player_type").getValue() == \'' . BroadcastConfig::BROADCAST_URL . '\') {
						HTMLForms.getField("broadcast_url").enable();
						HTMLForms.getField("broadcast_widget").disable();
					} else if (HTMLForms.getField("player_type").getValue() == \'' . BroadcastConfig::BROADCAST_WIDGET . '\'){
						HTMLForms.getField("broadcast_url").disable();
						HTMLForms.getField("broadcast_widget").enable();
					} else {
						HTMLForms.getField("broadcast_url").enable();
						HTMLForms.getField("broadcast_widget").enable();						
					}'
				)
			)
		));

		$fieldset->add_field(new FormFieldUploadFile('broadcast_url', $this->lang['broadcast.url'], $this->config->get_broadcast_url()->relative(),
			array(
				'class' => 'top-field',
				'hidden' => $this->config->get_player_type() == BroadcastConfig::BROADCAST_WIDGET
			)
		));

		$fieldset->add_field(new FormFieldRichTextEditor('broadcast_widget', $this->lang['broadcast.widget'], $this->config->get_broadcast_widget(),
			array(
				'class' => 'top-field',
				'description' => $this->lang['broadcast.widget.tag'],
				'placeholder' => $this->lang['broadcast.widget.tag'],
				'hidden' => $this->config->get_player_type() == BroadcastConfig::BROADCAST_URL
			)
		));

		$module_fieldset = new FormFieldsetHTML('module_configuration', $this->lang['broadcast.module.config']);
		$form->add_fieldset($module_fieldset);

		$module_fieldset->add_field(new FormFieldSimpleSelectChoice('display_type', $this->lang['form.display.type'], $this->config->get_display_type(),
			array(
				new FormFieldSelectChoiceOption($this->lang['broadcast.display.type.accordion'], BroadcastConfig::ACCORDION_VIEW),
				new FormFieldSelectChoiceOption($this->lang['form.display.type.table'], BroadcastConfig::TABLE_VIEW),
				new FormFieldSelectChoiceOption($this->lang['broadcast.display.type.calendar'], BroadcastConfig::CALENDAR_VIEW),
			)
		));

		$fieldset_authorizations = new FormFieldsetHTML('authorizations_fieldset', $this->lang['form.authorizations'], 
			array('description' => $this->lang['form.authorizations.clue']) 
		);
		$form->add_fieldset($fieldset_authorizations);

		$auth_settings = new AuthorizationsSettings(RootCategory::get_authorizations_settings());
		$auth_settings->build_from_auth_array($this->config->get_authorizations());
		$fieldset_authorizations->add_field(new FormFieldAuthorizationsSetter('authorizations', $auth_settings));

		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());

		$this->form = $form;
	}

	private function save()
	{
		$this->config->set_broadcast_name($this->form->get_value('broadcast_name'));
		$this->config->set_broadcast_logo($this->form->get_value('broadcast_logo'));
		$this->config->set_player_type($this->form->get_value('player_type')->get_raw_value());

		if ($this->form->get_value('player_type')->get_raw_value() == BroadcastConfig::BROADCAST_URL)
			$this->config->set_broadcast_url($this->form->get_value('broadcast_url'));
		else if ($this->form->get_value('player_type')->get_raw_value() == BroadcastConfig::BROADCAST_WIDGET)
			$this->config->set_broadcast_widget($this->form->get_value('broadcast_widget'));
		else
		{
			$this->config->set_broadcast_url($this->form->get_value('broadcast_url'));
			$this->config->set_broadcast_widget($this->form->get_value('broadcast_widget'));			
		}
			
		$this->config->set_player_width($this->form->get_value('player_width'));
		$this->config->set_player_height($this->form->get_value('player_height'));
		$this->config->set_display_type($this->form->get_value('display_type')->get_raw_value());
		$this->config->set_authorizations($this->form->get_value('authorizations')->build_auth_array());
		BroadcastConfig::save();
		CategoriesService::get_categories_manager()->regenerate_cache();

		HooksService::execute_hook_action('edit_config', self::$module_id, array('title' => StringVars::replace_vars($this->lang['form.module.title'], array('module_name' => self::get_module_configuration()->get_name())), 'url' => ModulesUrlBuilder::configuration()->rel()));
	}
}
?>
