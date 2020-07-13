<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 07 13
 * @since       PHPBoost 5.2 - 2020 03 06
*/

class HomeLandingRss
{
    public static function get_rss_view()
	{
        $theme_id = AppContext::get_current_user()->get_theme();
        if (file_exists(PATH_TO_ROOT . '/templates/' . $theme_id . '/modules/HomeLanding/pagecontent/rssreader.tpl'))
			$view = new FileTemplate('/templates/' . $theme_id . '/modules/HomeLanding/pagecontent/rssreader.tpl');
        else
		    $view = new FileTemplate('HomeLanding/pagecontent/rssreader.tpl');

        $home_lang = LangLoader::get('common', 'HomeLanding');
        $view->add_lang($home_lang);

		$home_config = HomeLandingConfig::load();
        $modules     = HomeLandingModulesList::load();
        $module_name = HomeLandingConfig::MODULE_RSS;

        $rss_number  = $modules[$module_name]->get_elements_number_displayed();
        $char_number = $modules[$module_name]->get_characters_number_displayed();
        $xml_url     = $home_config->get_rss_xml_url();

        // $time_renew = time() + (60*60);
        // $d_actuelle = date('H:i');
        // $d_renew = date('H:i', $time_renew);

        $view->put_all(array(
            'SITE_TITLE' => $home_config->get_rss_site_name(),
            'SITE_URL' => $home_config->get_rss_site_url(),
            'MODULE_POSITION' => $home_config->get_module_position_by_id($module_name),
        ));

        if (empty($xml_url))
        {
            $view->put_all(array(
                'C_RSS_FILE' => false,
                'NO_RSS_FILE' => $home_lang['link.no.xml.file']
            ));
        }
        else
        {
            $check_url = curl_init();
            curl_setopt($check_url, CURLOPT_URL, $xml_url);
            curl_setopt($check_url, CURLOPT_RETURNTRANSFER, 1);
            $output = curl_exec($check_url);
            curl_close($check_url);

            if(substr($output, 0, 5) !== "<?xml") {
                $view->put_all(array(
                    'C_RSS_FILE' => false,
                    'NO_RSS_FILE' =>  $home_lang['link.not.xml.file']
                ));
            } else {
                // create cache file
                $host = parse_url($xml_url, PHP_URL_HOST);
                $lastname = str_replace(".", "-", $host);
                $path = parse_url($xml_url, PHP_URL_PATH);
                $firstname = preg_replace("~[/.#=?]~", "-", $path);
                $filename = $lastname . $firstname . '.xml';
                $content = file_get_contents($xml_url);
                file_put_contents($filename, $content);

                // Move cache file into cache folder
                $filecache =  PATH_TO_ROOT . '/HomeLanding/templates/rsscache/' . $filename;
                copy($filename, $filecache);
                unlink($filename);

                // Read cache file
                $xml = simplexml_load_file($filecache);
                $items = array();
                $items['title'] = array();
                $items['link']  = array();
                $items['desc']  = array();
                $items['img']   = array();
                $items['date']  = array();

                foreach($xml->channel->item as $i)
                {
                    $items['title'][] = $i->title;
                    $items['link'][]  = $i->link;
                    $items['desc'][]  = $i->description;
                    $items['img'][]   = $i->image;
                    $items['date'][]  = $i->pubDate;
                }

                $nbr_item = $rss_number <= count($items['title']) ? $rss_number : count($items['title']);

                $view->put_all(array(
                    'C_RSS_FILE' => true
                ));

                for($i = 0; $i < $nbr_item ; $i++)
                {
                    $date = strtotime($items['date'][$i]);
                    $item_date = strftime('%d/%m/%Y %Hh%M', $date);
                    $desc = $items['desc'][$i];
                    $cut_desc = strip_tags(trim(substr($desc, 0, $char_number)));
                    $item_img = $items['img'][$i];
                    $view->assign_block_vars('item',array(
                        'TITLE_FEED'  => $items['title'][$i],
                        'LINK_FEED'   => $items['link'][$i],
                        'DATE_FEED'   => $item_date,
                        'DESC'        => $cut_desc,
                        'C_READ_MORE' => $cut_desc != $desc,
                        'C_IMG_FEED'  => !empty($item_img),
                        'IMG_FEED'    => $item_img,
                    ));
                }
            }

        }
        return $view;
	}
}
?>
