<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 15
 * @since       PHPBoost 6.0 - 2023 03 05
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class DiscordModuleMiniMenu extends ModuleMiniMenu
{
	public function get_default_block(): int
	{
		return self::BLOCK_POSITION__LEFT;
	}

	public function admin_display(): string
	{
		return '';
	}

	public function get_menu_id(): string
	{
		return 'module-mini-discord';
	}

	public function get_menu_title(): string
	{
		return LangLoader::get_message('discord.module.title', 'common', 'discord');
	}

	public function is_displayed(): bool
	{
		return DiscordAuthorizationsService::check_authorizations()->read();
	}

	public function get_menu_content(): string
	{
		$lang = LangLoader::get_all_langs('discord');
		$view = new FileTemplate('discord/DiscordModuleMiniMenu.tpl');
		$view->add_lang($lang);
		MenuService::assign_positions_conditions($view, $this->get_block());
		Menu::assign_common_template_variables($view);

		$discord_config = DiscordConfig::load();

		$view->put_all([
            'C_DISCORD_ID' => !empty($discord_config->get_discord_id()),
            'DISCORD_ID' => $discord_config->get_discord_id()
        ]);

		return $view->render();
	}

	public function display(): string
	{
		if ($this->is_displayed())
		{
            return $this->get_menu_content();
		}
		return '';
	}
}
?>
