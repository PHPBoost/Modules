<?php
/*##################################################
 *                          ServerStatusAjaxDeleteServerController.class.php
 *                            -------------------
 *   begin                : August 8, 2013
 *   copyright            : (C) 2013 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
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
	public function execute(HTTPRequestCustom $request)
	{
		$id = $request->get_int('id', 0);
		
		$code = -1;
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
				$code = $position;
			}
		}
		
		return new JSONResponse(array('code' => $code));
	}
}
?>
