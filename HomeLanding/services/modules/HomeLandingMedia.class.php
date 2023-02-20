<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2023 02 20
 * @since       PHPBoost 5.2 - 2020 03 06
*/

class HomeLandingMedia
{
    public static function get_media_view()
	{
        $view = new FileTemplate('HomeLanding/pagecontent/media.tpl');
        $module_config = MediaConfig::load();
		$home_config = HomeLandingConfig::load();
        $modules = HomeLandingModulesList::load();
        $module_name   = HomeLandingConfig::MODULE_MEDIA;

        $home_lang = LangLoader::get_all_langs('HomeLanding');
        $module_lang = LangLoader::get_all_langs($module_name);
        $view->add_lang(array_merge(LangLoader::get_all_langs(), $home_lang, $module_lang));

		$authorized_categories = CategoriesService::get_authorized_categories(Category::ROOT_CATEGORY, true, $module_name, 'id_category');

        $result = PersistenceContext::get_querier()->select('SELECT media.*, mb.display_name, mb.user_groups, mb.level, notes.average_notes, notes.notes_number, note.note
        FROM ' . PREFIX . 'media AS media
        LEFT JOIN ' . PREFIX . 'media_cats cat ON cat.id = media.id_category
        LEFT JOIN ' . DB_TABLE_MEMBER . ' AS mb ON media.author_user_id = mb.user_id
        LEFT JOIN ' . DB_TABLE_AVERAGE_NOTES . ' notes ON notes.id_in_module = media.id AND notes.module_name = \'media\'
        LEFT JOIN ' . DB_TABLE_NOTE . ' note ON note.id_in_module = media.id AND note.module_name = \'media\' AND note.user_id = :user_id
        WHERE id_category IN :authorized_categories AND published = 2
        ORDER BY media.creation_date DESC
        LIMIT :media_limit', array(
            'authorized_categories' => $authorized_categories,
            'user_id' => AppContext::get_current_user()->get_id(),
            'media_limit' => $modules[$module_name]->get_elements_number_displayed()
        ));

        $view->put_all(array(
			'C_ITEMS'     => $result->get_rows_count() > 0,
            'C_LIST_VIEW' => $module_config->get_display_type() == MediaConfig::LIST_VIEW,
            'C_GRID_VIEW' => $module_config->get_display_type() == MediaConfig::GRID_VIEW,

            'MODULE_NAME'     => $module_name,
            'MODULE_POSITION' => $home_config->get_module_position_by_id($module_name),
        	'ITEMS_PER_ROW'   => $module_config->get_items_per_row(),

            'L_MODULE_TITLE'  => ModulesManager::get_module($module_name)->get_configuration()->get_name(),
        ));

        while ($row = $result->fetch())
        {
            $summary = TextHelper::cut_string(@strip_tags(FormatingHelper::second_parse($row['content']), '<br><br/>'), $module_config->get_characters_number_to_cut());

            $view->assign_block_vars('items', array(
                'C_SUMMARY'   => !empty($row['content']),
                'C_AUDIO'     => $row['mime_type'] == 'audio/mpeg',
                'C_HAS_THUMBNAIL' => !empty($row['thumbnail']),

                'PSEUDO'        => $row['display_name'],
                'TITLE'         => $row['title'],
                'ID'            => $row['id'],
                'DATE'          => Date::to_format($row['creation_date'], Date::FORMAT_DAY_MONTH_YEAR),
                'SORT_DATE'     => $row['creation_date'],
                'CATEGORY_NAME' => $row['id_category'] == Category::ROOT_CATEGORY ? $module_lang['common.root'] : CategoriesService::get_categories_manager('media')->get_categories_cache()->get_category($row['id_category'])->get_name(),
                'SUMMARY'       => FormatingHelper::second_parse($summary),

                'U_THUMBNAIL' => Url::to_rel($row['thumbnail']),
                'U_ITEM'      => Url::to_rel('/media/' . url('media.php?id=' . $row['id'], 'media-' . $row['id'] . '-' . $row['id_category'] . '+' . Url::encode_rewrite($row['title']) . '.php')),
            ));
        }
        $result->dispose();

        return $view;
	}
}
?>
