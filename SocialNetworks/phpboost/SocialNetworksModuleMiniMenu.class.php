<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 15
 * @since       PHPBoost 4.1 - 2014 08 24
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class SocialNetworksModuleMiniMenu extends ModuleMiniMenu
{
	public function get_default_block(): int
	{
		return self::BLOCK_POSITION__RIGHT;
	}

	public function get_menu_id(): string
	{
		return 'module-mini-social-networks';
	}

	public function get_menu_title(): string
	{
		return LangLoader::get_message('common.share', 'common-lang');
	}

	public function get_formated_title(): string
	{
		return LangLoader::get_message('sn.module.title', 'common', 'SocialNetworks');
	}

	public function get_menu_content(): string
	{
		$tpl = new FileTemplate('SocialNetworks/SocialNetworksModuleMiniMenu.tpl');

		$tpl->put('NETWORK', ContentSharingActionsMenuService::display_sharing_elements());

		return $tpl->render();
	}
}
?>
