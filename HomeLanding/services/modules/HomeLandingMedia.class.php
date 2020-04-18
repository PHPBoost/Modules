<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 04 18
 * @since       PHPBoost 5.2 - 2020 03 06
*/

class HomeLandingMedia
{
    public static function get_media_view()
	{
        $view = new FileTemplate('HomeLanding/pagecontent/media.tpl');
		$home_config = HomeLandingConfig::load();
        $modules = HomeLandingModulesList::load();
        $module_name   = HomeLandingConfig::MODULE_MEDIA;

        $home_lang = LangLoader::get('common', 'HomeLanding');
        $module_lang = LangLoader::get('common', $module_name);
        $view->add_lang($home_lang);
        $view->add_lang($module_lang);

		$authorized_categories = CategoriesService::get_authorized_categories(Category::ROOT_CATEGORY, true, $module_name, 'id_category');

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
            'media_limit' => $modules[$module_name]->get_elements_number_displayed()
        ));

        $view->put_all(array(
			'C_NO_ITEM'       => $result->get_rows_count() == 0,
            'MODULE_NAME'     => $module_name,
            'MODULE_POSITION' => $home_config->get_module_position_by_id($module_name),
            'L_MODULE_TITLE'  => LangLoader::get_message('last.'.$module_name, 'common', 'HomeLanding'),
            'L_SEE_ALL_ITEMS' => LangLoader::get_message('link.to.'.$module_name, 'common', 'HomeLanding'),
		));

        while ($row = $result->fetch())
        {
            $mime_type_tpl = $row['mime_type'];

            if ($mime_type_tpl == 'application/x-shockwave-flash')
            {
                $poster = new Url($row['poster']);
                $view->assign_block_vars('media_swf', array(
                    'PSEUDO' => $row['display_name'],
                    'TITLE' => $row['name'],
                    'ID' => $row['id'],
                    'DATE' => strftime('%d/%m/%Y', $row['timestamp']),
                    'POSTER' => $poster->rel(),

                    'U_MEDIA_LINK' => Url::to_rel('/media/' . url('media.php?id=' . $row['id'], 'media-' . $row['id'] . '-' . $row['id_category'] . '+' . Url::encode_rewrite($row['name']) . '.php')),
                    'URL' => $row['url'],
                    'URL_EMBED' => str_replace("v", "embed", $row['url']),
                    'MIME' => $row['mime_type']
                ));
            }
            elseif ($mime_type_tpl == 'video/host')
            {
                $poster = new Url($row['poster']);
                $pathinfo = pathinfo($row['url']);
            	$video_id = $pathinfo['basename'];

            	if(strpos($pathinfo['dirname'], 'youtu') !== false)
            	{
            		$watch = 'watch?v=';
            	    if(strpos($video_id, $watch) !== false)
            	        $video_id = substr_replace($video_id, '', 0, 8);

            			$player = 'https://www.youtube.com/embed/';
            	}
            	elseif(strpos($pathinfo['dirname'], 'vimeo') !== false)
            	{
            			$player = 'https://player.vimeo.com/video/';
            	}
            	elseif(strpos($pathinfo['dirname'], 'dailymotion') !== false)
            	{
            			$player = 'https://www.dailymotion.com/embed/video/';
            	}

                $view->assign_block_vars('media_host', array(
                    'PSEUDO' => $row['display_name'],
                    'TITLE' => $row['name'],
                    'ID' => $row['id'],
                    'DATE' => strftime('%d/%m/%Y', $row['timestamp']),
                    'POSTER' => $poster->rel(),

                    'U_MEDIA_LINK' => Url::to_rel('/media/' . url('media.php?id=' . $row['id'], 'media-' . $row['id'] . '-' . $row['id_category'] . '+' . Url::encode_rewrite($row['name']) . '.php')),
                    'MEDIA_ID' => $video_id,
                    'PLAYER' => $player,
                    'WIDTH' => $row['width'],
                    'HEIGHT' => $row['height']
                ));
            }
            elseif ($mime_type_tpl == 'video/x-flv')
            {
                $poster = new Url($row['poster']);
                $view->assign_block_vars('media_flv', array(
                    'PSEUDO' => $row['display_name'],
                    'TITLE' => $row['name'],
                    'ID' => $row['id'],
                    'DATE' => strftime('%d/%m/%Y', $row['timestamp']),
                    'POSTER' => $poster->rel(),

                    'U_MEDIA_LINK' => Url::to_rel('/media/' . url('media.php?id=' . $row['id'], 'media-' . $row['id'] . '-' . $row['id_category'] . '+' . Url::encode_rewrite($row['name']) . '.php')),
                    'URL' => $row['url'],
                    'MIME' => $row['mime_type'],
                    'WIDTH' => $row['width'],
                    'HEIGHT' => $row['height']
                ));
            }
            elseif ($mime_type_tpl == 'video/mp4')
            {
                $poster = new Url($row['poster']);
                $view->assign_block_vars('media_mp4', array(
                    'PSEUDO' => $row['display_name'],
                    'TITLE' => $row['name'],
                    'ID' => $row['id'],
                    'DATE' => strftime('%d/%m/%Y', $row['timestamp']),
                    'C_POSTER' => !empty($poster),
                    'POSTER' => $poster->rel(),

                    'U_MEDIA_LINK' => Url::to_rel('/media/' . url('media.php?id=' . $row['id'], 'media-' . $row['id'] . '-' . $row['id_category'] . '+' . Url::encode_rewrite($row['name']) . '.php')),
                    'URL' => $row['url'],
                    'MIME' => $row['mime_type'],
                    'WIDTH' => $row['width'],
                    'HEIGHT' => $row['height']
                ));
            }
            elseif ($mime_type_tpl == 'audio/mpeg')
            {
                $poster = new Url($row['poster']);
                $view->assign_block_vars('media_mp3', array(
                    'PSEUDO' => $row['display_name'],
                    'TITLE' => $row['name'],
                    'ID' => $row['id'],
                    'DATE' => strftime('%d/%m/%Y', $row['timestamp']),
                    'C_POSTER' => !empty($poster),
                    'POSTER' => $poster->rel(),

                    'U_MEDIA_LINK' => Url::to_rel('/media/' . url('media.php?id=' . $row['id'], 'media-' . $row['id'] . '-' . $row['id_category'] . '+' . Url::encode_rewrite($row['name']) . '.php')),
                    'URL' => $row['url'],
                    'MIME' => $row['mime_type'],
                    'WIDTH' => $row['width'],
                    'HEIGHT' => $row['height']
                ));
            }
            else
            {
                $poster = new Url($row['poster']);
                $view->assign_block_vars('media_other', array(
                    'PSEUDO' => $row['display_name'],
                    'TITLE' => $row['name'],
                    'ID' => $row['id'],
                    'DATE' => strftime('%d/%m/%Y', $row['timestamp']),
                    'C_POSTER' => !empty($poster),
                    'POSTER' => $poster->rel(),

                    'U_MEDIA_LINK' => Url::to_rel('/media/' . url('media.php?id=' . $row['id'], 'media-' . $row['id'] . '-' . $row['id_category'] . '+' . Url::encode_rewrite($row['name']) . '.php')),
                    'URL' => $row['url'],
                    'MIME' => $row['mime_type'],
                    'WIDTH' => $row['width'],
                    'HEIGHT' => $row['height']
                ));
            }
        }
        $result->dispose();

        return $view;
	}
}
?>
