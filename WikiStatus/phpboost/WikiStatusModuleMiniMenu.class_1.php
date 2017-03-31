<?php
/*##################################################
 *                               WikiStatusModuleMiniMenu.class.php
 *                            -------------------
 *   begin                : January 30, 2017
 *   copyright            : (C) 2016 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
 *
 *
 ###################################################
 *
 * This program is a free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

 /**
 * @author Julien BRISWALTER <j1.seth@phpboost.com>
 */

class WikiStatusModuleMiniMenu extends ModuleMiniMenu
{
	public function get_default_block()
	{
		return self::BLOCK_POSITION__BOTTOM_CENTRAL;
	}
	
	public function get_menu_id()
	{
		return 'module-mini-wiki-status';
	}
	
	public function get_menu_title()
	{
		return LangLoader::get_message('menu_title', 'common', 'WikiStatus');
	}
	
	public function get_menu_content()
	{
		$tpl = new FileTemplate('WikiStatus/ArticlesWikiStatusUpdated.tpl');
		$lang = LangLoader::get('common', 'WikiStatus');
		
		//Assign the lang file to the tpl
		$tpl->add_lang($lang);
		
		$status_classes = array(
			1 => 'success',
			1 => 'question',
			1 => 'notice',
			1 => 'warning',
			1 => 'error'
		);
		
		//Load module config
		$querier = PersistenceContext::get_querier();
        $maj_article = 1;
        $results = $querier->select('SELECT a.title, a.encoded_title, a.undefined_status, a.defined_status, c.timestamp, c.user_id, m.display_name, m.groups, m.level, c.id_article, c.activ
		FROM ' . PREFIX . 'wiki_contents c
		LEFT JOIN ' . PREFIX . 'wiki_articles a ON a.id = c.id_article AND a.id_contents = c.id_contents
		LEFT JOIN ' . DB_TABLE_MEMBER . ' m ON m.user_id = c.user_id
		WHERE defined_status = "-1" OR defined_status > "0"
		ORDER BY c.timestamp DESC', array(
			'user_id' => AppContext::get_current_user()->get_id()
		));

		while($row = $results->fetch())
		{
			$user_group_color = User::get_group_color($row['groups'], $row['level']);
			$tpl->assign_block_vars('articles_wiki_items', array(
				'TITLE' => stripslashes($row['title']),
				'STATUS_CLASS' => $status_classes['defined_status'],
				'STATUS' => $row['undefined_status'] ? $row['undefined_status'] : $lang['status_' . $row['defined_status']],
				'C_AUTHOR_EXIST' => !empty($row['display_name']),
				'USER_LEVEL_CLASS' => UserService::get_level_class($row['level']),
				'C_USER_GROUP_COLOR' => !empty($user_group_color),
				'USER_GROUP_COLOR' => $user_group_color,
				'PSEUDO' => $row['display_name'],
				'U_AUTHOR_PROFILE' => UserUrlBuilder::profile($row['user_id'])->rel(),
				'AUTHOR_IP' => $row['user_ip'],
				'DATE' => Date::to_format($row['timestamp'], Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE),
				'U_ARTICLE' => $row['activ'] == 1 ? url('wiki.php?title=' . $row['encoded_title'], $row['encoded_title']) : url('wiki.php?id_contents=' . $row['id_contents']),
			)); 
			$maj_article++;
		}
 
        return $tpl->render();
	}
}
?>
