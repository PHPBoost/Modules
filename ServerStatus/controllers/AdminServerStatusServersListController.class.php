<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 16
 * @since       PHPBoost 4.0 - 2013 08 04
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminServerStatusServersListController extends DefaultAdminModuleController
{
	protected function get_template_to_use()
	{
		return new FileTemplate('ServerStatus/AdminServerStatusServersListController.tpl');
	}

	public function execute(HTTPRequestCustom $request)
	{
		if ($request->get_value('regenerate_status', false))
		{
			ServerStatusService::check_servers_status(true);
			$this->view->put('MESSAGE_HELPER', MessageHelper::display($this->lang['warning.process.success'], MessageHelper::SUCCESS, 5));
		}

		$this->update_servers($request);

		$servers_number = 0;
		foreach ($this->config->get_servers_list() as $id => $server)
		{
			$this->view->assign_block_vars('servers', array(
				'C_ICON'    => $server->has_medium_icon(),
				'C_DISPLAY' => $server->is_displayed(),
				'ID'        => $id,
				'NAME'      => $server->get_name(),
				'ICON'      => $server->get_medium_icon(),
				'U_EDIT'    => ServerStatusUrlBuilder::edit_server($id)->rel()
			));
			$servers_number++;
		}

		$this->view->put_all(array(
			'C_SERVERS' => $servers_number,
			'C_SEVERAL_SERVERS' => $servers_number > 1
		));

		return new AdminServerStatusDisplayResponse($this->view, $this->lang['server.management']);
	}

	private function update_servers(HTTPRequestCustom $request)
	{
		if ($request->get_value('submit', false))
		{
			$this->update_position($request);
			$this->view->put('MESSAGE_HELPER', MessageHelper::display($this->lang['warning.success.position.update'], MessageHelper::SUCCESS, 5));
		}
	}

	private function update_position(HTTPRequestCustom $request)
	{
		$servers = $this->config->get_servers_list();
		$sorted_servers_list = array();

		$servers_list = json_decode(TextHelper::html_entity_decode($request->get_value('tree')));
		foreach($servers_list as $position => $tree)
		{
			$sorted_servers_list[$position + 1] = $servers[$tree->id];
		}
		$this->config->set_servers_list($sorted_servers_list);

		ServerStatusConfig::save();
	}
}
?>
