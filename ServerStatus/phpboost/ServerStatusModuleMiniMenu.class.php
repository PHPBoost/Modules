<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2016 02 11
 * @since   	PHPBoost 4.0 - 2013 08 04
*/

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
