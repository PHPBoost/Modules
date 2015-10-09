<?php
/*##################################################
 *                       AdminServerStatusServersListController.class.php
 *                            -------------------
 *   begin                : August 4, 2013
 *   copyright            : (C) 2013 Julien BRISWALTER
 *   email                : julienseth78@phpboost.com
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

class AdminServerStatusServersListController extends AdminController
{
	private $lang;
	private $view;
	private $config;
	
	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		
		if ($request->get_value('regenerate_status', false))
		{
			ServerStatusService::check_servers_status(true);
			$this->view->put('MSG', MessageHelper::display(LangLoader::get_message('process.success', 'status-messages-common'), MessageHelper::SUCCESS, 5));
		}
		
		$this->update_servers($request);
		
		$servers_number = 0;
		foreach ($this->config->get_servers_list() as $id => $server)
		{
			$this->view->assign_block_vars('servers', array(
				'C_ICON' => $server->has_medium_icon(),
				'C_DISPLAY' => $server->is_displayed(),
				'ID' => $id,
				'NAME' => $server->get_name(),
				'ICON' => $server->get_medium_icon(),
				'U_EDIT' => ServerStatusUrlBuilder::edit_server($id)->rel()
			));
			$servers_number++;
		}
		
		$this->view->put_all(array(
			'C_SERVERS' => $servers_number,
			'C_MORE_THAN_ONE_SERVER' => $servers_number > 1
		));
		
		return new AdminServerStatusDisplayResponse($this->view, $this->lang['admin.config.servers.management']);
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('common', 'ServerStatus');
		$this->view = new FileTemplate('ServerStatus/AdminServerStatusServersListController.tpl');
		$this->view->add_lang($this->lang);
		$this->config = ServerStatusConfig::load();
	}
	
	private function update_servers(HTTPRequestCustom $request)
	{
		if ($request->get_value('submit', false))
		{
			$this->update_position($request);
			$this->view->put('MSG', MessageHelper::display(LangLoader::get_message('message.success.position.update', 'status-messages-common'), MessageHelper::SUCCESS, 5));
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
