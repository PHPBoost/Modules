<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 02 23
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
        $view->add_lang(array_merge($home_lang, $module_lang));

		$authorized_categories = CategoriesService::get_authorized_categories(Category::ROOT_CATEGORY, true, $module_name, 'id_category');

        $result = PersistenceContext::get_querier()->select('SELECT media.*, mb.display_name, mb.user_groups, mb.level, notes.average_notes, notes.number_notes, note.note
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

            if ($mime_type_tpl == 'video/host')
            {
                $poster = new Url($row['poster']);
                $pathinfo = pathinfo($row['url']);
                $media_id = $pathinfo['basename'];

            	if(strpos($pathinfo['dirname'], 'youtu') !== false)
            	{
            		$watch = 'watch?v=';
            	    if(strpos($media_id, $watch) !== false) {
        				$media_id = substr_replace($media_id, '', 0, 8);
        				list($media_id) = explode('&', $media_id);
                    }
                    $player = 'https://www.youtube.com/embed/';
            	}
            	if(strpos($pathinfo['dirname'], 'vimeo') !== false)
            	{
        			$player = 'https://player.vimeo.com/video/';

            	}
            	if(strpos($pathinfo['dirname'], 'dailymotion') !== false)
            	{
        			$player = 'https://www.dailymotion.com/embed/video/';
            	}
                if(strpos($pathinfo['dirname'], 'odysee') !== false)
                {
                    $explode = explode('/', $pathinfo['dirname']);
			        $media_id = $explode[5] . '/' . $media_id;
                    $player = 'https://odysee.com/$/embed/';
                }
                if(strpos($pathinfo['dirname'], MediaConfig::load()->get_peertube_constant()) !== false)
                {
                    $peertube_link = MediaConfig::load()->get_peertube_constant();
                    $peertube_host = explode('/', $peertube_link);
                    $peertube_host_player = explode('.', $peertube_host[2]);
                    $sliced_name = array_slice($peertube_host_player, 0, -1);
                    $player = implode('.', $sliced_name);
                    $player = $peertube_link . '/videos/embed/';
                }

                $view->assign_block_vars('media_host', array(
                    'PSEUDO' => $row['display_name'],
                    'TITLE' => $row['name'],
                    'ID' => $row['id'],
                    'DATE' => strftime('%d/%m/%Y', $row['timestamp']),
                    'POSTER' => $poster->rel(),
                    'PLAYER' => $player,

                    'U_MEDIA_LINK' => Url::to_rel('/media/' . url('media.php?id=' . $row['id'], 'media-' . $row['id'] . '-' . $row['id_category'] . '+' . Url::encode_rewrite($row['name']) . '.php')),
                    'MEDIA_ID' => $media_id,
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
