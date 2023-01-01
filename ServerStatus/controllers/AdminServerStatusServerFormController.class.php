<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 16
 * @since       PHPBoost 4.0 - 2013 08 04
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminServerStatusServerFormController extends DefaultAdminModuleController
{
	private $server;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init($request);
		$this->build_form();

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			if ($this->save())
				AppContext::get_response()->redirect(ServerStatusUrlBuilder::servers_management());
			else
				$this->view->put('MESSAGE_HELPER', MessageHelper::display($this->lang['server.warning.empty.address'], MessageHelper::ERROR));
		}

		$this->view->put('CONTENT', $this->form->display());

		return new AdminServerStatusDisplayResponse($this->view, !empty($this->id) ? $this->lang['server.edit.item'] : $this->lang['server.add.item']);
	}

	private function init(HTTPRequestCustom $request)
	{
		$this->id = $request->get_getint('id', 0);
	}

	private function build_form()
	{
		$server = $this->get_server();

		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTML('server', !empty($this->id) ? $this->lang['server.edit.item'] : $this->lang['server.add.item']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldTextEditor('name', $this->lang['form.name'], $server->get_name(),
			array('class' => 'text', 'required' => true)
		));

		$fieldset->add_field(new FormFieldRichTextEditor('description', $this->lang['form.description'], $server->get_description(),
			array('rows' => 4, 'cols' => 47)
		));

		$fieldset->add_field(new FormFieldSimpleSelectChoice('address_type', $this->lang['server.address.type'], $server->get_address_type(),
			array(
				new FormFieldSelectChoiceOption($this->lang['server.address.type.dns'], AbstractServerStatusServer::DNS),
				new FormFieldSelectChoiceOption($this->lang['server.address.type.ip'], AbstractServerStatusServer::IP)
			),
			array(
				'events' => array('change' => '
					if (HTMLForms.getField("address_type").getValue() == \'' . AbstractServerStatusServer::DNS . '\') {
						HTMLForms.getField("ip_address").disable();
						HTMLForms.getField("dns_address").enable();
					} else {
						HTMLForms.getField("dns_address").disable();
						HTMLForms.getField("ip_address").enable();
					}'
				)
			)
		));

		$fieldset->add_field(new FormFieldTextEditor('dns_address', $this->lang['server.address.dns'], $server->address_type_is_dns() ? $server->get_address() : '',
			array(
				'required' => true,
				'description' => $this->lang['server.address.dns.clue'],
				'hidden' => !$server->address_type_is_dns()
			)
		));

		$fieldset->add_field(new FormFieldTextEditor('ip_address', $this->lang['server.address.ip'], $server->address_type_is_ip() ? $server->get_address() : '',
			array(
				'required' => true,
				'description' => $this->lang['server.address.ip.clue'],
				'hidden' => !$server->address_type_is_ip()
			),
			array(new FormFieldConstraintRegex('`^((([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5]))$|^((([0-9A-Fa-f]{1,4}:){7}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){6}:[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){5}:([0-9A-Fa-f]{1,4}:)?[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){4}:([0-9A-Fa-f]{1,4}:){0,2}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){3}:([0-9A-Fa-f]{1,4}:){0,3}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){2}:([0-9A-Fa-f]{1,4}:){0,4}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){6}((b((25[0-5])|(1d{2})|(2[0-4]d)|(d{1,2}))b).){3}(b((25[0-5])|(1d{2})|(2[0-4]d)|(d{1,2}))b))|(([0-9A-Fa-f]{1,4}:){0,5}:((b((25[0-5])|(1d{2})|(2[0-4]d)|(d{1,2}))b).){3}(b((25[0-5])|(1d{2})|(2[0-4]d)|(d{1,2}))b))|(::([0-9A-Fa-f]{1,4}:){0,5}((b((25[0-5])|(1d{2})|(2[0-4]d)|(d{1,2}))b).){3}(b((25[0-5])|(1d{2})|(2[0-4]d)|(d{1,2}))b))|([0-9A-Fa-f]{1,4}::([0-9A-Fa-f]{1,4}:){0,5}[0-9A-Fa-f]{1,4})|(::([0-9A-Fa-f]{1,4}:){0,6}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){1,7}:))$`iu'))
		));

		$types_properties = $this->get_types_properties();
		$fieldset->add_field(new FormFieldSimpleSelectChoice('type', $this->lang['server.type'], $server->get_type(), $types_properties['array_select'],
			array('events' => array('change' => $types_properties['events']))
		));

		$fieldset->add_field(new FormFieldFree('preview_icon', $this->lang['server.icon'], '<img id="preview_icon" ' . ($server->has_medium_icon() ? 'src="' . $server->get_medium_icon() . '"' : 'style="display:none"') . ' alt="' . $this->lang['server.icon'] . '" /><span id="preview_icon_none" ' . ($server->has_medium_icon() ? 'style="display:none"' : '') . '>' . $this->lang['common.none.alt'] . '</span>'));

		$fieldset->add_field(new FormFieldTextEditor('port', $this->lang['server.port'], $server->get_port(),
			array(
				'class' => 'text', 'maxlength' => 5, 'size' => 5, 'required' => true,
				'description' => $this->lang['server.port.clue']
			),
			array(new FormFieldConstraintIntegerRange(1, 65535))
		));

		$fieldset->add_field(new FormFieldCheckbox('display', $this->lang['common.display'], $server->is_displayed(),
			array('class' => 'custom-checkbox')
		));

		$fieldset_authorizations = new FormFieldsetHTML('authorizations', $this->lang['form.authorizations']);
		$form->add_fieldset($fieldset_authorizations);


		$auth_settings = new AuthorizationsSettings(array(
			new ActionAuthorization($this->lang['form.authorizations.read'], AbstractServerStatusServer::DISPLAY_SERVER_AUTHORIZATIONS),
		));

		$auth_settings->build_from_auth_array($server->get_authorizations());
		$fieldset_authorizations->add_field(new FormFieldAuthorizationsSetter('authorizations', $auth_settings));

		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());

		$this->form = $form;
	}

	private function get_server()
	{
		if ($this->server === null)
		{
			$servers_list = $this->config->get_servers_list();

			if (!empty($this->id) && isset($servers_list[$this->id]))
			{
				$this->server = $servers_list[$this->id];
			}
			else
			{
				$this->server = new ServerStatusDefaultServer();
				$this->server->set_port($this->server->get_default_port());
			}
		}
		return $this->server;
	}

	private function get_types_properties()
	{
		$array_select = array(new FormFieldSelectChoiceOption('', 'ServerStatusDefaultServer'));
		$events = 'if (HTMLForms.getField("type").getValue() == "ServerStatusDefaultServer") {
				HTMLForms.getField("port").setValue(\'1\');
				jQuery(\'#preview_icon\').attr(\'src\', \'\');
				jQuery(\'#preview_icon\').hide();
				jQuery(\'#preview_icon_none\').show();
			}';

		$types = ServerStatusService::get_types();
		$types_number = count($types);

		foreach ($types as $type_id => $type)
		{
			$array_options = array();
			foreach ($type as $id => $options)
			{
				if ($types_number > 1)
					$array_options[] = new FormFieldSelectChoiceOption($options['name'], $id);
				else
					$array_select[] = new FormFieldSelectChoiceOption($options['name'], $id);

				$events .= 'if (HTMLForms.getField("type").getValue() == "' . $id . '") {
					HTMLForms.getField("port").setValue(\'' . $options['default_port'] . '\');
					' . ($options['icon'] ? 'jQuery(\'#preview_icon\').attr(\'src\', \'' . $options['icon'] . '\');
					jQuery(\'#preview_icon\').show();
					jQuery(\'#preview_icon_none\').hide();' : 'jQuery(\'#preview_icon\').attr(\'src\', \'\');
					jQuery(\'#preview_icon\').hide();
					jQuery(\'#preview_icon_none\').show();') . '
				}';
			}

			if ($types_number > 1)
				$array_select[] = new FormFieldSelectChoiceGroupOption($this->lang['server.' . $type_id], $array_options);
		}

		return array('array_select' => $array_select, 'events' => $events);
	}

	private function save()
	{
		$address_type = $this->form->get_value('address_type')->get_raw_value();
		$address = $this->form->get_value($address_type . '_address');

		if (empty($address))
			return false;

		$server = $this->get_server();

		$type = $this->form->get_value('type')->get_raw_value();
		if ($type != $server->get_type())
			$server = new $type();

		$server->set_name($this->form->get_value('name'));
		$server->set_rewrited_name($this->form->get_value('name'));
		$server->set_description($this->form->get_value('description'));

		$address_type = $this->form->get_value('address_type')->get_raw_value();
		$server->set_address_type($address_type);
		$server->set_address($address);
		$server->set_port($this->form->get_value('port'));

		if ((bool)$this->form->get_value('display'))
			$server->displayed();
		else
			$server->not_displayed();

		$server->set_authorizations($this->form->get_value('authorizations')->build_auth_array());
		$server->check_status(true);

		$servers_list = $this->config->get_servers_list();
		$servers_list[!empty($this->id) ? $this->id : sizeof($servers_list) + 1] = $server;

		$this->config->set_servers_list($servers_list);

		ServerStatusConfig::save();
		return true;
	}
}
?>
