<?php
/*##################################################
 *                          SmalladsModuleMiniMenu.class.php
 *                            -------------------
 *   begin                : January 29, 2013
 *   copyright            : (C) 2013 Julien BRISWALTER
 *   email                : julienseth78@phpboost.com
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
	
	public function display($tpl = false)
	{
		global $Cache, $User, $CONFIG_SMALLADS, $LANG;
		global $_smallads_mini, $_smallads_mini_info;
		
		define('SMALLADS_LIST',		0x08);
		define('SMALLADS_CONTRIB',	0x10);
		
		load_module_lang('smallads');
		$Cache->load('smallads'); //Chargement du cache
		
		$type_options = array();
		for ($i = 1; $i <= 9; $i++) {
			if (!empty($LANG['sa_group_'.$i]))
				$type_options[$i] = $LANG['sa_group_'.$i];
			else
				break;
		}

		$tpl = new FileTemplate('smallads/SmalladsModuleMiniMenu.tpl');
		
		MenuService::assign_positions_conditions($tpl, $this->get_block());
		
		$tpl->put_all(array(
			'C_LIST_ACCES' 		=> $User->check_auth($CONFIG_SMALLADS['auth'], SMALLADS_LIST|SMALLADS_CONTRIB),
			'L_TITLE'			=> $LANG['sa_title'],
			'L_ALL_SMALLADS' 	=> $LANG['sa_title_all'],
			'L_PRICE_UNIT'		=> $LANG['sa_price_unit'],
			'U_HREF'			=> TPL_PATH_TO_ROOT.'/smallads/smallads.php',
			'L_INFO' 			=> sprintf($LANG['sa_mini_info'],$_smallads_mini_info['count'],gmdate_format('date_format', $_smallads_mini_info['date_last'])),
			));
		
		foreach($_smallads_mini as $v)
		{
			$tpl->assign_block_vars('item', array(
				'ID' 		=> $v['id'],
				'TITLE' 	=> $v['title'],
				'CONTENTS'	=> FormatingHelper::second_parse($v['contents']),
				'TYPE' 		=> $type_options[intval($v['type'])],
				'PRICE' 	=> $v['price'],
				'DATE'		=> $LANG['sa_created'].gmdate_format('date_format_short', $v['date_created']),
				'C_PICTURE'	 => !empty($v['picture']),
				'PICTURE'	 => !empty($v['picture']) ? TPL_PATH_TO_ROOT.'/smallads/pics/'.$v['picture'] : '',
				));
		}
		
		return $tpl->render();
	}
}
?>