<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2021 12 16
 * @since       PHPBoost 4.0 - 2013 08 04
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class ServerStatusModuleMiniMenu extends ModuleMiniMenu
{
	public function get_default_block()
	{
		return self::BLOCK_POSITION__LEFT;
	}

	public function get_formated_title()
	{
		return LangLoader::get_message('server.module.title', 'common', 'ServerStatus');
	}

	public function display($view = false)
	{
		if (!Url::is_current_url('/ServerStatus/') && ServerStatusAuthorizationsService::check_authorizations()->read())
		{
			$lang = LangLoader::get_all_langs('ServerStatus');

			$view = new FileTemplate('ServerStatus/ServerStatusModuleMiniMenu.tpl');
			$view->add_lang($lang);
			MenuService::assign_positions_conditions($view, $this->get_block());

			ServerStatusService::check_servers_status();

			$config = ServerStatusConfig::load();

			$i = $servers_number = 0;
			foreach ($config->get_servers_list() as $id => $server)
			{
				if ($server->is_authorized() && $server->is_displayed())
				{
					$view->assign_block_vars('servers', array(
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
					$servers_number++;
				}
			}

			$view->put_all(array(
				'C_ADDRESS_DISPLAYED' => $config->is_address_displayed(),
				'C_SEVERAL_SERVERS' => $servers_number > 1,
				'C_SERVERS' => $servers_number
			));

			return $view->render();
		}
		return '';
	}
}
?>
