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

        $categories_id = $modules[HomeLandingConfig::MODULE_NEWS_CATEGORY]->is_subcategories_content_displayed() ? CategoriesService::get_authorized_categories($modules[HomeLandingConfig::MODULE_NEWS_CATEGORY]->get_id_category(), $news_config->is_summary_displayed_to_guests(), HomeLandingConfig::MODULE_NEWS) : array($modules[HomeLandingConfig::MODULE_NEWS_CATEGORY]->get_id_category());

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
            'C_NO_ITEM' => $result->get_rows_count() == 0,
            'C_GRID_VIEW' => $news_config->get_display_type() == NewsConfig::GRID_VIEW,
            'ITEMS_PER_ROW' => $news_config->get_items_per_row()
        ));

        while ($row = $result->fetch())
        {
            $news = new News();
            $news->set_properties($row);

            $contents = @strip_tags(FormatingHelper::second_parse($news->get_contents()));
            $summary = @strip_tags(FormatingHelper::second_parse($news->get_summary()));
            $nb_char = $modules[HomeLandingConfig::MODULE_NEWS_CATEGORY]->get_characters_number_displayed();
            $description = trim(TextHelper::substr($summary, 0, $nb_char));
            $cut_contents = trim(TextHelper::substr($contents, 0, $nb_char));

            $tpl->assign_block_vars('item', array_merge($news->get_array_tpl_vars(), array(
                'C_DESCRIPTION' => $news->get_summary(),
                'C_READ_MORE' => $news->get_summary() ? ($description != $summary) : ($cut_contents != $contents),
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

		$authorized_categories = CategoriesService::get_authorized_categories(Category::ROOT_CATEGORY, $news_config->is_summary_displayed_to_guests(), HomeLandingConfig::MODULE_NEWS);

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
				'C_FULL_ITEM_DISPLAY' => $news_config->get_full_item_display() && $news_config->get_display_type() == NewsConfig::LIST_VIEW,
				'C_GRID_VIEW' => $news_config->get_display_type() == NewsConfig::GRID_VIEW,
				'ITEMS_PER_ROW' => $news_config->get_items_per_row()
			));

			$tpl->assign_block_vars('item', $news->get_array_tpl_vars());
		}
		$result->dispose();

		return $tpl;
	}
}
?>
