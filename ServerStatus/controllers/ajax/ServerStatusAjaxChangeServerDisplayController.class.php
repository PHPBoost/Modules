<?php
/*##################################################
 *                          ServerStatusAjaxChangeServerDisplayController.class.php
 *                            -------------------
 *   begin                : October 8, 2015
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

class ServerStatusAjaxChangeServerDisplayController extends AbstractController
{
	public function execute(HTTPRequestCustom $request)
	{
		$id = $request->get_int('id', 0);
		
		$display = -1;
		if ($id !== 0)
		{
			$config = ServerStatusConfig::load();
			$servers_list = $config->get_servers_list();
			if ($servers_list[$id]->is_displayed())
			{
				$display = 0;
				$servers_list[$id]->not_displayed();
			}
			else
			{
				$display = 1;
				$servers_list[$id]->displayed();
			}
			$config->set_servers_list($servers_list);
			
			ServerStatusConfig::save();
		}
		
		return new JSONResponse(array('id' => $id, 'display' => $display));
	}
}
?>
