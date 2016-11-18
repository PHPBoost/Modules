<?php
/*##################################################
 *                        LastcomsModuleMiniMenu.class.php
 *                            -------------------
 *   begin                       : July 26, 2009
 *   copyright                   : (C) 2009 ROGUELON Geoffrey
 *   email                       : liaght@gmail.com
 *   Adapted for Phpboost 4.1 by : babsolune - babso@web33.fr
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

class LastcomsModuleMiniMenu extends ModuleMiniMenu
{
	public function get_default_block()
	{
		return self::BLOCK_POSITION__LEFT;
	}
	
	public function get_menu_id()
	{
		return 'module-mini-lastcoms';
	}
	
	public function get_menu_title()
	{
		return LangLoader::get_message('title', 'common', 'lastcoms');
	}

	public function get_menu_content()
	{
    	if (LastcomsAuthorizationsService::check_authorizations()->read())
		{
			
			$lang = LangLoader::get('common', 'lastcoms');
			$tpl = new FileTemplate('lastcoms/LastcomsModuleMiniMenu.tpl');
			$tpl->add_lang($lang);
			MenuService::assign_positions_conditions($tpl, $this->get_block());
			
			$now = new Date();
			$lastcoms_config = LastcomsConfig::load();
			$coms_number = $lastcoms_config->get_lastcoms_number();
			$coms_char = $lastcoms_config->get_lastcoms_char();
			$level = array(0 => '', 1 => ' class="modo"', 2 => ' class="admin"');
			
			$querier = PersistenceContext::get_querier();
			
			$results = $querier->select('SELECT c.id, c.user_id, c.pseudo, c.message, c.timestamp, ct.path, m.level
				FROM ' . DB_TABLE_COMMENTS . ' AS c
				LEFT JOIN ' . DB_TABLE_COMMENTS_TOPIC . ' AS ct ON ct.id_topic = c.id_topic
				LEFT JOIN ' . DB_TABLE_MEMBER . ' AS m ON c.user_id = m.user_id
				ORDER BY c.timestamp DESC
				LIMIT :lastcoms_number', array(
					'lastcoms_number' => (int)$coms_number
				)
			);
	
			while($row = $results->fetch())
			{
				$contents = strip_tags(FormatingHelper::second_parse($row['message']));
				
				$tpl->assign_block_vars('coms', array(
					'LOGIN' => $row['pseudo'],
					'PROFIL' => $row['user_id'] > 0 ? UserUrlBuilder::profile($row['user_id'])->absolute() : 0,
					'LEVEL' => (string)($row['level'] > 0 ? ' class="' . UserService::get_level_class($row['level']) . '"' : ''),
					'ETC' => strlen($contents) > $coms_char ? '...' : '',
					'CONTENTS' => trim(substr($contents, 0, $coms_char)),
					'DATE' => strftime(date("d-m-y/H:i", $row['timestamp'])),
					'PATH' => PATH_TO_ROOT . $row['path'] . '#com' . $row['id']
				));
			}
			
			$tpl->put_all(array(
				'L_LAST_COMS' => $lang['title'],
			));
			
			return $tpl->render();
		}
	}
}
?>