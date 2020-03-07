<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 03 06
 * @since       PHPBoost 5.2 - 2020 03 06
*/

class HomeLandingLastcoms
{
    public static function get_lastcoms_view()
	{
        $tpl = new FileTemplate('HomeLanding/pagecontent/lastcoms.tpl');
        $modules_config = ModulesConfig::load();
		$config = HomeLandingConfig::load();
        $modules = HomeLandingModulesList::load();

        $user_accounts_config = UserAccountsConfig::load();

        $result = PersistenceContext::get_querier()->select('SELECT c.id, c.user_id, c.pseudo, c.message, c.timestamp, ct.module_id, ct.is_locked, ct.path, m.*, ext_field.user_avatar
        FROM ' . DB_TABLE_COMMENTS . ' AS c
        LEFT JOIN ' . DB_TABLE_COMMENTS_TOPIC . ' AS ct ON ct.id_topic = c.id_topic
        LEFT JOIN ' . DB_TABLE_MEMBER . ' AS m ON c.user_id = m.user_id
        LEFT JOIN ' . DB_TABLE_MEMBER_EXTENDED_FIELDS . ' ext_field ON ext_field.user_id = m.user_id
        WHERE ct.is_locked = 0
        AND ct.module_id != :forbidden_module
        ORDER BY c.timestamp DESC
        LIMIT :last_coms_limit', array(
            'last_coms_limit' => $modules[HomeLandingConfig::MODULE_LASTCOMS]->get_elements_number_displayed(),
            'forbidden_module' => 'user'
        ));

        $tpl->put_all(array(
            'LASTCOMS_POSITION' => $config->get_module_position_by_id(HomeLandingConfig::MODULE_LASTCOMS),
            'C_NO_COMMENT' => $result->get_rows_count() == 0,
        ));

        while ($row = $result->fetch())
        {
            $contents = @strip_tags(FormatingHelper::second_parse($row['message']));
            $nb_char = $modules[HomeLandingConfig::MODULE_LASTCOMS]->get_characters_number_displayed();
            $cut_contents = trim(TextHelper::substr($contents, 0, $nb_char));
            $date = new Date($row['timestamp'], Timezone::SERVER_TIMEZONE);

            $user_avatar = !empty($row['user_avatar']) ? Url::to_rel($row['user_avatar']) : ($user_accounts_config->is_default_avatar_enabled() ? Url::to_rel('/templates/' . AppContext::get_current_user()->get_theme() . '/images/' .  $user_accounts_config->get_default_avatar_name()) : '');

            $author = new User();
            if (!empty($row['user_id']))
                $author->set_properties($row);
            else
                $author->init_visitor_user();
            $user_group_color = User::get_group_color($author->get_groups(), $author->get_level(), true);

            $tpl->assign_block_vars('item', array(
                'C_USER_GROUP_COLOR' => !empty($user_group_color),
                'C_AUTHOR_EXIST' => $author->get_id() !== User::VISITOR_LEVEL,
                'U_AVATAR' => $user_avatar,
                'PSEUDO' => $author->get_display_name(),
                'USER_LEVEL_CLASS' => UserService::get_level_class($author->get_level()),
                'USER_GROUP_COLOR' => $user_group_color,
                'U_AUTHOR_PROFILE' => UserUrlBuilder::profile($author->get_id())->rel(),
                'MODULE_NAME' => $modules_config->get_module($row['module_id']) ? $modules_config->get_module($row['module_id'])->get_configuration()->get_name() : '',
                'C_READ_MORE' => $cut_contents != $contents,
                'ARTICLE' => Url::to_rel($row['path']),
                'CONTENTS' => $cut_contents,
                'DATE' => $date->format(Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE),
                'U_LINK' => Url::to_rel($row['path'] . '#com' . $row['id'])
            ));
        }
        $result->dispose();


        return $tpl;
	}
}
?>
