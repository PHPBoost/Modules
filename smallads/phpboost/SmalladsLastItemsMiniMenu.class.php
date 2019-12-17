<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 11 12
 * @since       PHPBoost 5.1 - 2018 03 15
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

 class SmalladsLastItemsMiniMenu extends ModuleMiniMenu
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
 		$tpl = new FileTemplate('smallads/SmalladsLastItemsMiniMenu.tpl');
 		$tpl->add_lang(LangLoader::get('common', 'smallads'));
 		MenuService::assign_positions_conditions($tpl, $this->get_block());
		Menu::assign_common_template_variables($tpl);
        $config = SmalladsConfig::load();

 		//Load module caches
 		$smallads_cache = SmalladsCache::load();
 		$categories_cache = CategoriesService::get_categories_manager('smallads')->get_categories_cache();

 		$smallad = $smallads_cache->get_smallad();
        $smallad_nb = SmalladsService::count('WHERE published != 0');

 		$tpl->put_all(array(
 			'C_SMALLADS'        => !empty($smallad),
 			'C_ONE_SMALLAD'     => $smallad_nb == 1,
			'SMALLADS_TOTAL_NB' => $smallad_nb,
            'CURRENCY'          => $config->get_currency(),
			'ANIMATION_SPEED'   => $config->get_mini_menu_animation_speed(),
			'AUTOPLAY'          => $config->is_slideshow_autoplayed(),
			'AUTOPLAY_SPEED'    => $config->get_mini_menu_autoplay_speed(),
			'AUTOPLAY_HOVER'    => $config->is_slideshow_hover_enabled(),
 		));

 		foreach ($smallad as $file)
 		{
 			$smallad = new Smallad();
 			$smallad->set_properties($file);

 			$tpl->assign_block_vars('items', $smallad->get_array_tpl_vars());
 		}

 		return $tpl->render();
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
