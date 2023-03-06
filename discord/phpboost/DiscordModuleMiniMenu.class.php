<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2023 03 06
 * @since       PHPBoost 6.0 - 2023 03 05
*/

class DiscordModuleMiniMenu extends ModuleMiniMenu
{
	public function get_default_block()
	{
		return self::BLOCK_POSITION__LEFT;
	}

	public function admin_display()
	{
		return '';
	}

	public function get_menu_id()
	{
		return 'module-mini-discord';
	}

	public function get_menu_title()
	{
		return LangLoader::get_message('discord.module.title', 'common', 'discord');
	}

	public function is_displayed()
	{
		return DiscordAuthorizationsService::check_authorizations()->read();
	}

	public function get_menu_content()
	{
		$lang = LangLoader::get_all_langs('discord');
		$view = new FileTemplate('discord/DiscordModuleMiniMenu.tpl');
		$view->add_lang($lang);
		MenuService::assign_positions_conditions($view, $this->get_block());
		Menu::assign_common_template_variables($view);

		$discord_config = DiscordConfig::load();

		$view->put_all(array(
            'C_DISCORD_ID' => !empty($discord_config->get_discord_id()),
            'DISCORD_ID' => $discord_config->get_discord_id()
        ));

		return $view->render();
	}

	public function display()
	{
		if ($this->is_displayed())
		{
            return $this->get_menu_content();
		}
	}
}
?>
