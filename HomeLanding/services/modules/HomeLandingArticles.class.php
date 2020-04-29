<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 04 29
 * @since       PHPBoost 5.2 - 2020 03 06
*/

class HomeLandingArticles
{
    public static function get_articles_cat_view()
	{
        $now = new Date();

		$module_config = ArticlesConfig::load();
		$home_config   = HomeLandingConfig::load();
        $modules       = HomeLandingModulesList::load();
        $module_name   = HomeLandingConfig::MODULE_ARTICLES;
        $module_cat    = HomeLandingConfig::MODULE_ARTICLES_CATEGORY;

        $theme_id = AppContext::get_current_user()->get_theme();
        if (file_exists(PATH_TO_ROOT . '/templates/' . $theme_id . '/modules/HomeLanding/pagecontent/' . $module_cat . '.tpl'))
			$view = new FileTemplate('/templates/' . $theme_id . '/modules/HomeLanding/pagecontent/' . $module_cat . '.tpl');
		else
            $view = new FileTemplate('HomeLanding/pagecontent/items.tpl');

        $home_lang = LangLoader::get('common', 'HomeLanding');
        $module_lang = LangLoader::get('common', $module_name);
        $view->add_lang($home_lang);
        $view->add_lang($module_lang);

		$categories_id = $modules[$module_cat]->is_subcategories_content_displayed() ? CategoriesService::get_authorized_categories($modules[$module_cat]->get_id_category(), $module_config->get_summary_displayed_to_guests(), $module_name) : array($modules[$module_cat]->get_id_category());

		$result = PersistenceContext::get_querier()->select('SELECT articles.*, member.*, com.number_comments, notes.average_notes, notes.number_notes, note.note
		FROM ' . PREFIX . 'articles articles
		LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = articles.author_user_id
		LEFT JOIN ' . DB_TABLE_COMMENTS_TOPIC . ' com ON com.id_in_module = articles.id AND com.module_id = \'articles\'
		LEFT JOIN ' . DB_TABLE_AVERAGE_NOTES . ' notes ON notes.id_in_module = articles.id AND notes.module_name = \'articles\'
		LEFT JOIN ' . DB_TABLE_NOTE . ' note ON note.id_in_module = articles.id AND note.module_name = \'articles\' AND note.user_id = :user_id
		WHERE (published = 1 OR (published = 2 AND publishing_start_date < :timestamp_now AND (publishing_end_date > :timestamp_now OR publishing_end_date = 0))) AND id_category IN :categories_id
		ORDER BY articles.creation_date DESC
		LIMIT :articles_cat_limit', array(
			'user_id' => AppContext::get_current_user()->get_id(),
			'timestamp_now' => $now->get_timestamp(),
			'categories_id' => $categories_id,
			'articles_cat_limit' => $modules[$module_cat]->get_elements_number_displayed()
		));

		$category = CategoriesService::get_categories_manager($module_name)->get_categories_cache()->get_category($modules[$module_cat]->get_id_category());
        $view->put_all(array(
            'C_NO_ITEM'       => $result->get_rows_count() == 0,
            'C_CATEGORY'      => true,
			'C_VIEWS_NUMBER'  => true,
			'C_GRID_VIEW'     => $module_config->get_display_type() == ArticlesConfig::GRID_VIEW,
			'MODULE_NAME'     => $module_name,
            'MODULE_POSITION' => $home_config->get_module_position_by_id($module_cat),
			'L_MODULE_TITLE'  => LangLoader::get_message('last.'.$module_name.'.cat', 'common', 'HomeLanding') . ': ' . $category->get_name(),
            'L_SEE_ALL_ITEMS' => LangLoader::get_message('link.to.'.$module_name, 'common', 'HomeLanding'),
            'ITEMS_PER_ROW'   => $module_config->get_items_per_row(),
		));

		while ($row = $result->fetch())
		{
			$article = new Article();
			$article->set_properties($row);

            $category_datas = CategoriesService::get_categories_manager($module_name)->get_categories_cache()->get_category($article->get_id_category());
            $view->assign_block_vars('item', array_merge($article->get_template_vars(), array(
                'C_VIEWS_NUMBER'     => true,
                'C_SEVERAL_VIEWS'    => $article->get_views_number() > 1,
                // Waiting for a real dev
                'C_AUTHOR_DISPLAYED' => $module_config->get_author_displayed(),
                'USER_LEVEL_CLASS'   => UserService::get_level_class($article->get_author_user()->get_level()),
                'C_USER_GROUP_COLOR' => !empty(User::get_group_color($article->get_author_user()->get_groups(), $article->get_author_user()->get_level(), true)),
                'USER_GROUP_COLOR'   => User::get_group_color($article->get_author_user()->get_groups(), $article->get_author_user()->get_level(), true),
                'U_AUTHOR_PROFILE'   => UserUrlBuilder::profile($article->get_author_user()->get_id())->rel(),
                'PSEUDO'             => $article->get_author_user()->get_display_name(),
                'CATEGORY_ID'        => $category_datas->get_id(),
                'CATEGORY_NAME'      => $category_datas->get_name(),
                'U_CATEGORY'         => Url::to_rel($module_name . '/' . $category_datas->get_id() . '-' . $category_datas->get_rewrited_name()),
                'U_ITEM'             => Url::to_rel($module_name . '/' . $category_datas->get_id() . '-' . $category_datas->get_rewrited_name() .'/' . $article->get_id() . '-' . $article->get_rewrited_title())
            )));
		}
		$result->dispose();

