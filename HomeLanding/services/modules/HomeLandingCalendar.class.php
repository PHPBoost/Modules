<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 03 06
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
        $tpl = new FileTemplate('HomeLanding/pagecontent/events.tpl');
		$config = HomeLandingConfig::load();
        $modules = HomeLandingModulesList::load();

        $authorized_categories = CategoriesService::get_authorized_categories(Category::ROOT_CATEGORY, true, HomeLandingConfig::MODULE_CALENDAR);

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
            'calendar_limit' => $modules[HomeLandingConfig::MODULE_CALENDAR]->get_elements_number_displayed()
        ));

        $tpl->put_all(array(
            'CALENDAR_POSITION' => $config->get_module_position_by_id(HomeLandingConfig::MODULE_CALENDAR),
            'C_NO_EVENT' => $result->get_rows_count() == 0,
        ));

        while ($row = $result->fetch())
        {
            $event = new CalendarEvent();
            $event->set_properties($row);

            $description = TextHelper::substr(@strip_tags(FormatingHelper::second_parse($row['contents']), '<br><br/>'), 0, $modules[HomeLandingConfig::MODULE_CALENDAR]->get_characters_number_displayed());

            $tpl->assign_block_vars('item', array_merge($event->get_array_tpl_vars(), array(
                'C_READ_MORE' => TextHelper::strlen(FormatingHelper::second_parse($row['contents'])) >= $modules[HomeLandingConfig::MODULE_CALENDAR]->get_characters_number_displayed(),
                'DESCRIPTION' => $description
            )));
        }
        $result->dispose();

        return $tpl;
	}
}
?>
