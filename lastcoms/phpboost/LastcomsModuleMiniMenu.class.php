<?php
/**
 * @copyright   &copy; 2005-2025 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Geoffrey ROGUELON <liaght@gmail.com>
 * @version     PHPBoost 6.0 - last update: 2023 02 16
 * @since       PHPBoost 3.0 - 2009 07 26
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

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
		return LangLoader::get_message('lastcoms.module.title', 'common', 'lastcoms');
	}

	public function is_displayed()
	{
		return LastcomsAuthorizationsService::check_authorizations()->read();
	}

	public function get_menu_content()
	{
		$lang = LangLoader::get_all_langs('lastcoms');
		$view = new FileTemplate('lastcoms/LastcomsModuleMiniMenu.tpl');
		$view->add_lang($lang);
		MenuService::assign_positions_conditions($view, $this->get_block());
		Menu::assign_common_template_variables($view);

		$lastcoms_config = LastcomsConfig::load();
		$coms_char = $lastcoms_config->get_lastcoms_char();

		$results = PersistenceContext::get_querier()->select("SELECT
			c.id, c.user_id, c.pseudo, c.message, c.timestamp,
			ct.path, ct.module_id, ct.is_locked,
			m.level, m.user_groups
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
			$content = @strip_tags(FormatingHelper::second_parse($row['message']), '<br><br/>');
			$limited_content = TextHelper::cut_string($content, (int)$coms_char);
			$user_group_color = User::get_group_color($row['user_groups'], $row['level']);
			$modules_config = ModulesConfig::load();

			$view->assign_block_vars('items', array_merge(
				Date::get_array_tpl_vars(new Date($row['timestamp'], Timezone::SERVER_TIMEZONE), 'date'),
				array(
				'C_AUTHOR_GROUP_COLOR' => !empty($user_group_color),
				'C_AUTHOR_EXISTS'      => $row['user_id'] !== User::VISITOR_LEVEL,

				'AUTHOR_LEVEL_CLASS'  => UserService::get_level_class($row['level']),
				'AUTHOR_GROUP_COLOR'  => $user_group_color,
				'AUTHOR_DISPLAY_NAME' => $row['pseudo'],
				'CONTENT'             => $limited_content . (TextHelper::strlen($content) > $coms_char ? '...' : ''),
				'PATH'                => Url::to_rel($row['path'] . '#com' . $row['id']),
				'MODULE_NAME'		  => $modules_config->get_module($row['module_id']) ? $modules_config->get_module($row['module_id'])->get_configuration()->get_name() : '',

				'U_AUTHOR_PROFILE'   => UserUrlBuilder::profile($row['user_id'])->rel()
				)
			));
		}

		$view->put('C_COMS', $comments_number > 0);

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
