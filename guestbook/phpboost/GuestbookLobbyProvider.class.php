<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 6.1 - 2026 03 21
*/

class GuestbookLobbyProvider extends DefaultModuleLobbyProvider
{
	public function get_module_id(): string
	{
		return 'guestbook';
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

		$view = $this->get_lobby_template('MessagesLobbyProvider.tpl');
		$view->add_lang(array_merge(LangLoader::get_all_langs(), LangLoader::get_all_langs('lobby'), LangLoader::get_all_langs($module_id)));

		$result = PersistenceContext::get_querier()->select('
                SELECT
                    member.*,
                    guestbook.*, guestbook.login as glogin,
                    ext_field.user_avatar
                FROM ' . GuestbookSetup::$guestbook_table . ' guestbook
                LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = guestbook.user_id
                LEFT JOIN ' . DB_TABLE_MEMBER_EXTENDED_FIELDS . ' ext_field ON ext_field.user_id = member.user_id
                ORDER BY guestbook.timestamp DESC
                LIMIT :limit
            ', [
                'limit' => $module->get_elements_number_displayed()
            ]
		);

		$view->put_all([
			'C_NO_ITEM'       => $result->get_rows_count() == 0,
			'C_MODULE_LINK'   => true,
			'C_AVATAR_IMG'    => $user_accounts_config->is_default_avatar_enabled(),
			'MODULE_NAME'     => $module_id,
			'MODULE_POSITION' => LobbyConfig::load()->get_module_position_by_id($module_id),
			'L_MODULE_TITLE'  => ModulesManager::get_module($module_id)->get_configuration()->get_name(),
		]);

		while ($row = $result->fetch())
		{
			$item = new GuestbookItem();
			$item->set_properties($row);

			$contents    = @strip_tags(FormatingHelper::second_parse($item->get_content()), '<br><br/>');
			$cut_contents = TextHelper::cut_string($contents, (int) $module->get_characters_number_displayed());

            $view->assign_block_vars('items', array_merge($item->get_template_vars(), [
				'CONTENT' => $cut_contents,

				'U_AVATAR_IMG' => !empty($row['user_avatar']) ? Url::to_rel($row['user_avatar']) : $user_accounts_config->get_default_avatar(),
                'U_ITEM'       => GuestbookUrlBuilder::home(1, $item->get_id())->rel()
			]));
		}
		$result->dispose();

		return $view;
	}
}
?>
