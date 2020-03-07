<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 03 06
 * @since       PHPBoost 5.2 - 2020 03 06
*/

class HomeLandingArticles
{
    public static function get_articles_cat_view()
	{
        $now = new Date();
		$tpl = new FileTemplate('HomeLanding/pagecontent/articles-cat.tpl');
		$articles_config = ArticlesConfig::load();
		$config = HomeLandingConfig::load();
        $modules = HomeLandingModulesList::load();

		$categories_id = $modules[HomeLandingConfig::MODULE_ARTICLES_CATEGORY]->is_subcategories_content_displayed() ? CategoriesService::get_authorized_categories($modules[HomeLandingConfig::MODULE_ARTICLES_CATEGORY]->get_id_category(), $articles_config->are_descriptions_displayed_to_guests(), HomeLandingConfig::MODULE_ARTICLES) : array($modules[HomeLandingConfig::MODULE_ARTICLES_CATEGORY]->get_id_category());

		$result = PersistenceContext::get_querier()->select('SELECT articles.*, member.*, com.number_comments, notes.average_notes, notes.number_notes, note.note
		FROM ' . ArticlesSetup::$articles_table . ' articles
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
			'articles_cat_limit' => $modules[HomeLandingConfig::MODULE_ARTICLES_CATEGORY]->get_elements_number_displayed()
		));

		$category = CategoriesService::get_categories_manager(HomeLandingConfig::MODULE_ARTICLES)->get_categories_cache()->get_category($modules[HomeLandingConfig::MODULE_ARTICLES_CATEGORY]->get_id_category());
		$tpl->put_all(array(
			'ARTICLES_CAT_POSITION' => $config->get_module_position_by_id(HomeLandingConfig::MODULE_ARTICLES_CATEGORY),
			'CATEGORY_NAME' => $category->get_name(),
			'C_NO_ARTICLES_ITEM' => $result->get_rows_count() == 0,
			'C_DISPLAY_GRID_VIEW' => $articles_config->get_display_type() == ArticlesConfig::GRID_VIEW,
			'COL_NBR' => $articles_config->get_categories_number_per_row()
		));

		while ($row = $result->fetch())
		{
			$article = new Article();
			$article->set_properties($row);

			$contents = @strip_tags(FormatingHelper::second_parse($article->get_contents()));
			$short_contents = @strip_tags(FormatingHelper::second_parse($article->get_description()));
			$nb_char = $modules[HomeLandingConfig::MODULE_ARTICLES_CATEGORY]->get_characters_number_displayed();
			$description = trim(TextHelper::substr($short_contents, 0, $nb_char));
			$cut_contents = trim(TextHelper::substr($contents, 0, $nb_char));

			$tpl->assign_block_vars('item', array_merge($article->get_array_tpl_vars(), array(
				'C_DESCRIPTION' => $article->get_description(),
				'C_READ_MORE' => $article->get_description() ? ($description != $short_contents) : ($cut_contents != $contents),
				'DATE' => $article->get_creation_date()->format(Date::FORMAT_DAY_MONTH_YEAR),
				'DESCRIPTION' => $description,
				'CONTENTS' => $cut_contents
			)));
		}
		$result->dispose();

        return $tpl;
	}

    public static function get_articles_view()
	{
        $now = new Date();
		$tpl = new FileTemplate('HomeLanding/pagecontent/articles.tpl');
		$articles_config = ArticlesConfig::load();
        $config = HomeLandingConfig::load();
        $modules = HomeLandingModulesList::load();

		$authorized_categories = CategoriesService::get_authorized_categories(Category::ROOT_CATEGORY, $articles_config->are_descriptions_displayed_to_guests(), HomeLandingConfig::MODULE_ARTICLES);

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
			'articles_limit' => $modules[HomeLandingConfig::MODULE_ARTICLES]->get_elements_number_displayed()
		));

		while ($row = $result->fetch())
		{
			$article = new Article();
			$article->set_properties($row);

			$tpl->assign_block_vars('item', $article->get_array_tpl_vars());
			$tpl->put_all(array(
				'DATE_DAY' => strftime('%d', $article->get_creation_date()->get_timestamp()),
				'DATE_MONTH_A' => strftime('%b', $article->get_creation_date()->get_timestamp()),
				'ARTICLES_POSITION' => $config->get_module_position_by_id(HomeLandingConfig::MODULE_ARTICLES),
				'C_DISPLAY_GRID_VIEW' => $articles_config->get_display_type() == ArticlesConfig::GRID_VIEW,
				'ITEMS_PER_ROW' => $articles_config->get_items_number_per_row(),
			));
		}
		$result->dispose();

		return $tpl;
	}
}
?>
