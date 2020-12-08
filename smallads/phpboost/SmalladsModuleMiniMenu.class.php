<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 12 08
 * @since       PHPBoost 5.1 - 2018 03 15
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

 class SmalladsModuleMiniMenu extends ModuleMiniMenu
 {
 	public function get_default_block()
 	{
 		return self::BLOCK_POSITION__RIGHT;
 	}

 	public function get_menu_id()
 	{
 		return 'smallads-mini-module';
 	}

 	public function get_menu_title()
 	{
 		return LangLoader::get_message('mini.last.smallads', 'common', 'smallads');
 	}

 	public function is_displayed()
 	{
 		return CategoriesAuthorizationsService::check_authorizations(Category::ROOT_CATEGORY, 'smallads')->read();
 	}

 	public function get_menu_content()
 	{
 		$view = new FileTemplate('smallads/SmalladsModuleMiniMenu.tpl');
 		$view->add_lang(LangLoader::get('common', 'smallads'));
 		MenuService::assign_positions_conditions($view, $this->get_block());
		Menu::assign_common_template_variables($view);
        $config = SmalladsConfig::load();

 		// Load module caches
 		$smallads_cache = SmalladsCache::load();
 		$categories_cache = CategoriesService::get_categories_manager('smallads')->get_categories_cache();

 		$smallads_items = $smallads_cache->get_smallad();
        $items_number = SmalladsService::count('WHERE published != 0');

 		$view->put_all(array(
 			'C_ITEMS'         => !empty($smallads_items),
 			'C_ONE_ITEM'      => $items_number == 1,
			'C_CSS_CACHE_ENABLED' => CSSCacheConfig::load()->is_enabled(),
			'ITEMS_TOTAL_NB'  => $items_number,
            'CURRENCY'        => $config->get_currency(),
			'ANIMATION_SPEED' => $config->get_mini_menu_animation_speed(),
			'AUTOPLAY'        => $config->is_slideshow_autoplayed(),
			'AUTOPLAY_SPEED'  => $config->get_mini_menu_autoplay_speed(),
			'AUTOPLAY_HOVER'  => $config->is_slideshow_hover_enabled(),
 		));

 		foreach ($smallads_items as $smallad)
 		{
 			$item = new Smallad();
 			$item->set_properties($smallad);

 			$view->assign_block_vars('items', $item->get_array_tpl_vars());
 		}

 		return $view->render();
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

				$template->put_all(array(
					'ID' => $this->get_menu_id(),
					'TITLE' => $this->get_menu_title(),
					'CONTENTS' => $this->get_menu_content()
				));

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
