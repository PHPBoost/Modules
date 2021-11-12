<?php
/**
 * @copyright   &copy; 2005-2021 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 11 12
 * @since       PHPBoost 6.0 - 2021 11 11
*/

class HomeLandingPinnedNews
{
	public static function get_items()
	{
		$parent_module = HomeLandingConfig::MODULE_NEWS;
		$module = ModulesManager::get_module(HomeLandingConfig::MODULE_NEWS);
		$module_config = $module->get_configuration()->get_configuration_parameters();
		$home_modules  = HomeLandingModulesList::load();
		$page_type     = HomeLandingConfig::MODULE_PINNED_NEWS;
		$sql_condition = 'WHERE id_category IN :categories
			AND top_list_enabled = 1
			AND (published = ' . Item::PUBLISHED . ' OR (published = ' . Item::DEFERRED_PUBLICATION . ' AND publishing_start_date < :timestamp_now AND (publishing_end_date > :timestamp_now OR publishing_end_date = 0)))';

		$sql_parameters = array();
		$sql_parameters['categories'] = CategoriesService::get_authorized_categories(Category::ROOT_CATEGORY, $module->get_configuration()->has_rich_config_parameters() ? $module_config->get_summary_displayed_to_guests() : true, $parent_module);

		$items = ItemsService::get_items_manager($parent_module)->get_items($sql_condition, $sql_parameters, $home_modules[$page_type]->get_elements_number_displayed(), 0, 'creation_date', Item::DESC);

		return $items;
	}

	public static function get_pinned_news_view()
	{
		$parent_module = HomeLandingConfig::MODULE_NEWS;
		$module = ModulesManager::get_module(HomeLandingConfig::MODULE_NEWS);
		$module_config = $module->get_configuration()->get_configuration_parameters();
		$page_type     = HomeLandingConfig::MODULE_PINNED_NEWS;

        $theme_id = AppContext::get_current_user()->get_theme();
        if (file_exists(PATH_TO_ROOT . '/templates/' . $theme_id . '/modules/HomeLanding/pagecontent/' . $page_type . '.tpl'))
			$view = new FileTemplate('/templates/' . $theme_id . '/modules/HomeLanding/pagecontent/' . $page_type . '.tpl');
        elseif (file_exists(PATH_TO_ROOT . '/HomeLanding/templates/pagecontent/' . $page_type . '.tpl'))
			$view = new FileTemplate('/HomeLanding/templates/pagecontent/' . $page_type . '.tpl');
		else
            $view = new FileTemplate('HomeLanding/pagecontent/items.tpl');

		$home_lang = LangLoader::get('common', 'HomeLanding');
		$module_lang = LangLoader::get('common', $parent_module);
		$view->add_lang(array_merge($home_lang, $module_lang, LangLoader::get('common-lang')));

		$sql_condition = 'WHERE id_category IN :categories
			AND top_list_enabled = 1
			AND (published = ' . Item::PUBLISHED . ' OR (published = ' . Item::DEFERRED_PUBLICATION . ' AND publishing_start_date < :timestamp_now AND (publishing_end_date > :timestamp_now OR publishing_end_date = 0)))';

		$sql_parameters = array();
		$sql_parameters['categories'] = CategoriesService::get_authorized_categories(Category::ROOT_CATEGORY, $module->get_configuration()->has_rich_config_parameters() ? $module_config->get_summary_displayed_to_guests() : true, $parent_module);

		$view->put_all(array(
			'C_NO_ITEM'          => count(self::get_items()) == 0,
			'C_VIEWS_NUMBER'     => $module_config->get_views_number_enabled(),
			'MODULE_NAME'        => $parent_module,
			'MODULE_POSITION'    => HomeLandingConfig::load()->get_module_position_by_id($page_type),
		));

		if ($module->get_configuration()->has_rich_config_parameters())
		{
			$view->put_all(array(
				'C_VIEWS_NUMBER'     => $module_config->get_views_number_enabled(),
				'C_LIST_VIEW'        => $module_config->get_display_type() == DefaultRichModuleConfig::LIST_VIEW,
				'C_GRID_VIEW'        => $module_config->get_display_type() == DefaultRichModuleConfig::GRID_VIEW,
				'C_TABLE_VIEW'       => $module_config->get_display_type() == DefaultRichModuleConfig::TABLE_VIEW,
				'C_AUTHOR_DISPLAYED' => $module_config->get_author_displayed(),
				'ITEMS_PER_ROW'      => $module_config->get_items_per_row(),
				'L_MODULE_TITLE' 	 => HomeLandingConfig::load()->get_pinned_news_title(),
			));
		}

		foreach (self::get_items() as $item)
		{
			$view->assign_block_vars('items', $item->get_template_vars());
		}

		return $view;
	}
}
?>
