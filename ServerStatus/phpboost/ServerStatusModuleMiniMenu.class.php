<?php
/*##################################################
 *                          ServerStatusModuleMiniMenu.class.php
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

class ServerStatusModuleMiniMenu extends ModuleMiniMenu
{
	public function get_default_block()
	{
		return self::BLOCK_POSITION__LEFT;
	}
	
	public function display($tpl = false)
	{
		if (!Url::is_current_url('/ServerStatus/') && ServerStatusAuthorizationsService::check_authorizations()->read())
		{
			$lang = LangLoader::get('common', 'ServerStatus');
			$main_lang = LangLoader::get('main');
			
			$tpl = new FileTemplate('ServerStatus/ServerStatusModuleMiniMenu.tpl');
			$tpl->add_lang($lang);
			MenuService::assign_positions_conditions($tpl, $this->get_block());
			
			ServerStatusService::check_servers_status();
			
			$config = ServerStatusConfig::load();
			
			$i = $number_servers = 0;
			foreach ($config->get_servers_list() as $id => $server)
			{
				if ($server->is_authorized() && $server->is_displayed())
				{
					$tpl->assign_block_vars('servers', array(
						'C_NEW_LINE' => $i % 3 == 0,
						'C_END_LINE' => $i % 3 == 2,
						'C_ICON' => $server->has_medium_icon(),
						'C_ONLINE' => $server->is_online(),
						'NAME' => $server->get_name(),
						'ADDRESS' => $server->get_address(),
						'PORT' => $server->get_port(),
						'ICON' => $server->get_medium_icon(),
						'U_DISPLAY_SERVER' => ServerStatusUrlBuilder::home(get_parent_class($server) == 'AbstractServerStatusServer' ? '#' . $server->get_rewrited_name() : $id . '#' . $server->get_rewrited_name())->rel()
					));
					$i++;
					$number_servers++;
				}
			}
			
			$tpl->put_all(array(
				'C_ADDRESS_DISPLAYED' => $config->is_address_displayed(),
				'C_MORE_THAN_ONE_SERVER' => $number_servers > 1,
				'C_SERVERS' => $number_servers
			));
			
			return $tpl->render();
		}
		return '';
	}
}
?>
