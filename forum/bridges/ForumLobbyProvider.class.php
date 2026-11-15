<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 6.1 - 2026 03 21
*/

class ForumLobbyProvider extends DefaultModuleLobbyProvider
{
	public function get_module_id(): string
	{
		return 'forum';
	}

	public function has_categories(): bool
	{
		return false;
	}

	public function get_view(): FileTemplate
	{
		$module_id            = $this->get_module_id();
		$module               = LobbyModulesList::load()[$module_id];
		$user_accounts_config = UserAccountsConfig::load();

		$module_config = ForumConfig::load();

		$view = $this->get_lobby_template('MessagesLobbyProvider.tpl');
		$view->add_lang(array_merge(
            LangLoader::get_all_langs('lobby'),
            LangLoader::get_module_langs($module_id)
        ));

		$authorized_categories = CategoriesService::get_authorized_categories(Category::ROOT_CATEGORY, true, $module_id, 'id_category');

		$result = PersistenceContext::get_querier()->select('
                SELECT
                    t.id, t.id_category, t.title, t.last_timestamp, t.last_user_id, t.last_msg_id, t.display_msg, t.nbr_msg AS t_nbr_msg, t.user_id AS glogin,
                    member.display_name AS last_login, member.user_groups, member.level,
                    msg.id mid, msg.content,
                    ext_field.user_avatar
                FROM ' . PREFIX . 'forum_topics t
                LEFT JOIN ' . PREFIX . 'forum_cats cat ON cat.id = t.id_category
                LEFT JOIN ' . PREFIX . 'forum_msg msg ON msg.id = t.last_msg_id
                LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = t.last_user_id
                LEFT JOIN ' . DB_TABLE_MEMBER_EXTENDED_FIELDS . ' ext_field ON ext_field.user_id = member.user_id
                WHERE t.display_msg = 0 AND id_category IN :authorized_categories
                ORDER BY t.last_timestamp DESC
                LIMIT :limit
            ', [
				'authorized_categories' => $authorized_categories,
				'limit'                 => $module->get_elements_number_displayed(),
			]
		);

		$view->put_all([
			'C_NO_ITEM'       => $result->get_rows_count() == 0,
			'C_PARENT'        => true,
			'C_TOPIC'         => true,
			'C_AVATAR_IMG'    => $user_accounts_config->is_default_avatar_enabled(),
			'C_MODULE_LINK'   => true,
			'MODULE_NAME'     => $module_id,
			'MODULE_POSITION' => LobbyConfig::load()->get_module_position_by_id($module_id),
			'L_MODULE_TITLE'  => ModulesManager::get_module($module_id)->get_configuration()->get_name(),
		]);

		while ($row = $result->fetch())
		{
			$contents    = @strip_tags(FormatingHelper::second_parse($row['content']), '<br><br/>');
			$cut_contents = TextHelper::cut_string($contents, (int) $module->get_characters_number_displayed());
			$last_page = ceil($row['t_nbr_msg'] / $module_config->get_number_messages_per_page());
			$last_page_rewrite = ($last_page > 1) ? '-' . $last_page : '';
			$last_page = ($last_page > 1) ? 'pt=' . $last_page . '&amp;' : '';
			$link = new Url('/forum/topic' . url('.php?' . $last_page .  'id=' . $row['id'], '-' . $row['id'] . $last_page_rewrite . '-' . Url::encode_rewrite($row['title'])  . '.php') . '#m' .  $row['last_msg_id']);
			$link_message = new Url('/forum/topic' . url('.php?' . $last_page .  'id=' . $row['id'], '-' . $row['id'] . $last_page_rewrite . '-' . Url::encode_rewrite($row['title'])  . '.php'));
			$user_avatar = !empty($row['user_avatar'])
				? Url::to_rel($row['user_avatar'])
				: $user_accounts_config->get_default_avatar();

			$author_group_color = User::get_group_color($row['user_groups'], $row['level']);

			$view->assign_block_vars('items', [
				'C_AUTHOR_EXISTS'      => !empty($row['last_user_id']),
				'C_AUTHOR_GROUP_COLOR' => !empty($author_group_color),
				'C_AVATAR_IMG'         => !empty($row['user_avatar']),
				'C_READ_MORE'          => strlen($contents) > $module->get_characters_number_displayed(),

                'TITLE'               => $row['title'],
				'TOPIC'               => stripslashes($row['title']),
				'CONTENT'             => $cut_contents,
				'DATE'                => Date::to_format($row['last_timestamp'], Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE),
				'DATE_TIMESTAMP'      => $row['last_timestamp'],
				'AUTHOR_DISPLAY_NAME' => $row['last_login'],
				'AUTHOR_LEVEL_CLASS'  => UserService::get_level_class($row['level']),
				'AUTHOR_GROUP_COLOR'  => $author_group_color ? 'color:' . $author_group_color : '',

                'U_AVATAR_IMG'     => $user_avatar,
				'U_AUTHOR_PROFILE' => UserUrlBuilder::profile($row['last_user_id'])->rel(),
				'U_TOPIC'          => $link_message->rel(),
				'U_ITEM'           => $link->rel(),
			]);
		}
		$result->dispose();

		return $view;
	}
}
?>

