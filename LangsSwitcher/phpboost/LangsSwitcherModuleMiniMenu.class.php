<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 3.0 - 2012 02 22
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Arnaud GENET <elenwii@phpboost.com>
 * @author      mipel <mipel@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class LangsSwitcherModuleMiniMenu extends ModuleMiniMenu
{
	public function get_default_block()
	{
		return self::BLOCK_POSITION__RIGHT;
	}

	public function admin_display()
	{
		return '';
	}

	public function get_menu_id()
	{
		return 'module-mini-langsswitcher';
	}

	public function get_menu_title()
	{
		return LangLoader::get_message('ls.switch.lang', 'common', 'LangsSwitcher');
	}

	public function is_displayed()
	{
		return count(LangsManager::get_activated_and_authorized_langs_map_sorted_by_localized_name()) > 1;
	}

	public function get_menu_content()
	{
		$user = AppContext::get_current_user();

		$item_id = AppContext::get_request()->get_string('switchlang', '');
		$query_string = preg_replace('`switchlang=[^&]+`u', '', QUERY_STRING);

		$current_url = AppContext::get_request()->get_site_url() . $_SERVER['SCRIPT_NAME'] . '?' . rtrim($query_string, '&');
        $parsed = parse_url($current_url);
        if (isset($parsed['path']) && TextHelper::strpos($parsed['path'], '/modules') !== false)
        {
            $parsed['path'] = str_replace('/modules', '', $parsed['path']);
            $current_url = $this->build_url($parsed); 
        }

		if (!empty($item_id))
		{
			$item = LangsManager::get_lang($item_id);
			if ($item !== null)
			{
				if ($item->is_activated() && $item->check_auth())
				{
					$user->update_lang($item->get_id());
				}
			}
			AppContext::get_response()->redirect(trim($current_url));
		}
		else
			$item = LangsManager::get_lang($user->get_locale());

		$view = new FileTemplate('LangsSwitcher/LangsSwitcherModuleMiniMenu.tpl');
		$view->add_lang(LangLoader::get_all_langs('LangsSwitcher'));
		MenuService::assign_positions_conditions($view, $this->get_block());
		Menu::assign_common_template_variables($view);
		$view->put_all([
			'C_HAS_PICTURE'  => $item->get_configuration()->has_picture(),
			'DEFAULT_ITEM'   => UserAccountsConfig::load()->get_default_lang(),
			'ITEM_NAME'      => $item->get_configuration()->get_name(),
			'U_ITEM_PICTURE' => $item->get_configuration()->get_picture_url()->rel(),
			'U_ITEM'         => $current_url . (strstr($current_url, '?') ? '&' : '?') . 'switchlang='
		]);

		foreach(LangsManager::get_activated_and_authorized_langs_map_sorted_by_localized_name() as $item)
		{
			$view->assign_block_vars('items', [
				'C_SELECTED'     => $user->get_locale() == $item->get_id(),
				'ITEM_NAME'      => $item->get_configuration()->get_name(),
				'U_ITEM_PICTURE' => $item->get_configuration()->get_picture_url()->rel(),
				'ITEM_ID'        => $item->get_id()
			]);
		}

		return $view->render();
	}

    private function build_url($parsed)
    {
        $scheme   = isset($parsed['scheme']) ? $parsed['scheme'] . '://' : '';
        $host     = isset($parsed['host']) ? $parsed['host'] : '';
        $port     = isset($parsed['port']) ? ':' . $parsed['port'] : '';
        $path     = isset($parsed['path']) ? $parsed['path'] : '';
        $query    = isset($parsed['query']) ? '?' . $parsed['query'] : '';
        $fragment = isset($parsed['fragment']) ? '#' . $parsed['fragment'] : '';
        return "$scheme$host$port$path$query$fragment";
    }

	public function display()
	{
		if ($this->is_displayed())
		{
			if ($this->get_block() == Menu::BLOCK_POSITION__LEFT || $this->get_block() == Menu::BLOCK_POSITION__RIGHT)
			{
				$template = $this->get_template_to_use();
				MenuService::assign_positions_conditions($template, $this->get_block());
				$this->assign_common_template_variables($template);

				$template->put_all([
					'ID'       => $this->get_menu_id(),
					'TITLE'    => $this->get_menu_title(),
					'CONTENTS' => $this->get_menu_content()
				]);

				return $template->render();
			}
			else
			{
				return $this->get_menu_content();
			}
		}
		return '';
	}
}
?>
