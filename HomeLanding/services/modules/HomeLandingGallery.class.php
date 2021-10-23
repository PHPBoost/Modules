<?php
/**
 * @copyright   &copy; 2005-2021 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 09 01
 * @since       PHPBoost 5.2 - 2020 03 06
*/

class HomeLandingGallery
{
    public static function get_gallery_view()
	{
		$module_config = GalleryConfig::load();
		$home_config = HomeLandingConfig::load();
        $modules = HomeLandingModulesList::load();
        $module_name   = HomeLandingConfig::MODULE_GALLERY;

        $view = new FileTemplate('HomeLanding/pagecontent/gallery.tpl');

        $home_lang = LangLoader::get('common', 'HomeLanding');
        $module_lang = LangLoader::get('common', $module_name);
        $view->add_lang(array_merge($home_lang, $module_lang, LangLoader::get('common-lang')));

		$authorized_categories = CategoriesService::get_authorized_categories(Category::ROOT_CATEGORY, true, $module_name, 'id_category');

        $result = PersistenceContext::get_querier()->select("SELECT
			g.id, g.id_category, g.name, g.path, g.timestamp, g.aprob, g.width, g.height, g.user_id, g.views, g.aprob,
			m.display_name, m.user_groups, m.level,
			notes.average_notes, notes.notes_number, note.note
		FROM " . PREFIX . "gallery g
		LEFT JOIN " . PREFIX . "gallery_cats cat ON cat.id = g.id_category
		LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = g.user_id
		LEFT JOIN " . DB_TABLE_COMMENTS_TOPIC . " com ON com.id_in_module = g.id AND com.module_id = 'gallery'
		LEFT JOIN " . DB_TABLE_AVERAGE_NOTES . " notes ON notes.id_in_module = g.id AND notes.module_name = 'gallery'
		LEFT JOIN " . DB_TABLE_NOTE . " note ON note.id_in_module = g.id AND note.module_name = 'gallery' AND note.user_id = :user_id
		WHERE id_category IN :authorized_categories
		ORDER BY g.timestamp DESC
		LIMIT :gallery_limit", array(
			'authorized_categories' => $authorized_categories,
			'gallery_limit' => $modules[$module_name]->get_elements_number_displayed(),
			'user_id' => AppContext::get_current_user()->get_id(),
		));

		$view->put_all(array(
            'C_NO_ITEM'       => $result->get_rows_count() == 0,
            'C_MODULE_LINK'   => true,
            'C_VIEWS_ENABLED' => $module_config->is_views_counter_enabled(),
            'MODULE_POSITION' => $home_config->get_module_position_by_id($module_name),
            'MODULE_NAME'     => $module_name,
            'ITEMS_PER_ROW'   => $module_config->get_columns_number(),
		    'L_MODULE_TITLE'  => ModulesManager::get_module($module_name)->get_configuration()->get_name(),
        ));

		while ($row = $result->fetch())
		{
			$view->assign_block_vars('items', array(
				'U_PICTURE'    => Url::to_rel('/gallery/pics/' . $row['path']),
				'TITLE'        => $row['name'],
				'VIEWS_NUMBER' => $row['views'],
				'U_CATEGORY'   => Url::to_rel('/gallery/gallery' . url('.php?cat=' . $row['id_category'], '-' . $row['id_category'] . '.php'))
			));
		}
		$result->dispose();

        return $view;
	}
}
?>
