<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 16
 * @since       PHPBoost 4.1 - 2014 09 24
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class TeamspeakModuleMiniMenu extends ModuleMiniMenu
{
	public function get_default_block()
	{
		return self::BLOCK_POSITION__LEFT;
	}

	public function get_formated_title()
	{
		return LangLoader::get_message('ts.module.title', 'common', 'teamspeak');
	}

	public function display($view = false)
	{
		$config = TeamspeakConfig::load();
		$ts_ip = $config->get_ip();

		if (!empty($ts_ip))
		{
			if (!Url::is_current_url('/teamspeak/') && TeamspeakAuthorizationsService::check_authorizations()->read())
			{
				$view = new FileTemplate('teamspeak/TeamspeakModuleMiniMenu.tpl');
				$view->add_lang(LangLoader::get_all_langs('teamspeak'));

				MenuService::assign_positions_conditions($view, $this->get_block());

				$view->put_all(array(
					'C_REFRESH_ENABLED' => $config->get_refresh_delay(),
					'REFRESH_DELAY'     => $config->get_refresh_delay() * 60000
				));

				return $view->render();
			}
		}
		else if (AppContext::get_current_user()->check_level(User::ADMINISTRATOR_LEVEL))
		{
			return MessageHelper::display(LangLoader::get_message('ts.warning.ip', 'common', 'teamspeak'), MessageHelper::WARNING)->render();
		}

		return '';
	}
}
?>
