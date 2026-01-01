<?php

/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 10 17
 * @since       PHPBoost 6.0 - 2022 10 25
 */

class BroadcastModuleMiniMenu extends ModuleMiniMenu
{
	public function get_default_block()
	{
		return self::BLOCK_POSITION__LEFT;
	}

	public function get_menu_id()
	{
		return 'module-mini-broadcast';
	}

	public function get_menu_title()
	{
		return LangLoader::get_message('broadcast.module.title', 'common', 'broadcast');
	}

	public function is_displayed()
	{
		return CategoriesAuthorizationsService::check_authorizations(Category::ROOT_CATEGORY, 'broadcast')->read();
	}

	public function get_menu_content()
	{
		if (CategoriesAuthorizationsService::check_authorizations()->read())
		{
			$view = new FileTemplate('broadcast/BroadcastModuleMiniMenu.tpl');
			$view->add_lang(LangLoader::get_all_langs('broadcast'));
			MenuService::assign_positions_conditions($view, $this->get_block());

			$config = BroadcastConfig::load();

			$view->put_all(array(
				'C_HAS_LOGO'  => !empty($config->get_broadcast_logo()),
				'C_ITEMS'     => !empty(BroadcastCache::load()->get_items()),

				'MODULE_ID' => $this->get_menu_id(),
				'TITLE'     => $config->get_broadcast_name(),
				'WIDTH'     => $config->get_player_width(),
				'HEIGHT'    => $config->get_player_height(),
				'WIDGET'    => FormatingHelper::second_parse($config->get_broadcast_widget()),

				'U_CONFIG' => BroadcastUrlBuilder::configuration()->rel(),
				'U_PROG'   => BroadcastUrlBuilder::home()->rel(),
				'U_LOGO'   => $config->get_broadcast_logo()->rel(),
				'U_STREAM' => $config->get_broadcast_url()->rel()
			));

			return $view->render();
		}
	}

	public function display()
	{
		if ($this->is_displayed())
		{
			return $this->get_menu_content();
		}
		return '';
	}
}
?>