        return $view;
	}

    public static function get_articles_view()
	{
        $now = new Date();

		$module_config = ArticlesConfig::load();
        $home_config   = HomeLandingConfig::load();
        $modules       = HomeLandingModulesList::load();
        $module_name   = HomeLandingConfig::MODULE_ARTICLES;

        $theme_id = AppContext::get_current_user()->get_theme();
        if (file_exists(PATH_TO_ROOT . '/templates/' . $theme_id . '/modules/HomeLanding/pagecontent/' . $module_name . '.tpl'))
			$view = new FileTemplate('/templates/' . $theme_id . '/modules/HomeLanding/pagecontent/' . $module_name . '.tpl');
		else
            $view = new FileTemplate('HomeLanding/pagecontent/items.tpl');

        $home_lang = LangLoader::get('common', 'HomeLanding');
        $module_lang = LangLoader::get('common', $module_name);
        $view->add_lang($home_lang);
        $view->add_lang($module_lang);

		$authorized_categories = CategoriesService::get_authorized_categories(Category::ROOT_CATEGORY, $module_config->get_summary_displayed_to_guests(), $module_name);

		$result = PersistenceContext::get_querier()->select('SELECT articles.*, member.*, com.number_comments, notes.average_notes, notes.number_notes, note.note, cat.rewrited_name AS rewrited_name_cat
		FROM ' . PREFIX . 'articles articles
		LEFT JOIN ' . PREFIX . 'articles_cats cat ON cat.id = articles.id_category
		LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = articles.author_user_id
		LEFT JOIN ' . DB_TABLE_COMMENTS_TOPIC . ' com ON com.id_in_module = articles.id AND com.module_id = \'articles\'
		LEFT JOIN ' . DB_TABLE_AVERAGE_NOTES . ' notes ON notes.id_in_module = articles.id AND notes.module_name = \'articles\'
		LEFT JOIN ' . DB_TABLE_NOTE . ' note ON note.id_in_module = articles.id AND note.module_name = \'articles\' AND note.user_id = :user_id
		WHERE (published = 1 OR (published = 2 AND publishing_start_date < :timestamp_now AND (publishing_end_date > :timestamp_now OR publishing_end_date = 0))) AND id_category IN :authorized_categories
		ORDER BY articles.creation_date DESC
		LIMIT :articles_limit', array(
			'authorized_categories' => $authorized_categories,
			'user_id' => AppContext::get_current_user()->get_id(),
			'timestamp_now' => $now->get_timestamp(),
			'articles_limit' => $modules[$module_name]->get_elements_number_displayed()
		));

		$view->put_all(array(
			'C_NO_ITEM'       => $result->get_rows_count() == 0,
            'C_VIEWS_NUMBER'  => true,
            'C_GRID_VIEW'     => $module_config->get_display_type() == ArticlesConfig::GRID_VIEW,
            'MODULE_NAME'     => $module_name,
            'MODULE_POSITION' => $home_config->get_module_position_by_id($module_name),
            'L_MODULE_TITLE'  => LangLoader::get_message('last.'.$module_name, 'common', 'HomeLanding'),
            'L_SEE_ALL_ITEMS' => LangLoader::get_message('link.to.'.$module_name, 'common', 'HomeLanding'),
			'ITEMS_PER_ROW'   => $module_config->get_items_per_row(),
		));

        while ($row = $result->fetch())
		{
            $article = new Article();
			$article->set_properties($row);

			$category_datas = CategoriesService::get_categories_manager($module_name)->get_categories_cache()->get_category($article->get_id_category());
            $view->assign_block_vars('item', array_merge($article->get_template_vars(), array(
                'C_VIEWS_NUMBER'     => true,
                'C_SEVERAL_VIEWS'    => $article->get_views_number() >= 2,
            	// Waiting for a real dev
                'C_AUTHOR_DISPLAYED' => $module_config->get_author_displayed(),
                'USER_LEVEL_CLASS'   => UserService::get_level_class($article->get_author_user()->get_level()),
                'C_USER_GROUP_COLOR' => !empty(User::get_group_color($article->get_author_user()->get_groups(), $article->get_author_user()->get_level(), true)),
                'USER_GROUP_COLOR'   => User::get_group_color($article->get_author_user()->get_groups(), $article->get_author_user()->get_level(), true),
                'U_AUTHOR_PROFILE'   => UserUrlBuilder::profile($article->get_author_user()->get_id())->rel(),
                'PSEUDO'             => $article->get_author_user()->get_display_name(),
                'CATEGORY_ID'        => $category_datas->get_id(),
                'CATEGORY_NAME'      => $category_datas->get_name(),
                'U_CATEGORY'         => Url::to_rel($module_name . '/' . $category_datas->get_id() . '-' . $category_datas->get_rewrited_name()),
                'U_ITEM'             => Url::to_rel($module_name . '/' . $category_datas->get_id() . '-' . $category_datas->get_rewrited_name() .'/' . $article->get_id() . '-' . $article->get_rewrited_title())
            )));
		}
		$result->dispose();

		return $view;
	}
}
?>
