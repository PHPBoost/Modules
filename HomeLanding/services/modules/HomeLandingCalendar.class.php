<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 05 13
 * @since       PHPBoost 5.2 - 2020 03 06
*/

class HomeLandingCalendar
{
    public static function get_calendar_view()
	{
        $today = new Date();
        $today->set_hours(0);
        $today->set_minutes(0);
        $today->set_seconds(0);

        $module_config = CalendarConfig::load();
		$home_config   = HomeLandingConfig::load();
        $modules       = HomeLandingModulesList::load();
        $module_name   = HomeLandingConfig::MODULE_CALENDAR;

        $theme_id = AppContext::get_current_user()->get_theme();
        if (file_exists(PATH_TO_ROOT . '/HomeLanding/templates/pagecontent/' . $module_name . '.tpl'))
			$view = new FileTemplate('/HomeLanding/templates/pagecontent/' . $module_name . '.tpl');
        elseif (file_exists(PATH_TO_ROOT . '/templates/' . $theme_id . '/modules/HomeLanding/pagecontent/' . $module_name . '.tpl'))
			$view = new FileTemplate('/templates/' . $theme_id . '/modules/HomeLanding/pagecontent/' . $module_name . '.tpl');
		else
            $view = new FileTemplate('HomeLanding/pagecontent/items.tpl');

        $home_lang = LangLoader::get('common', 'HomeLanding');
        $module_lang = LangLoader::get('common', $module_name);
        $view->add_lang($home_lang);
        $view->add_lang($module_lang);

        $authorized_categories = CategoriesService::get_authorized_categories(Category::ROOT_CATEGORY, true, $module_name);

        $result = PersistenceContext::get_querier()->select('SELECT *
            FROM '. PREFIX . 'calendar_events event
            LEFT JOIN ' . PREFIX . 'calendar_events_content event_content ON event_content.id = event.content_id
            LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = event_content.author_id
            LEFT JOIN '. PREFIX . 'calendar_cats cat ON cat.id = event_content.id_category
            WHERE approved = 1 AND id_category IN :authorized_categories
            AND start_date >= :timestamp_today
            ORDER BY start_date
            LIMIT :calendar_limit', array(
                'authorized_categories' => $authorized_categories,
                'timestamp_today' => $today->get_timestamp(),
                'calendar_limit' => $modules[$module_name]->get_elements_number_displayed()
        ));

        $view->put_all(array(
            'C_DATE' => true,
            'MODULE_NAME' => $module_name,
            'MODULE_POSITION' => $home_config->get_module_position_by_id($module_name),
            'L_MODULE_TITLE' => LangLoader::get_message('last.'.$module_name, 'common', 'HomeLanding'),
            'L_SEE_ALL_ITEMS' => LangLoader::get_message('link.to.'.$module_name, 'common', 'HomeLanding'),
			'C_NO_ITEM' => $result->get_rows_count() == 0,
        ));

        while ($row = $result->fetch())
        {
            $event = new CalendarEvent();
            $event->set_properties($row);

            $description = TextHelper::substr(@strip_tags(FormatingHelper::second_parse($row['contents']), '<br><br/>'), 0, $modules[$module_name]->get_characters_number_displayed());

            $view->assign_block_vars('item', array_merge($event->get_array_tpl_vars(), array(
                'C_READ_MORE' => TextHelper::strlen(FormatingHelper::second_parse($row['contents'])) >= $modules[$module_name]->get_characters_number_displayed(),
                'SUMMARY' => $description
            )));
        }
        $result->dispose();

        return $view;
	}
}
?>
