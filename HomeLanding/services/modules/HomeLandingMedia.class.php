<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 03 06
 * @since       PHPBoost 5.2 - 2020 03 06
*/

class HomeLandingMedia
{
    public static function get_media_view()
	{
        $tpl = new FileTemplate('HomeLanding/pagecontent/media.tpl');
		$config = HomeLandingConfig::load();
        $modules = HomeLandingModulesList::load();

        $authorized_categories = CategoriesService::get_authorized_categories(Category::ROOT_CATEGORY, true, HomeLandingConfig::MODULE_MEDIA, 'id_category');

        $result = PersistenceContext::get_querier()->select('SELECT media.*, mb.display_name, mb.groups, mb.level, notes.average_notes, notes.number_notes, note.note
        FROM ' . PREFIX . 'media AS media
        LEFT JOIN ' . PREFIX . 'media_cats cat ON cat.id = media.id_category
        LEFT JOIN ' . DB_TABLE_MEMBER . ' AS mb ON media.iduser = mb.user_id
        LEFT JOIN ' . DB_TABLE_AVERAGE_NOTES . ' notes ON notes.id_in_module = media.id AND notes.module_name = \'media\'
        LEFT JOIN ' . DB_TABLE_NOTE . ' note ON note.id_in_module = media.id AND note.module_name = \'media\' AND note.user_id = :user_id
        WHERE id_category IN :authorized_categories
        ORDER BY media.timestamp DESC
        LIMIT :media_limit', array(
            'authorized_categories' => $authorized_categories,
            'user_id' => AppContext::get_current_user()->get_id(),
            'media_limit' => $modules[HomeLandingConfig::MODULE_MEDIA]->get_elements_number_displayed()
        ));

        while ($row = $result->fetch())
        {
            $mime_type_tpl = $row['mime_type'];

            $tpl->put('MEDIA_POSITION', $config->get_module_position_by_id(HomeLandingConfig::MODULE_MEDIA));

            if ($mime_type_tpl == 'application/x-shockwave-flash')
            {
                $poster = new Url($row['poster']);
                $tpl->assign_block_vars('media_swf', array(
                    'PSEUDO' => $row['display_name'],
                    'TITLE' => $row['name'],
                    'ID' => $row['id'],
                    'DATE' => strftime('%d/%m/%Y', $row['timestamp']),
                    'POSTER' => $poster->rel(),

                    'U_MEDIA_LINK' => PATH_TO_ROOT . '/media/' . url('media.php?id=' . $row['id'], 'media-' . $row['id'] . '-' . $row['id_category'] . '+' . Url::encode_rewrite($row['name']) . '.php'),
                    'URL' => $row['url'],
                    'URL_EMBED' => str_replace("v", "embed", $row['url']),
                    'MIME' => $row['mime_type']
                ));
            }
            elseif ($mime_type_tpl == 'video/x-flv')
            {
                $poster = new Url($row['poster']);
                $tpl->assign_block_vars('media_flv', array(
                    'PSEUDO' => $row['display_name'],
                    'TITLE' => $row['name'],
                    'ID' => $row['id'],
                    'DATE' => strftime('%d/%m/%Y', $row['timestamp']),
                    'POSTER' => $poster->rel(),

                    'U_MEDIA_LINK' => PATH_TO_ROOT . '/media/' . url('media.php?id=' . $row['id'], 'media-' . $row['id'] . '-' . $row['id_category'] . '+' . Url::encode_rewrite($row['name']) . '.php'),
                    'URL' => $row['url'],
                    'MIME' => $row['mime_type'],
                    'WIDTH' => $row['width'],
                    'HEIGHT' => $row['height']
                ));
            }
            elseif ($mime_type_tpl == 'video/mp4')
            {
                $poster = new Url($row['poster']);
                $tpl->assign_block_vars('media_mp4', array(
                    'PSEUDO' => $row['display_name'],
                    'TITLE' => $row['name'],
                    'ID' => $row['id'],
                    'DATE' => strftime('%d/%m/%Y', $row['timestamp']),
                    'C_POSTER' => !empty($poster),
                    'POSTER' => $poster->rel(),

                    'U_MEDIA_LINK' => PATH_TO_ROOT . '/media/' . url('media.php?id=' . $row['id'], 'media-' . $row['id'] . '-' . $row['id_category'] . '+' . Url::encode_rewrite($row['name']) . '.php'),
                    'URL' => $row['url'],
                    'MIME' => $row['mime_type'],
                    'WIDTH' => $row['width'],
                    'HEIGHT' => $row['height']
                ));
            }
            elseif ($mime_type_tpl == 'audio/mpeg')
            {
                $poster = new Url($row['poster']);
                $tpl->assign_block_vars('media_mp3', array(
                    'PSEUDO' => $row['display_name'],
                    'TITLE' => $row['name'],
                    'ID' => $row['id'],
                    'DATE' => strftime('%d/%m/%Y', $row['timestamp']),
                    'C_POSTER' => !empty($poster),
                    'POSTER' => $poster->rel(),

                    'U_MEDIA_LINK' => PATH_TO_ROOT . '/media/' . url('media.php?id=' . $row['id'], 'media-' . $row['id'] . '-' . $row['id_category'] . '+' . Url::encode_rewrite($row['name']) . '.php'),
                    'URL' => $row['url'],
                    'MIME' => $row['mime_type'],
                    'WIDTH' => $row['width'],
                    'HEIGHT' => $row['height']
                ));
            }
            else
            {
                $poster = new Url($row['poster']);
                $tpl->assign_block_vars('media_other', array(
                    'PSEUDO' => $row['display_name'],
                    'TITLE' => $row['name'],
                    'ID' => $row['id'],
                    'DATE' => strftime('%d/%m/%Y', $row['timestamp']),
                    'C_POSTER' => !empty($poster),
                    'POSTER' => $poster->rel(),

                    'U_MEDIA_LINK' => PATH_TO_ROOT . '/media/' . url('media.php?id=' . $row['id'], 'media-' . $row['id'] . '-' . $row['id_category'] . '+' . Url::encode_rewrite($row['name']) . '.php'),
                    'URL' => $row['url'],
                    'MIME' => $row['mime_type'],
                    'WIDTH' => $row['width'],
                    'HEIGHT' => $row['height']
                ));
            }
        }
        $result->dispose();

        return $tpl;
	}
}
?>
