<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 03 06
 * @since       PHPBoost 5.2 - 2020 03 06
*/

class HomeLandingRss
{
    public static function get_rss_view()
	{
        $tpl = new FileTemplate('HomeLanding/pagecontent/rssreader.tpl');
		$config = HomeLandingConfig::load();
        $modules = HomeLandingModulesList::load();

        $rss_number = $this->modules[HomeLandingConfig::MODULE_RSS]->get_elements_number_displayed();
        $nb_char = $this->modules[HomeLandingConfig::MODULE_RSS]->get_characters_number_displayed();

        $time_renew = time() + (60*60);
        $d_actuelle = date('H:i');
        $d_renew = date('H:i', $time_renew);
        $xml_url = $this->config->get_rss_xml_url();

        if (empty($xml_url))
        {
            $xml = '';
        }
        else
        {
            $xml = simplexml_load_file($xml_url);
            $items = array();
            $items['title'] = array();
            $items['link'] = array();
            $items['desc'] = array();
            $items['img'] = array();
            $items['date'] = array();

            foreach($xml->channel->item as $i)
            {
                $items['title'][] = utf8_decode($i->title);
                $items['link'][] = utf8_decode($i->link);
                $items['desc'][] = utf8_decode($i->description);
                $items['img'][] = utf8_decode($i->image);
                $items['date'][] = utf8_decode($i->pubDate);
            }

            $nbr_item = $rss_number <= count($items['title']) ? $rss_number : count($items['title']);

            $tpl->put_all(array(
                'SITE_TITLE' => $this->config->get_rss_site_name(),
                'SITE_URL' => $this->config->get_rss_site_url(),
                'RSS_POSITION' => $this->config->get_module_position_by_id(HomeLandingConfig::MODULE_RSS),
            ));

            for($i = 0; $i < $nbr_item ; $i++)
            {
                $date = strtotime($items['date'][$i]);
                $date_feed = strftime('%d/%m/%Y %Hh%M', $date);
                $desc = $items['desc'][$i];
                $cut_desc = strip_tags(trim(substr($desc, 0, $nb_char)));
                $img_feed = $items['img'][$i];
                $tpl->assign_block_vars('rssreader',array(
                    'TITLE_FEED' => $items['title'][$i],
                    'LINK_FEED' => $items['link'][$i],
                    'DATE_FEED' => $date_feed,
                    'DESC' => $cut_desc,
                    'C_READ_MORE' => $cut_desc != $desc,
                    'C_IMG_FEED' => !empty($img_feed),
                    'IMG_FEED' => $img_feed,
                ));
            }
        }
        return $tpl;
	}
}
?>
