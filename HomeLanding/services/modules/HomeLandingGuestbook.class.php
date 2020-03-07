<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 03 06
 * @since       PHPBoost 5.2 - 2020 03 06
*/

class HomeLandingGuestbook
{
    public static function get_guestbook_view()
	{
        $tpl = new FileTemplate('HomeLanding/pagecontent/guestbook.tpl');
        $user_accounts_config = UserAccountsConfig::load();
		$config = HomeLandingConfig::load();
        $modules = HomeLandingModulesList::load();

        $result = PersistenceContext::get_querier()->select('SELECT member.*, guestbook.*, guestbook.login as glogin, ext_field.user_avatar
        FROM ' . GuestbookSetup::$guestbook_table . ' guestbook
        LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = guestbook.user_id
        LEFT JOIN ' . DB_TABLE_MEMBER_EXTENDED_FIELDS . ' ext_field ON ext_field.user_id = member.user_id
        ORDER BY guestbook.timestamp DESC
        LIMIT :guestbook_limit', array(
            'guestbook_limit' => $modules[HomeLandingConfig::MODULE_GUESTBOOK]->get_elements_number_displayed()
        ));

        $tpl->put_all(array(
            'GUESTBOOK_POSITION' => $config->get_module_position_by_id(HomeLandingConfig::MODULE_GUESTBOOK),
            'C_EMPTY_GUESTBOOK' => $result->get_rows_count() == 0,
        ));

        while ($row = $result->fetch())
        {
            $message = new GuestbookMessage();
            $message->set_properties($row);

            $contents = @strip_tags(FormatingHelper::second_parse($message->get_contents()));
            $nb_char = $modules[HomeLandingConfig::MODULE_GUESTBOOK]->get_characters_number_displayed();
            $user_avatar = !empty($row['user_avatar']) ? Url::to_rel($row['user_avatar']) : ($user_accounts_config->is_default_avatar_enabled() ? Url::to_rel('/templates/' . AppContext::get_current_user()->get_theme() . '/images/' .  $user_accounts_config->get_default_avatar_name()) : '');
            $cut_contents = trim(TextHelper::substr($contents, 0, $nb_char));

            $tpl->assign_block_vars('item', array_merge($message->get_array_tpl_vars(), array(
                'C_READ_MORE' => $cut_contents != $contents,
                'U_AVATAR' => $user_avatar,
                'CONTENTS' => $cut_contents,
            )));
        }
        $result->dispose();
        return $tpl;
	}
}
?>
