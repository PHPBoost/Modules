<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2023 02 20
 * @since       PHPBoost 6.0 - 2020 10 25
 */

class HomeLandingRecipe
{
    public static function get_recipe_cat_view()
	{
		$now = new Date();

		$module_config = RecipeConfig::load();
		$home_config   = HomeLandingConfig::load();
        $modules       = HomeLandingModulesList::load();
        $module_name   = HomeLandingConfig::MODULE_RECIPE;
        $module_cat    = HomeLandingConfig::MODULE_RECIPE_CATEGORY;

        $theme_id = AppContext::get_current_user()->get_theme();
        if (file_exists(PATH_TO_ROOT . '/templates/' . $theme_id . '/modules/HomeLanding/pagecontent/' . $module_cat . '.tpl'))
			$view = new FileTemplate('/templates/' . $theme_id . '/modules/HomeLanding/pagecontent/' . $module_cat . '.tpl');
        elseif (file_exists(PATH_TO_ROOT . '/HomeLanding/templates/pagecontent/' . $module_cat . '.tpl'))
			$view = new FileTemplate('/HomeLanding/templates/pagecontent/' . $module_cat . '.tpl');
		else
            $view = new FileTemplate('HomeLanding/pagecontent/items.tpl');

        $home_lang = LangLoader::get_module_langs('HomeLanding');
        $module_lang = LangLoader::get_module_langs($module_name);
        $view->add_lang(array_merge(LangLoader::get_all_langs(), $home_lang, $module_lang));

		$categories_id = $modules[$module_cat]->is_subcategories_content_displayed() ? CategoriesService::get_authorized_categories($modules[$module_cat]->get_id_category(), $module_config->is_summary_displayed_to_guests(), $module_name) : array($modules[$module_cat]->get_id_category());

		$result = PersistenceContext::get_querier()->select('SELECT recipe.*, member.*, com.comments_number, notes.average_notes, notes.notes_number, note.note
		FROM ' . PREFIX . 'recipe  recipe
		LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = recipe.author_user_id
		LEFT JOIN ' . DB_TABLE_COMMENTS_TOPIC . ' com ON com.id_in_module = recipe.id AND com.module_id = \'recipe\'
		LEFT JOIN ' . DB_TABLE_AVERAGE_NOTES . ' notes ON notes.id_in_module = recipe.id AND notes.module_name = \'recipe\'
		LEFT JOIN ' . DB_TABLE_NOTE . ' note ON note.id_in_module = recipe.id AND note.module_name = \'recipe\' AND note.user_id = :user_id
		WHERE (published = 1 OR (published = 2 AND publishing_start_date < :timestamp_now AND (publishing_end_date > :timestamp_now OR publishing_end_date = 0))) AND id_category IN :categories_id
		ORDER BY recipe.update_date DESC
		LIMIT :recipe_cat_limit', array(
			'user_id' => AppContext::get_current_user()->get_id(),
			'timestamp_now' => $now->get_timestamp(),
			'categories_id' => $categories_id,
			'recipe_cat_limit' => $modules[$module_cat]->get_elements_number_displayed()
		));

		$category = CategoriesService::get_categories_manager($module_name)->get_categories_cache()->get_category($modules[$module_cat]->get_id_category());
		$view->put_all(array(
            'C_NO_ITEM'       => $result->get_rows_count() == 0,
            'C_CATEGORY'      => true,
			'C_VIEWS_NUMBER'  => true,
            'C_DL_NUMBER'     => true,
			'C_GRID_VIEW'     => $module_config->get_display_type() == RecipeConfig::GRID_VIEW,
			'C_TABLE_VIEW'    => $module_config->get_display_type() == RecipeConfig::TABLE_VIEW,
            'MODULE_POSITION' => $home_config->get_module_position_by_id($module_cat),
			'MODULE_NAME'     => $module_name,
            'ITEMS_PER_ROW'   => $module_config->get_items_per_row(),
			'L_MODULE_TITLE'  => ModulesManager::get_module($module_name)->get_configuration()->get_name(),
			'L_CATEGORY_NAME' => $category->get_name(),
        ));

