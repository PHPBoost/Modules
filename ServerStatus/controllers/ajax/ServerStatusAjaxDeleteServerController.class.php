<?php
/*##################################################
 *                          ServerStatusAjaxDeleteServerController.class.php
 *                            -------------------
 *   begin                : August 8, 2013
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

class ServerStatusAjaxDeleteServerController extends AbstractController
{
	private $view;
	
	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		$this->build_view($request);
		return new SiteNodisplayResponse($this->view);
	}
	
	private function build_view(HTTPRequestCustom $request)
	{
		AppContext::get_session()->csrf_post_protect();
		
		$id = $request->get_int('id', 0);
		
		$result = -1;
		if (!empty($id))
		{
			$config = ServerStatusConfig::load();
			$servers = $config->get_servers_list();
			if (isset($servers[$id]))
			{
				unset($servers[$id]);
				$new_servers_list = array();
				
				$position = 0;
				foreach ($servers as $key => $server)
				{
					$position++;
					$new_servers_list[$position] = $server;
				}
				
				$config->set_servers_list($new_servers_list);
				
				ServerStatusConfig::save();
				$result = $position;
			}
		}
		
		$this->view->put('RESULT', $result);
	}
	
	private function init()
	{
		$this->view = new StringTemplate('{RESULT}');
	}
}
?>
