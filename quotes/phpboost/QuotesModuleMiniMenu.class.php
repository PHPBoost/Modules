<?php
/*##################################################
 *                          QuotesModuleMiniMenu.class.php
 *                            -------------------
 *   begin                : February 4, 2013
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

class QuotesModuleMiniMenu extends ModuleMiniMenu
{
	public function get_default_block()
	{
		return self::BLOCK_POSITION__LEFT;
	}
	
	public function display($tpl = false)
	{
		global $Cache, $User, $CONFIG_QUOTES, $QUOTES_LANG, $LANG;
		global $_quotes_rand_msg;
		
		require_once(PATH_TO_ROOT.'/quotes/quotes.inc.php');
		
		load_module_lang('quotes');
		$Cache->load('quotes'); //Chargement du cache
		
		$tpl = new FileTemplate('quotes/quotes_mini.tpl');
		
		MenuService::assign_positions_conditions($tpl, $this->get_block());
		
		$quotes_rand = $_quotes_rand_msg[array_rand($_quotes_rand_msg)];
		
		$tpl->put_all(array(
			'C_LIST_ACCES' 		=> $User->check_auth($CONFIG_QUOTES['auth'], QUOTES_LIST_ACCESS),
			'L_RANDOM_QUOTES' 	=> $QUOTES_LANG['q_title'],
			'L_ALL_QUOTES' 		=> $QUOTES_LANG['q_title_all'],
			'RAND_MSG_ID' 		=> $quotes_rand['id'],
			'RAND_MSG_CONTENTS' => ucfirst($quotes_rand['contents']),
			'RAND_MSG_AUTHOR' 	=> ucfirst($quotes_rand['author'])
		));
		
		return $tpl->render();
	}
}
?>