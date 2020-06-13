<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2015 04 13
 * @since       PHPBoost 4.1 - 2014 09 24
*/

class TeamspeakModuleMiniMenu extends ModuleMiniMenu
{
	public function get_default_block()
	{
		return self::BLOCK_POSITION__LEFT;
	}

	public function display($tpl = false)
	{
		$config = TeamspeakConfig::load();
		$ts_ip = $config->get_ip();

		if (!empty($ts_ip))
		{
			if (!Url::is_current_url('/teamspeak/') && TeamspeakAuthorizationsService::check_authorizations()->read())
			{
				$tpl = new FileTemplate('teamspeak/TeamspeakModuleMiniMenu.tpl');
				$tpl->add_lang(LangLoader::get('common', 'teamspeak'));

				MenuService::assign_positions_conditions($tpl, $this->get_block());

				$tpl->put_all(array(
					'C_REFRESH_ENABLED' => $config->get_refresh_delay(),
					'REFRESH_DELAY' => $config->get_refresh_delay() * 60000
				));

				return $tpl->render();
			}
		}
		else if (AppContext::get_current_user()->check_level(User::ADMIN_LEVEL))
		{
			return MessageHelper::display(LangLoader::get_message('ts_ip_missing', 'common', 'teamspeak'), MessageHelper::WARNING)->render();
		}

		return '';
	}
}
?>
