<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 6.1 - 2026 03 21
*/

class CalendarLobbyProvider extends DefaultModuleLobbyProvider
{
	public function get_module_id(): string
	{
		return 'calendar';
	}

	/**
	 * Calendar uses CategoriesService for authorisations but has no category sub-view in lobby.
	 * The base provider fetches upcoming events across all authorized categories.
	 */
	public function has_categories(): bool
	{
		return false;
	}

	public function get_view(): FileTemplate
	{
		$module_id     = $this->get_module_id();
		$module        = LobbyModulesList::load()[$module_id];
		$module_config = CalendarConfig::load();

		$today = new Date();
		$today->set_hours(0);
		$today->set_minutes(0);
		$today->set_seconds(0);

		$view = $this->get_lobby_template('CalendarLobbyProvider.tpl');
		$view->add_lang(array_merge(LangLoader::get_all_langs(), LangLoader::get_all_langs('lobby'), LangLoader::get_all_langs($module_id)));

		$authorized_categories = CategoriesService::get_authorized_categories(Category::ROOT_CATEGORY, true, $module_id);

		$result = PersistenceContext::get_querier()->select('
                SELECT *
                FROM ' . CalendarSetup::$calendar_events_table . ' event
                LEFT JOIN ' . CalendarSetup::$calendar_events_content_table . ' event_content ON event_content.id = event.content_id
                LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = event_content.author_user_id
                LEFT JOIN ' . CalendarSetup::$calendar_cats_table . ' cat ON cat.id = event_content.id_category
                WHERE approved = 1 AND id_category IN :authorized_categories
                AND start_date >= :timestamp_today
                ORDER BY start_date
                LIMIT :limit
            ', [
				'authorized_categories' => $authorized_categories,
				'timestamp_today'       => $today->get_timestamp(),
				'limit'                 => $module->get_elements_number_displayed(),
			]
		);

		$view->put_all([
			'C_DATE'          => true,
			'C_NO_ITEM'       => $result->get_rows_count() == 0,
			'C_LIST_VIEW'     => $module_config->get_display_type() == CalendarConfig::LIST_VIEW,
			'C_GRID_VIEW'     => $module_config->get_display_type() == CalendarConfig::GRID_VIEW,
			'C_TABLE_VIEW'    => $module_config->get_display_type() == CalendarConfig::TABLE_VIEW,
			'MODULE_NAME'     => $module_id,
			'MODULE_POSITION' => LobbyConfig::load()->get_module_position_by_id($module_id),
			'ITEMS_PER_ROW'   => $module_config->get_items_per_row(),
			'L_MODULE_TITLE'  => ModulesManager::get_module($module_id)->get_configuration()->get_name(),
		]);

		while ($row = $result->fetch())
		{
			$item = new CalendarItem();
			$item->set_properties($row);
			$view->assign_block_vars('items', $item->get_template_vars());
		}
		$result->dispose();

		return $view;
	}
}
?>
