<?php
/*##################################################
 *                        LastcomsModuleMiniMenu.class.php
 *                            -------------------
 *   begin                             : July 26, 2009
 *   copyright                         : (C) 2009 ROGUELON Geoffrey
 *   email                             : liaght@gmail.com
 *   Adapted for Phpboost since 4.1 by : babsolune - babsolune@phpboost.com
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

	public function admin_display()
	{
		return '';
	}

	public function get_menu_id()
	{
		return 'module-mini-lastcoms';
	}

	public function get_menu_title()
	{
		return LangLoader::get_message('lastcoms.title', 'common', 'lastcoms');
	}

	public function is_displayed()
	{
		return LastcomsAuthorizationsService::check_authorizations()->read();
	}

	public function get_menu_content()
	{

		$lang = LangLoader::get('common', 'lastcoms');
		$tpl = new FileTemplate('lastcoms/LastcomsModuleMiniMenu.tpl');
		$tpl->add_lang($lang);
		MenuService::assign_positions_conditions($tpl, $this->get_block());

		$now = new Date();
		$lastcoms_config = LastcomsConfig::load();
		$coms_char = $lastcoms_config->get_lastcoms_char();
		
		$querier = PersistenceContext::get_querier();
		$results = $querier->select("SELECT c.id, c.user_id, c.pseudo, c.message, c.timestamp, ct.path, ct.module_id, ct.is_locked, m.level, m.groups
			FROM " . DB_TABLE_COMMENTS . " AS c
			LEFT JOIN " . DB_TABLE_COMMENTS_TOPIC . " AS ct ON ct.id_topic = c.id_topic
			LEFT JOIN " . DB_TABLE_MEMBER . " AS m ON c.user_id = m.user_id
			WHERE ct.is_locked = 0
			AND ct.module_id != :forbidden_module
			ORDER BY c.timestamp DESC
			LIMIT :lastcoms_number",
			array(
				'lastcoms_number' => (int)$lastcoms_config->get_lastcoms_number(),
				'forbidden_module' => 'user'
			)
		);
		
		$comments_number = 0;
		while($row = $results->fetch())
		{
			$comments_number++;
			$contents = @strip_tags(FormatingHelper::second_parse($row['message']));
			$content_limited = trim(TextHelper::substr($contents, 0, (int)$coms_char));
			$user_group_color = User::get_group_color($row['groups'], $row['level']);
			
			$tpl->assign_block_vars('coms', array(
				'C_USER_GROUP_COLOR' => !empty($user_group_color),
				'C_AUTHOR_EXIST' => $row['user_id'] !== User::VISITOR_LEVEL,
				'USER_LEVEL_CLASS' => UserService::get_level_class($row['level']),
				'USER_GROUP_COLOR' => $user_group_color,
				'PSEUDO' => $row['pseudo'],
				'ETC' => TextHelper::strlen($contents) > $coms_char ? '...' : '',
				'COM_CONTENT' => $content_limited,
				'DATE' => strftime(date("d-m-y / H:i", $row['timestamp'])),
				'PATH' => Url::to_rel($row['path'] . '#com' . $row['id']),
				'U_AUTHOR_PROFILE' => UserUrlBuilder::profile($row['user_id'])->rel()
			));
		}

		$tpl->put_all(array(
			'C_COMS' => $comments_number > 0,
			'L_LAST_COMS' => $lang['lastcoms.title'],
		));

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
