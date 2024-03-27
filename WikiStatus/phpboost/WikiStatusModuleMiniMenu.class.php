<?php
/**
 * @copyright   &copy; 2005-2024 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 04 01
 * @since       PHPBoost 5.0 - 2017 01 30
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
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
		return LangLoader::get_message('wiki.status.module.title', 'common', 'WikiStatus');
	}

	public function get_formated_title()
	{
		return LangLoader::get_message('wiki.status.formated.module.title', 'common', 'WikiStatus');
	}

	public function get_menu_content()
	{
		$lang = LangLoader::get_all_langs('WikiStatus');
		$view = new FileTemplate('WikiStatus/WikiStatusModuleMiniMenu.tpl');
		$view->add_lang($lang);
		MenuService::assign_positions_conditions($view, $this->get_block());
		Menu::assign_common_template_variables($view);

		$status_classes = array(
			1 => 'success',
			2 => 'question',
			3 => 'notice',
			4 => 'warning',
			5 => 'error'
		);

		//Load module config
		$querier = PersistenceContext::get_querier();
        $maj_article = 1;
        $results = $querier->select('SELECT
			a.title, a.encoded_title, a.undefined_status, a.defined_status,
			c.timestamp, c.user_id, c.user_ip, c.id_article, c.activ,
			m.display_name, m.user_groups, m.level
		FROM ' . PREFIX . 'wiki_contents c
		LEFT JOIN ' . PREFIX . 'wiki_articles a ON a.id = c.id_article AND a.id_contents = c.id_contents
		LEFT JOIN ' . DB_TABLE_MEMBER . ' m ON m.user_id = c.user_id
		WHERE defined_status = "-1" OR defined_status > "0"
		ORDER BY c.timestamp DESC', array(
			'user_id' => AppContext::get_current_user()->get_id()
		));

		$view->put('C_ITEMS', $results->get_rows_count() > 0 );

		while($row = $results->fetch())
		{
			$user_group_color = User::get_group_color($row['user_groups'], $row['level']);
			$view->assign_block_vars('wiki_items', array(
				'C_AUTHOR_GROUP_COLOR' => !empty($user_group_color),
				'C_AUTHOR_EXISTS'      => !empty($row['display_name']),

				'TITLE'               => stripslashes($row['title']),
				'STATUS_CLASS'        => in_array($row['defined_status'], array_keys($status_classes)) ? $status_classes[$row['defined_status']] : '',
				'AUTHOR_LEVEL_CLASS'  => UserService::get_level_class($row['level']),
				'AUTHOR_GROUP_COLOR'  => $user_group_color,
				'AUTHOR_DISPLAY_NAME' => $row['display_name'],
				'AUTHOR_IP'           => $row['user_ip'],
				'DATE'                => Date::to_format($row['timestamp'], Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE),
				'SHORT_DATE'          => Date::to_format($row['timestamp'], Date::FORMAT_DAY_MONTH_YEAR),

				'U_AUTHOR_PROFILE' => UserUrlBuilder::profile($row['user_id'])->rel(),
				'U_ITEM' 		   => $row['activ'] == 1 ? url('wiki.php?title=' . $row['encoded_title'], $row['encoded_title']) : url('wiki.php?id_contents=' . $row['id_contents']),

				'L_STATUS' => $row['undefined_status'] ? $row['undefined_status'] : ($lang['wiki.status.' . $row['defined_status']] ? $lang['wiki.status.' . $row['defined_status']] : ''),
			));
			$maj_article++;
		}

        return $view->render();
	}
}
?>
