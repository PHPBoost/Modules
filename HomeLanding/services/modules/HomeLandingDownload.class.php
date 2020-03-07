<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 03 06
 * @since       PHPBoost 5.2 - 2020 03 06
*/

class HomeLandingDownload
{
    public static function get_download_cat_view()
	{
		$now = new Date();
		$tpl = new FileTemplate('HomeLanding/pagecontent/download-cat.tpl');
		$download_config = DownloadConfig::load();
		$config = HomeLandingConfig::load();
        $modules = HomeLandingModulesList::load();

		$categories_id = $modules[HomeLandingConfig::MODULE_DOWNLOAD_CATEGORY]->is_subcategories_content_displayed() ? CategoriesService::get_authorized_categories($modules[HomeLandingConfig::MODULE_DOWNLOAD_CATEGORY]->get_id_category(), $download_config->is_summary_displayed_to_guests(), HomeLandingConfig::MODULE_DOWNLOAD) : array($modules[HomeLandingConfig::MODULE_DOWNLOAD_CATEGORY]->get_id_category());

		$result = PersistenceContext::get_querier()->select('SELECT download.*, member.*, com.number_comments, notes.average_notes, notes.number_notes, note.note
		FROM ' . DownloadSetup::$download_table . ' download
		LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = download.author_user_id
		LEFT JOIN ' . DB_TABLE_COMMENTS_TOPIC . ' com ON com.id_in_module = download.id AND com.module_id = \'download\'
		LEFT JOIN ' . DB_TABLE_AVERAGE_NOTES . ' notes ON notes.id_in_module = download.id AND notes.module_name = \'download\'
		LEFT JOIN ' . DB_TABLE_NOTE . ' note ON note.id_in_module = download.id AND note.module_name = \'download\' AND note.user_id = :user_id
		WHERE (approbation_type = 1 OR (approbation_type = 2 AND start_date < :timestamp_now AND (end_date > :timestamp_now OR end_date = 0))) AND id_category IN :categories_id
		ORDER BY download.creation_date DESC
		LIMIT :download_cat_limit', array(
			'user_id' => AppContext::get_current_user()->get_id(),
			'timestamp_now' => $now->get_timestamp(),
			'categories_id' => $categories_id,
			'download_cat_limit' => $modules[HomeLandingConfig::MODULE_DOWNLOAD_CATEGORY]->get_elements_number_displayed()
		));

		$category = CategoriesService::get_categories_manager(HomeLandingConfig::MODULE_DOWNLOAD)->get_categories_cache()->get_category($modules[HomeLandingConfig::MODULE_DOWNLOAD_CATEGORY]->get_id_category());
		$tpl->put_all(array(
			'DOWNLOAD_CAT_POSITION' => $config->get_module_position_by_id(HomeLandingConfig::MODULE_DOWNLOAD_CATEGORY),
			'CATEGORY_NAME' => $category->get_name(),
			'C_NO_DOWNLOAD_ITEM' => $result->get_rows_count() == 0,
			'C_DISPLAY_GRID_VIEW' => $download_config->get_display_type() == DownloadConfig::GRID_VIEW,
			'C_DISPLAY_TABLE' => $download_config->get_display_type() == DownloadConfig::TABLE_VIEW,
			'COL_NBR' => $download_config->get_items_per_row()
		));

		while ($row = $result->fetch())
		{
			$file = new DownloadFile();
			$file->set_properties($row);

			$contents = @strip_tags(FormatingHelper::second_parse($file->get_contents()));
			$short_contents = @strip_tags(FormatingHelper::second_parse($file->get_summary()));
			$nb_char = $modules[HomeLandingConfig::MODULE_DOWNLOAD_CATEGORY]->get_characters_number_displayed();
			$description = trim(TextHelper::substr($short_contents, 0, $nb_char));
			$cut_contents = trim(TextHelper::substr($contents, 0, $nb_char));

			$tpl->assign_block_vars('item', array_merge($file->get_array_tpl_vars(), array(
				'C_DESCRIPTION' => $file->get_summary(),
				'C_READ_MORE' => $file->get_summary() ? ($description != $short_contents) : ($cut_contents != $contents),
				'DATE' => $file->get_creation_date()->format(Date::FORMAT_DAY_MONTH_YEAR),
				'DESCRIPTION' => $description,
				'CONTENTS' => $cut_contents
			)));
		}
		$result->dispose();

        return $tpl;
	}

    public static function get_download_view()
	{
		$now = new Date();
		$tpl = new FileTemplate('HomeLanding/pagecontent/download.tpl');
		$download_config = DownloadConfig::load();
		$config = HomeLandingConfig::load();
        $modules = HomeLandingModulesList::load();

		$authorized_categories = CategoriesService::get_authorized_categories(Category::ROOT_CATEGORY, $download_config->is_summary_displayed_to_guests(), HomeLandingConfig::MODULE_DOWNLOAD);

		$result = PersistenceContext::get_querier()->select('SELECT download.*, member.*, notes.average_notes, notes.number_notes, note.note, cat.rewrited_name AS rewrited_name_cat
		FROM ' . PREFIX . 'download download
		LEFT JOIN ' . PREFIX . 'download_cats cat ON cat.id = download.id_category
		LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = download.author_user_id
		LEFT JOIN ' . DB_TABLE_AVERAGE_NOTES . ' notes ON notes.id_in_module = download.id AND notes.module_name = \'download\'
		LEFT JOIN ' . DB_TABLE_NOTE . ' note ON note.id_in_module = download.id AND note.module_name = \'download\' AND note.user_id = :user_id
		WHERE (approbation_type = 1 OR (approbation_type = 2 AND start_date < :timestamp_now AND (end_date > :timestamp_now OR end_date = 0))) AND id_category IN :authorized_categories
		ORDER BY download.creation_date DESC
		LIMIT :download_limit', array(
			'authorized_categories' => $authorized_categories,
			'user_id' => AppContext::get_current_user()->get_id(),
			'timestamp_now' => $now->get_timestamp(),
			'download_limit' => $modules[HomeLandingConfig::MODULE_DOWNLOAD]->get_elements_number_displayed()
		));

		while ($row = $result->fetch())
		{
			$download = new DownloadFile();
			$download->set_properties($row);

			$tpl->put_all(array(
				'DOWNLOAD_POSITION' => $config->get_module_position_by_id(HomeLandingConfig::MODULE_DOWNLOAD),
				'C_DISPLAY_GRID_VIEW' => $download_config->get_display_type() == DownloadConfig::GRID_VIEW,
				'C_DISPLAY_TABLE' => $download_config->get_display_type() == DownloadConfig::TABLE_VIEW,
				'COL_NBR' => $download_config->get_items_per_row()
			));
			$tpl->assign_block_vars('item', $download->get_array_tpl_vars());
		}
		$result->dispose();

		return $tpl;
	}
}
?>
