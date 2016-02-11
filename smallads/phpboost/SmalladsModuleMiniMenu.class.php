<?php
/*##################################################
 *                          SmalladsModuleMiniMenu.class.php
 *                            -------------------
 *   begin                : January 29, 2013
 *   copyright            : (C) 2013 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
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

class SmalladsModuleMiniMenu extends ModuleMiniMenu
{
	public function get_default_block()
	{
		return self::BLOCK_POSITION__LEFT;
	}
	
	public function get_menu_id()
	{
		return 'module-mini-smallads';
	}
	
	public function get_menu_title()
	{
		global $LANG;
		load_module_lang('smallads');
		
		return $LANG['sa_title'];
	}
	
	public function is_displayed()
	{
		return SmalladsAuthorizationsService::check_authorizations()->read();
	}
	
	public function get_menu_content()
	{
		global $LANG;
		
		load_module_lang('smallads');
		
		$smallads_cache = SmalladsCache::load();
		
		$type_options = array();
		for ($i = 1; $i <= 9; $i++) {
			if (!empty($LANG['sa_group_'.$i]))
				$type_options[$i] = $LANG['sa_group_'.$i];
			else
				break;
		}

		$tpl = new FileTemplate('smallads/SmalladsModuleMiniMenu.tpl');
		
		$last_smallad_date = new Date($smallads_cache->get_last_smallad_date(), Timezone::SERVER_TIMEZONE);
		
		$tpl->put_all(array(
			'L_ALL_SMALLADS' 	=> $LANG['sa_title_all'],
			'L_PRICE_UNIT'		=> $LANG['sa_price_unit'],
			'U_HREF'			=> TPL_PATH_TO_ROOT.'/smallads/smallads.php',
			'L_INFO' 			=> sprintf($LANG['sa_mini_info'],$smallads_cache->get_number_smallads(), $last_smallad_date->format(Date::FORMAT_DAY_MONTH_YEAR)),
		));
		
		foreach($smallads_cache->get_smallads() as $v)
		{
			$date_created = !empty($v['date_created']) ? new Date($v['date_created'], Timezone::SERVER_TIMEZONE) : null;
			
			$tpl->assign_block_vars('item', array(
				'ID' 		=> $v['id'],
				'TITLE' 	=> $v['title'],
				'CONTENTS'	=> FormatingHelper::second_parse(stripslashes($v['contents'])),
				'TYPE' 		=> $type_options[intval($v['type'])],
				'PRICE' 	=> $v['price'],
				'DATE'		=> (!empty($date_created)) ? $LANG['sa_created'] . $date_created->format(Date::FORMAT_DAY_MONTH_YEAR) : '',
				'C_PICTURE'	 => !empty($v['picture']),
				'PICTURE'	 => !empty($v['picture']) ? TPL_PATH_TO_ROOT.'/smallads/pics/'.$v['picture'] : '',
				));
		}
		
		return $tpl->render();
	}
}
?>