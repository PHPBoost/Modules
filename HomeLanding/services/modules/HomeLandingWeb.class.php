<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 03 06
 * @since       PHPBoost 5.2 - 2020 03 06
*/

class HomeLandingWeb
{
    public static function get_web_cat_view()
	{
        $now = new Date();
        $tpl = new FileTemplate('HomeLanding/pagecontent/web-cat.tpl');
        $web_config = WebConfig::load();
		$config = HomeLandingConfig::load();
        $modules = HomeLandingModulesList::load();

        $categories_id = $modules[HomeLandingConfig::MODULE_WEB_CATEGORY]->is_subcategories_content_displayed() ? CategoriesService::get_authorized_categories($modules[HomeLandingConfig::MODULE_WEB_CATEGORY]->get_id_category(), $web_config->are_descriptions_displayed_to_guests(), HomeLandingConfig::MODULE_WEB) : array($modules[HomeLandingConfig::MODULE_WEB_CATEGORY]->get_id_category());

        $result = PersistenceContext::get_querier()->select('SELECT web.*, member.*, com.number_comments, notes.average_notes, notes.number_notes, note.note
        FROM '. WebSetup::$web_table .' web
        LEFT JOIN '. DB_TABLE_MEMBER .' member ON member.user_id = web.author_user_id
        LEFT JOIN ' . DB_TABLE_COMMENTS_TOPIC . ' com ON com.id_in_module = web.id AND com.module_id = \'web\'
        LEFT JOIN ' . DB_TABLE_AVERAGE_NOTES . ' notes ON notes.id_in_module = web.id AND notes.module_name = \'web\'
        LEFT JOIN ' . DB_TABLE_NOTE . ' note ON note.id_in_module = web.id AND note.module_name = \'web\' AND note.user_id = :user_id
        WHERE (approbation_type = 1 OR (approbation_type = 2 AND start_date < :timestamp_now AND (end_date > :timestamp_now OR end_date = 0))) AND partner = 1 AND id_category IN :categories_id
        ORDER BY web.rewrited_name ASC
        LIMIT :web_cat_limit', array(
            'user_id' => AppContext::get_current_user()->get_id(),
            'timestamp_now' => $now->get_timestamp(),
            'categories_id' => $categories_id,
            'web_cat_limit' => $modules[HomeLandingConfig::MODULE_WEB_CATEGORY]->get_elements_number_displayed()
        ));

        $category = CategoriesService::get_categories_manager(HomeLandingConfig::MODULE_WEB)->get_categories_cache()->get_category($modules[HomeLandingConfig::MODULE_WEB_CATEGORY]->get_id_category());
        $tpl->put_all(array(
            'WEB_CAT_POSITION' => $config->get_module_position_by_id(HomeLandingConfig::MODULE_WEB_CATEGORY),
            'CATEGORY_NAME' => $category->get_name(),
            'C_NO_WEB_ITEM' => $result->get_rows_count() == 0
        ));

        while ($row = $result->fetch())
        {
            $link = new WebLink();
            $link->set_properties($row);

            $contents = @strip_tags(FormatingHelper::second_parse($link->get_contents()));
            $summary = @strip_tags(FormatingHelper::second_parse($link->get_summary()));
            $nb_char = $modules[HomeLandingConfig::MODULE_WEB_CATEGORY]->get_characters_number_displayed();
            $description = trim(TextHelper::substr($summary, 0, $nb_char));
            $cut_contents = trim(TextHelper::substr($contents, 0, $nb_char));

            $tpl->assign_block_vars('item', array_merge($link->get_array_tpl_vars(), array(
                'C_DESCRIPTION' => $link->get_summary(),
                'C_READ_MORE' => $link->get_summary() ? ($description != $summary) : ($cut_contents != $contents),
                'DATE' => $link->get_creation_date()->format(Date::FORMAT_DAY_MONTH_YEAR),
                'DESCRIPTION' => $description,
                'CONTENTS' => $cut_contents
            )));
        }
        $result->dispose();

        return $tpl;
	}

    public static function get_web_view()
	{
        $now = new Date();
        $tpl = new FileTemplate('HomeLanding/pagecontent/web.tpl');
        $web_config = WebConfig::load();
		$config = HomeLandingConfig::load();
        $modules = HomeLandingModulesList::load();

        $authorized_categories = CategoriesService::get_authorized_categories(Category::ROOT_CATEGORY, $web_config->are_descriptions_displayed_to_guests(), HomeLandingConfig::MODULE_WEB);

        $result = PersistenceContext::get_querier()->select('SELECT web.*, member.*, cat.rewrited_name AS rewrited_name_cat, notes.average_notes, notes.number_notes, note.note
        FROM ' . PREFIX . 'web web
        LEFT JOIN ' . PREFIX . 'web_cats cat ON cat.id = web.id_category
        LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = web.author_user_id
        LEFT JOIN ' . DB_TABLE_AVERAGE_NOTES . ' notes ON notes.id_in_module = web.id AND notes.module_name = \'web\'
        LEFT JOIN ' . DB_TABLE_NOTE . ' note ON note.id_in_module = web.id AND note.module_name = \'web\'
        WHERE (approbation_type = 1 OR (approbation_type = 2 AND start_date < :timestamp_now AND (end_date > :timestamp_now OR end_date = 0))) AND partner = 1 AND id_category IN :authorized_categories
        ORDER BY web.rewrited_name ASC
        LIMIT :web_limit', array(
            'authorized_categories' => $authorized_categories,
            'timestamp_now' => $now->get_timestamp(),
            'web_limit' => $modules[HomeLandingConfig::MODULE_WEB]->get_elements_number_displayed()
        ));

        while ($row = $result->fetch())
        {
            $web = new WebLink();
            $web->set_properties($row);

            $tpl->put('WEB_POSITION', $config->get_module_position_by_id(HomeLandingConfig::MODULE_WEB));
            $tpl->assign_block_vars('item', $web->get_array_tpl_vars());
        }
        $result->dispose();

		return $tpl;
	}
}
?>
