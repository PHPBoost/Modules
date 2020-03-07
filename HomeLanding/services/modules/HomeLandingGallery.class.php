<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 03 06
 * @since       PHPBoost 5.2 - 2020 03 06
*/

class HomeLandingGallery
{
    public static function get_gallery_view()
	{
		$tpl = new FileTemplate('HomeLanding/pagecontent/gallery.tpl');
		$authorized_categories = CategoriesService::get_authorized_categories(Category::ROOT_CATEGORY, true, HomeLandingConfig::MODULE_GALLERY, 'id_category');
		$gallery_config = GalleryConfig::load();
		$config = HomeLandingConfig::load();
        $modules = HomeLandingModulesList::load();

		$result = PersistenceContext::get_querier()->select("SELECT
			g.id, g.id_category, g.name, g.path, g.timestamp, g.aprob, g.width, g.height, g.user_id, g.views, g.aprob,
			m.display_name, m.groups, m.level,
			notes.average_notes, notes.number_notes, note.note
		FROM " . GallerySetup::$gallery_table . " g
		LEFT JOIN " . PREFIX . "gallery_cats cat ON cat.id = g.id_category
		LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = g.user_id
		LEFT JOIN " . DB_TABLE_COMMENTS_TOPIC . " com ON com.id_in_module = g.id AND com.module_id = 'gallery'
		LEFT JOIN " . DB_TABLE_AVERAGE_NOTES . " notes ON notes.id_in_module = g.id AND notes.module_name = 'gallery'
		LEFT JOIN " . DB_TABLE_NOTE . " note ON note.id_in_module = g.id AND note.module_name = 'gallery' AND note.user_id = :user_id
		WHERE id_category IN :authorized_categories
		ORDER BY g.timestamp DESC
		LIMIT :gallery_limit", array(
			'authorized_categories' => $authorized_categories,
			'gallery_limit' => $modules[HomeLandingConfig::MODULE_GALLERY]->get_elements_number_displayed(),
			'user_id' => AppContext::get_current_user()->get_id(),
		));

		$tpl->put_all(array(
			'GALLERY_POSITION' => $config->get_module_position_by_id(HomeLandingConfig::MODULE_GALLERY),
			'COL_NBR' => $gallery_config->get_columns_number()
		));

		while ($row = $result->fetch())
		{
			$tpl->assign_block_vars('item', array(
				'U_IMG' => PATH_TO_ROOT . '/gallery/pics/' . $row['path'],
				'TITLE' => $row['name'],
				'NB_VIEWS' => $row['views'],
				'U_CATEGORY' => PATH_TO_ROOT . '/gallery/gallery' . url('.php?cat=' . $row['id_category'], '-' . $row['id_category'] . '.php')
			));
		}
		$result->dispose();

        return $tpl;
	}
}
?>
