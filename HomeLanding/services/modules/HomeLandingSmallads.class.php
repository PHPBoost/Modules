<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 02 22
 * @since       PHPBoost 5.2 - 2021 01 25
*/

class HomeLandingSmallads
{
    public static function get_smallads_cat_view()
	{
        $now = new Date();

        $module_config = SmalladsConfig::load();
		$home_config   = HomeLandingConfig::load();
        $modules       = HomeLandingModulesList::load();
        $module_name   = HomeLandingConfig::MODULE_SMALLADS;
        $module_cat    = HomeLandingConfig::MODULE_SMALLADS_CATEGORY;

        $theme_id = AppContext::get_current_user()->get_theme();
        if (file_exists(PATH_TO_ROOT . '/templates/' . $theme_id . '/modules/HomeLanding/pagecontent/' . $module_cat . '.tpl'))
			$view = new FileTemplate('/templates/' . $theme_id . '/modules/HomeLanding/pagecontent/' . $module_cat . '.tpl');
        elseif (file_exists(PATH_TO_ROOT . '/HomeLanding/templates/pagecontent/' . $module_cat . '.tpl'))
			$view = new FileTemplate('/HomeLanding/templates/pagecontent/' . $module_cat . '.tpl');
		else
            $view = new FileTemplate('HomeLanding/pagecontent/items.tpl');

        $home_lang = LangLoader::get_all_langs('HomeLanding');
        $module_lang = LangLoader::get_all_langs($module_name);
        $view->add_lang(array_merge($home_lang, $module_lang));

        $categories_id = $modules[$module_cat]->is_subcategories_content_displayed() ? CategoriesService::get_authorized_categories($modules[$module_cat]->get_id_category(), $module_config->are_summaries_displayed_to_guests(), $module_name) : array($modules[$module_cat]->get_id_category());

        $result = PersistenceContext::get_querier()->select('SELECT smallads.*, member.*
        FROM ' . SmalladsSetup::$smallads_table . ' smallads
        LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = smallads.author_user_id
        WHERE (published = 1 OR (published = 2 AND publishing_start_date < :timestamp_now AND (publishing_end_date > :timestamp_now OR publishing_end_date = 0)))
        AND id_category IN :categories_id
        AND completed = 0
        ORDER BY smallads.update_date DESC
        LIMIT :smallads_cat_limit', array(
            'timestamp_now' => $now->get_timestamp(),
            'categories_id' => $categories_id,
            'smallads_cat_limit' => $modules[$module_cat]->get_elements_number_displayed()
        ));

        $category = CategoriesService::get_categories_manager($module_name)->get_categories_cache()->get_category($modules[$module_cat]->get_id_category());
        $view->put_all(array(
            'C_CATEGORY'         => true,
            'C_NO_ITEM'          => $result->get_rows_count() == 0,
            'C_GRID_VIEW'        => $module_config->get_display_type() == SmalladsConfig::GRID_VIEW,
            'C_LIST_VIEW'        => $module_config->get_display_type() == SmalladsConfig::LIST_VIEW,
            'C_TABLE_VIEW'        => $module_config->get_display_type() == SmalladsConfig::TABLE_VIEW,
			'C_VIEWS_NUMBER'     => true,
			'C_AUTHOR_DISPLAYED' => true,
            'MODULE_POSITION'    => $home_config->get_module_position_by_id($module_name),
            'MODULE_NAME'        => $module_name,
            'ITEMS_PER_ROW'      => $module_config->get_items_per_row(),
            'L_MODULE_TITLE'     => ModulesManager::get_module($module_name)->get_configuration()->get_name(),
            'L_CATEGORY_NAME'    => $category->get_name(),
        ));

		while ($row = $result->fetch())
		{
			$item = new SmalladsItem();
			$item->set_properties($row);

			$view->assign_block_vars('items', array_merge($item->get_template_vars(), array(
                'C_SEVERAL_VIEWS' => $item->get_views_number() > 1,
            )));
		}
		$result->dispose();

        return $view;
	}

    public static function get_smallads_view()
	{
        $now = new Date();

		$module_config = SmalladsConfig::load();
		$home_config   = HomeLandingConfig::load();
        $modules       = HomeLandingModulesList::load();
        $module_name   = HomeLandingConfig::MODULE_SMALLADS;

        $theme_id = AppContext::get_current_user()->get_theme();
        if (file_exists(PATH_TO_ROOT . '/templates/' . $theme_id . '/modules/HomeLanding/pagecontent/' . $module_name . '.tpl'))
			$view = new FileTemplate('/templates/' . $theme_id . '/modules/HomeLanding/pagecontent/' . $module_name . '.tpl');
        elseif (file_exists(PATH_TO_ROOT . '/HomeLanding/templates/pagecontent/' . $module_name . '.tpl'))
			$view = new FileTemplate('/HomeLanding/templates/pagecontent/' . $module_name . '.tpl');
		else
            $view = new FileTemplate('HomeLanding/pagecontent/items.tpl');

        $home_lang = LangLoader::get_all_langs('HomeLanding');
        $module_lang = LangLoader::get_all_langs($module_name);
        $view->add_lang(array_merge($home_lang, $module_lang));

		$authorized_categories = CategoriesService::get_authorized_categories(Category::ROOT_CATEGORY, $module_config->are_summaries_displayed_to_guests(), $module_name);

		$result = PersistenceContext::get_querier()->select('SELECT smallads.*, member.*, cat.rewrited_name AS rewrited_name_cat
		FROM ' . PREFIX . 'smallads smallads
		LEFT JOIN ' . PREFIX . 'smallads_cats cat ON cat.id = smallads.id_category
		LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = smallads.author_user_id
		WHERE (published = 1 OR (published = 2 AND publishing_start_date < :timestamp_now AND (publishing_end_date > :timestamp_now OR publishing_end_date = 0)))
        AND id_category IN :authorized_categories
        AND completed = 0
		ORDER BY smallads.update_date DESC
		LIMIT :smallads_limit', array(
			'authorized_categories' => $authorized_categories,
			'user_id' => AppContext::get_current_user()->get_id(),
			'timestamp_now' => $now->get_timestamp(),
			'smallads_limit' => $modules[$module_name]->get_elements_number_displayed()
		));

		$view->put_all(array(
			'C_NO_ITEM'          => $result->get_rows_count() == 0,
            'C_GRID_VIEW'        => $module_config->get_display_type() == SmalladsConfig::GRID_VIEW,
            'C_LIST_VIEW'        => $module_config->get_display_type() == SmalladsConfig::LIST_VIEW,
            'C_TABLE_VIEW'        => $module_config->get_display_type() == SmalladsConfig::TABLE_VIEW,
			'C_VIEWS_NUMBER'     => true,
			'C_AUTHOR_DISPLAYED' => true,
            'MODULE_POSITION'    => $home_config->get_module_position_by_id($module_name),
			'MODULE_NAME'        => $module_name,
        	'ITEMS_PER_ROW'      => $module_config->get_items_per_row(),
		    'L_MODULE_TITLE'     => ModulesManager::get_module($module_name)->get_configuration()->get_name(),
        ));

		while ($row = $result->fetch())
		{
			$item = new SmalladsItem();
			$item->set_properties($row);

			$view->assign_block_vars('items', array_merge($item->get_template_vars(), array(
                'C_SEVERAL_VIEWS' => $item->get_views_number() > 1,
            )));
		}
		$result->dispose();

		return $view;
	}
}
?>
