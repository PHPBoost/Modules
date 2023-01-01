<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 02 22
 * @since       PHPBoost 5.2 - 2020 03 06
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class HomeLandingDisplayItems
{
	public static function build_view($module_name, $module_cat = false, $has_pinned = false)
	{
		$module        = ModulesManager::get_module($module_name);
		$module_config = $module->get_configuration()->get_configuration_parameters();
		$home_modules  = HomeLandingModulesList::load();
		$page_type     = $module_cat ? $module_cat : $module_name;

        $theme_id = AppContext::get_current_user()->get_theme();
        if (file_exists(PATH_TO_ROOT . '/templates/' . $theme_id . '/modules/HomeLanding/pagecontent/' . $page_type . '.tpl'))
			$view = new FileTemplate('/templates/' . $theme_id . '/modules/HomeLanding/pagecontent/' . $page_type . '.tpl');
        elseif (file_exists(PATH_TO_ROOT . '/HomeLanding/templates/pagecontent/' . $page_type . '.tpl'))
			$view = new FileTemplate('/HomeLanding/templates/pagecontent/' . $page_type . '.tpl');
		else
            $view = new FileTemplate('HomeLanding/pagecontent/items.tpl');

		$home_lang = LangLoader::get_all_langs('HomeLanding');
		$module_lang = LangLoader::get_all_langs($module_name);
		$view->add_lang(array_merge($home_lang, $module_lang));

		$sql_condition = 'WHERE id_category IN :categories
			AND (published = ' . Item::PUBLISHED . ' OR (published = ' . Item::DEFERRED_PUBLICATION . ' AND publishing_start_date < :timestamp_now AND (publishing_end_date > :timestamp_now OR publishing_end_date = 0)))';

		// Manage pinned item in News
		if ($has_pinned && $home_modules[HomeLandingConfig::MODULE_PINNED_NEWS]->is_displayed())
			$sql_condition = 'WHERE id_category IN :categories
			AND top_list_enabled = 0
			AND (published = ' . Item::PUBLISHED . ' OR (published = ' . Item::DEFERRED_PUBLICATION . ' AND publishing_start_date < :timestamp_now AND (publishing_end_date > :timestamp_now OR publishing_end_date = 0)))';

		$sql_parameters = array();
		if ($module_cat)
			$sql_parameters['categories'] = $home_modules[$module_cat]->is_subcategories_content_displayed() ? CategoriesService::get_authorized_categories($home_modules[$module_cat]->get_id_category(), $module_config->get_summary_displayed_to_guests(), $module_name) : array($home_modules[$module_cat]->get_id_category());
		else
			$sql_parameters['categories'] = CategoriesService::get_authorized_categories(Category::ROOT_CATEGORY, $module->get_configuration()->has_rich_config_parameters() ? $module_config->get_summary_displayed_to_guests() : true, $module_name);

		$items = ItemsService::get_items_manager($module_name)->get_items($sql_condition, $sql_parameters, $home_modules[$page_type]->get_elements_number_displayed(), 0, 'creation_date', Item::DESC);

		$view->put_all(array(
			'C_NO_ITEM'          => count($items) == 0,
			'C_VIEWS_NUMBER'     => $module_config->get_views_number_enabled(),
			'MODULE_NAME'        => $module_name,
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
				'ITEMS_PER_ROW'      => $module_config->get_items_per_row()
			));
		}

		if ($module_cat)
		{
			$category = CategoriesService::get_categories_manager($module_name)->get_categories_cache()->get_category($home_modules[$module_cat]->get_id_category());
			$view->put_all(array(
				'C_CATEGORY'      => true,
				'L_MODULE_TITLE'  => ModulesManager::get_module($module_name)->get_configuration()->get_name(),
				'L_CATEGORY_NAME' => $category->get_name()
			));
		}
		else
		{
			$view->put_all(array(
				'L_MODULE_TITLE' => ModulesManager::get_module($module_name)->get_configuration()->get_name(),
			));
		}

		foreach ($items as $item)
		{
			$view->assign_block_vars('items', $item->get_template_vars());
		}

		return $view;
	}
}
?>
