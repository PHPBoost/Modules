<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 09 01
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
        if (file_exists(PATH_TO_ROOT . '/templates/' . $theme_id . '/modules/HomeLanding/pagecontent/' . $module_name . '.tpl'))
			$view = new FileTemplate('/templates/' . $theme_id . '/modules/HomeLanding/pagecontent/' . $module_name . '.tpl');
        elseif (file_exists(PATH_TO_ROOT . '/HomeLanding/templates/pagecontent/' . $module_name . '.tpl'))
			$view = new FileTemplate('/HomeLanding/templates/pagecontent/' . $module_name . '.tpl');
		else
            $view = new FileTemplate('HomeLanding/pagecontent/items.tpl');

        $home_lang = LangLoader::get('common', 'HomeLanding');
        $module_lang = LangLoader::get('common', $module_name);
        $view->add_lang(array_merge($home_lang, $module_lang, LangLoader::get('common-lang')));

        $authorized_categories = CategoriesService::get_authorized_categories(Category::ROOT_CATEGORY, true, $module_name);

        $result = PersistenceContext::get_querier()->select('SELECT *
            FROM '. PREFIX . 'calendar_events event
            LEFT JOIN ' . PREFIX . 'calendar_events_content event_content ON event_content.id = event.content_id
            LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = event_content.author_user_id
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
            'C_DATE'          => true,
            'C_NO_ITEM'       => $result->get_rows_count() == 0,
            'C_LIST_VIEW'     => $module_config->get_display_type() == CalendarConfig::LIST_VIEW,
            'C_GRID_VIEW'     => $module_config->get_display_type() == CalendarConfig::GRID_VIEW,
			'C_TABLE_VIEW'    => $module_config->get_display_type() == CalendarConfig::TABLE_VIEW,
            'MODULE_NAME'     => $module_name,
            'MODULE_POSITION' => $home_config->get_module_position_by_id($module_name),
            'ITEMS_PER_ROW'   => $module_config->get_items_per_row(),
            'L_MODULE_TITLE'  => ModulesManager::get_module($module_name)->get_configuration()->get_name(),
        ));

        while ($row = $result->fetch())
        {
            $item = new CalendarItem();
            $item->set_properties($row);

            $description = TextHelper::substr(@strip_tags(FormatingHelper::second_parse($row['content']), '<br><br/>'), 0, $modules[$module_name]->get_characters_number_displayed());

            $view->assign_block_vars('items', array_merge($item->get_array_tpl_vars(), array(
                'C_READ_MORE' => TextHelper::strlen(FormatingHelper::second_parse($row['content'])) >= $modules[$module_name]->get_characters_number_displayed(),
                'SUMMARY'     => $description
            )));
        }
        $result->dispose();

        return $view;
	}
}
?>
