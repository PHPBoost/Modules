<?php
/*##################################################
 *                        SmalladsLastItemsMiniMenu.class.php
 *                            -------------------
 *   begin                : March 15, 2018
 *   copyright            : (C) 2018 Sebastien LARTIGUE
 *   email                : babsolune@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

/**
 * @author Sebastien LARTIGUE <babsolune@phpboost.com>
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
 		return SmalladsAuthorizationsService::check_authorizations()->read();
 	}

 	public function get_menu_content()
 	{
 		$tpl = new FileTemplate('smallads/SmalladsLastItemsMiniMenu.tpl');
 		$tpl->add_lang(LangLoader::get('common', 'smallads'));
 		MenuService::assign_positions_conditions($tpl, $this->get_block());
        $config = SmalladsConfig::load();

 		//Load module caches
 		$smallads_cache = SmalladsCache::load();
 		$categories_cache = SmalladsService::get_categories_manager()->get_categories_cache();

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
