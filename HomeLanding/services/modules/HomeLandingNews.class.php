<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 03 06
 * @since       PHPBoost 5.2 - 2020 03 06
*/

class HomeLandingNews
{
    public static function get_news_cat_view()
	{
        $now = new Date();
        $tpl = new FileTemplate('HomeLanding/pagecontent/news-cat.tpl');
        $news_config = NewsConfig::load();
		$config = HomeLandingConfig::load();
        $modules = HomeLandingModulesList::load();

        $categories_id = $modules[HomeLandingConfig::MODULE_NEWS_CATEGORY]->is_subcategories_content_displayed() ? CategoriesService::get_authorized_categories($modules[HomeLandingConfig::MODULE_NEWS_CATEGORY]->get_id_category(), $news_config->are_descriptions_displayed_to_guests(), HomeLandingConfig::MODULE_NEWS) : array($modules[HomeLandingConfig::MODULE_NEWS_CATEGORY]->get_id_category());

        $result = PersistenceContext::get_querier()->select('SELECT news.*, member.*
        FROM ' . NewsSetup::$news_table . ' news
        LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = news.author_user_id
        WHERE (approbation_type = 1 OR (approbation_type = 2 AND start_date < :timestamp_now AND (end_date > :timestamp_now OR end_date = 0))) AND id_category IN :categories_id
        ORDER BY news.creation_date DESC
        LIMIT :news_cat_limit', array(
            'timestamp_now' => $now->get_timestamp(),
            'categories_id' => $categories_id,
            'news_cat_limit' => $modules[HomeLandingConfig::MODULE_NEWS_CATEGORY]->get_elements_number_displayed()
        ));

        $category = CategoriesService::get_categories_manager(HomeLandingConfig::MODULE_NEWS)->get_categories_cache()->get_category($modules[HomeLandingConfig::MODULE_NEWS_CATEGORY]->get_id_category());
        $tpl->put_all(array(
            'NEWS_CAT_POSITION' => $config->get_module_position_by_id(HomeLandingConfig::MODULE_NEWS_CATEGORY),
            'CATEGORY_NAME' => $category->get_name(),
            'C_NO_NEWS_ITEM' => $result->get_rows_count() == 0,
            'C_DISPLAY_GRID_VIEW' => $news_config->get_display_type() == NewsConfig::DISPLAY_GRID_VIEW,
            'COL_NBR' => $news_config->get_number_columns_display_news()
        ));

        while ($row = $result->fetch())
        {
            $news = new News();
            $news->set_properties($row);

            $contents = @strip_tags(FormatingHelper::second_parse($news->get_contents()));
            $short_contents = @strip_tags(FormatingHelper::second_parse($news->get_short_contents()));
            $nb_char = $modules[HomeLandingConfig::MODULE_NEWS_CATEGORY]->get_characters_number_displayed();
            $description = trim(TextHelper::substr($short_contents, 0, $nb_char));
            $cut_contents = trim(TextHelper::substr($contents, 0, $nb_char));

            $tpl->assign_block_vars('item', array_merge($news->get_array_tpl_vars(), array(
                'C_DESCRIPTION' => $news->get_short_contents(),
                'C_READ_MORE' => $news->get_short_contents() ? ($description != $short_contents) : ($cut_contents != $contents),
                'DATE' => $news->get_creation_date()->format(Date::FORMAT_DAY_MONTH_YEAR),
                'DESCRIPTION' => $description,
                'CONTENTS' => $cut_contents
            )));
        }
        $result->dispose();

        return $tpl;
	}

    public static function get_news_view()
	{
        $now = new Date();
		$tpl = new FileTemplate('HomeLanding/pagecontent/news.tpl');
		$news_config = NewsConfig::load();
		$config = HomeLandingConfig::load();
        $modules = HomeLandingModulesList::load();

		$authorized_categories = CategoriesService::get_authorized_categories(Category::ROOT_CATEGORY, $news_config->are_descriptions_displayed_to_guests(), HomeLandingConfig::MODULE_NEWS);

		$result = PersistenceContext::get_querier()->select('SELECT news.*, member.*, cat.rewrited_name AS rewrited_name_cat
		FROM ' . PREFIX . 'news news
		LEFT JOIN ' . PREFIX . 'news_cats cat ON cat.id = news.id_category
		LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = news.author_user_id
		WHERE (approbation_type = 1 OR (approbation_type = 2 AND start_date < :timestamp_now AND (end_date > :timestamp_now OR end_date = 0))) AND id_category IN :authorized_categories
		ORDER BY news.creation_date DESC
		LIMIT :news_limit', array(
			'authorized_categories' => $authorized_categories,
			'user_id' => AppContext::get_current_user()->get_id(),
			'timestamp_now' => $now->get_timestamp(),
			'news_limit' => $modules[HomeLandingConfig::MODULE_NEWS]->get_elements_number_displayed()
		));

		while ($row = $result->fetch())
		{
			$news = new News();
			$news->set_properties($row);

			$tpl->put_all(array(
				'NEWS_POSITION' => $config->get_module_position_by_id(HomeLandingConfig::MODULE_NEWS),
				'C_DISPLAY_CONDENSED_ENABLED' => $news_config->get_display_condensed_enabled() && $news_config->get_display_type() == NewsConfig::DISPLAY_LIST_VIEW,
				'C_DISPLAY_GRID_VIEW' => $news_config->get_display_type() == NewsConfig::DISPLAY_GRID_VIEW,
				'COL_NBR' => $news_config->get_number_columns_display_news()
			));

			$tpl->assign_block_vars('item', $news->get_array_tpl_vars());
		}
		$result->dispose();

		return $tpl;
	}
}
?>
