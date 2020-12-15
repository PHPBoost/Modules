<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 12 15
 * @since       PHPBoost 5.2 - 2020 03 06
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class HomeLandingWeb
{
    public static function get_web_cat_view()
	{
        $now = new Date();

        $module_config = WebConfig::load();
		$home_config   = HomeLandingConfig::load();
        $modules       = HomeLandingModulesList::load();
        $module_name   = HomeLandingConfig::MODULE_WEB;
        $module_cat    = HomeLandingConfig::MODULE_WEB_CATEGORY;

        $theme_id = AppContext::get_current_user()->get_theme();
        if (file_exists(PATH_TO_ROOT . '/HomeLanding/templates/pagecontent/' . $module_cat . '.tpl'))
			$view = new FileTemplate('/HomeLanding/templates/pagecontent/' . $module_cat . '.tpl');
        elseif (file_exists(PATH_TO_ROOT . '/templates/' . $theme_id . '/modules/HomeLanding/pagecontent/' . $module_cat . '.tpl'))
			$view = new FileTemplate('/templates/' . $theme_id . '/modules/HomeLanding/pagecontent/' . $module_cat . '.tpl');
		else
            $view = new FileTemplate('HomeLanding/pagecontent/items.tpl');

        $home_lang = LangLoader::get('common', 'HomeLanding');
        $module_lang = LangLoader::get('common', $module_name);
        $view->add_lang($home_lang);
        $view->add_lang($module_lang);

        $categories_id = $modules[$module_cat]->is_subcategories_content_displayed() ? CategoriesService::get_authorized_categories($modules[$module_cat]->get_id_category(), $module_config->are_descriptions_displayed_to_guests(), $module_name) : array($modules[$module_cat]->get_id_category());

        $result = PersistenceContext::get_querier()->select('SELECT web.*, member.*, com.number_comments, notes.average_notes, notes.number_notes, note.note
        FROM '. WebSetup::$web_table .' web
        LEFT JOIN '. DB_TABLE_MEMBER .' member ON member.user_id = web.author_user_id
        LEFT JOIN ' . DB_TABLE_COMMENTS_TOPIC . ' com ON com.id_in_module = web.id AND com.module_id = \'web\'
        LEFT JOIN ' . DB_TABLE_AVERAGE_NOTES . ' notes ON notes.id_in_module = web.id AND notes.module_name = \'web\'
        LEFT JOIN ' . DB_TABLE_NOTE . ' note ON note.id_in_module = web.id AND note.module_name = \'web\' AND note.user_id = :user_id
        WHERE (published = 1 OR (published = 2 AND publishing_start_date < :timestamp_now AND (publishing_end_date > :timestamp_now OR publishing_end_date = 0))) AND partner = 1 AND id_category IN :categories_id
        ORDER BY web.rewrited_title ASC
        LIMIT :web_cat_limit', array(
            'user_id' => AppContext::get_current_user()->get_id(),
            'timestamp_now' => $now->get_timestamp(),
            'categories_id' => $categories_id,
            'web_cat_limit' => $modules[$module_cat]->get_elements_number_displayed()
        ));

        $category = CategoriesService::get_categories_manager($module_name)->get_categories_cache()->get_category($modules[$module_cat]->get_id_category());
        $view->put_all(array(
            'C_NO_ITEM'       => $result->get_rows_count() == 0,
            'C_CATEGORY'      => true,
            'C_VIEWS_NUMBER'  => true,
            'C_VISIT'         => true,
            'C_GRID_VIEW'     => $module_config->get_display_type() == WebConfig::GRID_VIEW,
			'C_TABLE_VIEW'    => $module_config->get_display_type() == WebConfig::TABLE_VIEW,
            'MODULE_POSITION' => $home_config->get_module_position_by_id($module_cat),
            'MODULE_NAME'     => $module_name,
            'L_MODULE_TITLE'  => LangLoader::get_message('last.'.$module_name.'.cat', 'common', 'HomeLanding') . ': ' . $category->get_name(),
            'L_SEE_ALL_ITEMS' => LangLoader::get_message('link.to.'.$module_name, 'common', 'HomeLanding'),
            'ITEMS_PER_ROW'   => $module_config->get_items_per_row()
        ));

        while ($row = $result->fetch())
        {
            $link = new WebItem();
            $link->set_properties($row);

            $contents = @strip_tags(FormatingHelper::second_parse($link->get_contents()));
            $summary = @strip_tags(FormatingHelper::second_parse($link->get_real_summary()));
            $nb_char = $modules[$module_cat]->get_characters_number_displayed();
            $description = trim(TextHelper::substr($summary, 0, $nb_char));
            $cut_contents = trim(TextHelper::substr($contents, 0, $nb_char));

            $view->assign_block_vars('items', array_merge($link->get_array_tpl_vars(), array(
                'C_SEVERAL_VIEWS' => $link->get_views_number() > 1,
            )));
        }
        $result->dispose();

        return $view;
	}

    public static function get_web_view()
	{
        $now = new Date();

        $module_config = WebConfig::load();
		$home_config = HomeLandingConfig::load();
        $modules = HomeLandingModulesList::load();
        $module_name   = HomeLandingConfig::MODULE_WEB;

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

        $authorized_categories = CategoriesService::get_authorized_categories(Category::ROOT_CATEGORY, $module_config->are_descriptions_displayed_to_guests(), $module_name);

        $result = PersistenceContext::get_querier()->select('SELECT web.*, member.*, cat.rewrited_name AS rewrited_name_cat, notes.average_notes, notes.number_notes, note.note
        FROM ' . PREFIX . 'web web
        LEFT JOIN ' . PREFIX . 'web_cats cat ON cat.id = web.id_category
        LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = web.author_user_id
        LEFT JOIN ' . DB_TABLE_AVERAGE_NOTES . ' notes ON notes.id_in_module = web.id AND notes.module_name = \'web\'
        LEFT JOIN ' . DB_TABLE_NOTE . ' note ON note.id_in_module = web.id AND note.module_name = \'web\'
        WHERE (published = 1 OR (published = 2 AND publishing_start_date < :timestamp_now AND (publishing_end_date > :timestamp_now OR publishing_end_date = 0))) AND partner = 1 AND id_category IN :authorized_categories
        ORDER BY web.rewrited_title ASC
        LIMIT :web_limit', array(
            'authorized_categories' => $authorized_categories,
            'timestamp_now' => $now->get_timestamp(),
            'web_limit' => $modules[$module_name]->get_elements_number_displayed()
        ));

        $view->put_all(array(
            'C_NO_ITEM'       => $result->get_rows_count() == 0,
			'C_VIEWS_NUMBER'  => true,
            'C_VISIT'         => true,
			'C_GRID_VIEW'     => $module_config->get_display_type() == WebConfig::GRID_VIEW,
			'C_TABLE_VIEW'    => $module_config->get_display_type() == WebConfig::TABLE_VIEW,
            'MODULE_POSITION' => $home_config->get_module_position_by_id($module_name),
			'MODULE_NAME'     => $module_name,
            'L_MODULE_TITLE'  => LangLoader::get_message('last.'.$module_name, 'common', 'HomeLanding'),
            'L_SEE_ALL_ITEMS' => LangLoader::get_message('link.to.'.$module_name, 'common', 'HomeLanding'),
            'ITEMS_PER_ROW'   => $module_config->get_items_per_row(),
		));

        while ($row = $result->fetch())
        {
            $link = new WebItem();
            $link->set_properties($row);

            $view->assign_block_vars('items', array_merge($link->get_array_tpl_vars(), array(
                'C_VISIT'  => true,
                'C_SEVERAL_VIEWS' => $link->get_views_number() > 1,
            )));
        }
        $result->dispose();

		return $view;
	}
}
?>