		while ($row = $result->fetch())
		{
			$item = new RecipeItem();
			$item->set_properties($row);

			$view->assign_block_vars('items', array_merge($item->get_template_vars(), array(
                'C_SEVERAL_VIEWS' => $item->get_views_number() > 1,
			)));
		}
		$result->dispose();

        return $view;
	}

    public static function get_recipe_view()
	{
		$now = new Date();

		$module_config = RecipeConfig::load();
		$home_config   = HomeLandingConfig::load();
        $modules       = HomeLandingModulesList::load();
        $module_name   = HomeLandingConfig::MODULE_RECIPE;

        $theme_id = AppContext::get_current_user()->get_theme();
        if (file_exists(PATH_TO_ROOT . '/templates/' . $theme_id . '/modules/HomeLanding/pagecontent/' . $module_name . '.tpl'))
			$view = new FileTemplate('/templates/' . $theme_id . '/modules/HomeLanding/pagecontent/' . $module_name . '.tpl');
        elseif (file_exists(PATH_TO_ROOT . '/HomeLanding/templates/pagecontent/' . $module_name . '.tpl'))
			$view = new FileTemplate('/HomeLanding/templates/pagecontent/' . $module_name . '.tpl');
		else
            $view = new FileTemplate('HomeLanding/pagecontent/items.tpl');

        $home_lang = LangLoader::get_module_langs('HomeLanding');
        $module_lang = LangLoader::get_module_langs($module_name);
        $view->add_lang(array_merge(LangLoader::get_all_langs($module_name), $home_lang, $module_lang));

		$authorized_categories = CategoriesService::get_authorized_categories(Category::ROOT_CATEGORY, $module_config->is_summary_displayed_to_guests(), $module_name);

		$result = PersistenceContext::get_querier()->select('SELECT recipe.*, member.*, notes.average_notes, notes.notes_number, note.note, cat.rewrited_name AS rewrited_name_cat
		FROM ' . PREFIX . 'recipe recipe
		LEFT JOIN ' . PREFIX . 'recipe_cats cat ON cat.id = recipe.id_category
		LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = recipe.author_user_id
		LEFT JOIN ' . DB_TABLE_AVERAGE_NOTES . ' notes ON notes.id_in_module = recipe.id AND notes.module_name = \'recipe\'
		LEFT JOIN ' . DB_TABLE_NOTE . ' note ON note.id_in_module = recipe.id AND note.module_name = \'recipe\' AND note.user_id = :user_id
		WHERE (published = 1 OR (published = 2 AND publishing_start_date < :timestamp_now AND (publishing_end_date > :timestamp_now OR publishing_end_date = 0))) AND id_category IN :authorized_categories
		ORDER BY recipe.update_date DESC
		LIMIT :recipe_limit', array(
			'authorized_categories' => $authorized_categories,
			'user_id' => AppContext::get_current_user()->get_id(),
			'timestamp_now' => $now->get_timestamp(),
			'recipe_limit' => $modules[$module_name]->get_elements_number_displayed()
		));

		$view->put_all(array(
			'C_NO_ITEM'       => $result->get_rows_count() == 0,
            'C_VIEWS_NUMBER'  => true,
            'C_DL_NUMBER'     => true,
            'C_GRID_VIEW'     => $module_config->get_display_type() == RecipeConfig::GRID_VIEW,
			'C_TABLE_VIEW'    => $module_config->get_display_type() == RecipeConfig::TABLE_VIEW,
            'MODULE_NAME'     => $module_name,
            'MODULE_POSITION' => $home_config->get_module_position_by_id($module_name),
            'ITEMS_PER_ROW'   => $module_config->get_items_per_row(),
			'L_MODULE_TITLE'  => ModulesManager::get_module($module_name)->get_configuration()->get_name(),
        ));

		while ($row = $result->fetch())
		{
			$item = new RecipeItem();
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
