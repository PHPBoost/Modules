<?php
/*##################################################
 *                         ServerStatusController.class.php
 *                            -------------------
 *   begin                : August 12, 2013
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

class ServerStatusController extends ModuleController
{
	private $lang;
	private $view;
	
	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		$this->build_view();
		
		return $this->generate_response();
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('common', 'ServerStatus');
		$this->view = new FileTemplate('ServerStatus/ServerStatusController.tpl');
		$this->view->add_lang($this->lang);
	}
	
	public function build_view()
	{
		$number_servers = 0;
		
		ServerStatusService::check_servers_status();
		$config = ServerStatusConfig::load();
		$servers_list = $config->get_servers_list();
		
		foreach ($servers_list as $id => $server)
		{
			if ($server->is_authorized() && $server->is_displayed())
			{
				$this->view->assign_block_vars('servers', array(
					'VIEW' => $server->get_view()
				));
				$number_servers++;
			}
		}
		
		$this->view->put('C_SERVERS', $number_servers);
	}
	
	private function generate_response()
	{
		$response = new SiteDisplayResponse($this->view);
		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->lang['module_title']);
		
		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['module_title'], ServerStatusUrlBuilder::home());
		$graphical_environment->get_seo_meta_data()->set_canonical_url(ServerStatusUrlBuilder::home());
		
		return $response;
	}
}
?>
