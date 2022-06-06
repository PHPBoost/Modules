<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 06 06
 * @since       PHPBoost 6.0 - 2021 11 14
*/

class HomeLandingFlux
{
    public static function get_flux_view()
	{
        $module_config = FluxConfig::load();
		$home_config = HomeLandingConfig::load();
        $modules = HomeLandingModulesList::load();
        $module_name = HomeLandingConfig::MODULE_FLUX;

        $theme_id = AppContext::get_current_user()->get_theme();
        if (file_exists(PATH_TO_ROOT . '/templates/' . $theme_id . '/modules/HomeLanding/pagecontent/' . $module_name . '.tpl'))
			$view = new FileTemplate('/templates/' . $theme_id . '/modules/HomeLanding/pagecontent/' . $module_name . '.tpl');
        elseif (file_exists(PATH_TO_ROOT . '/HomeLanding/templates/pagecontent/' . $module_name . '.tpl'))
			$view = new FileTemplate('/HomeLanding/templates/pagecontent/' . $module_name . '.tpl');

        $home_lang = LangLoader::get_all_langs('HomeLanding');
        $module_lang = LangLoader::get_all_langs($module_name);
        $view->add_lang(array_merge($home_lang, $module_lang));

        $authorized_categories = CategoriesService::get_authorized_categories(Category::ROOT_CATEGORY, '', $module_name);

		$result = PersistenceContext::get_querier()->select('SELECT flux.*, member.*
		FROM '. FluxSetup::$flux_table .' flux
		LEFT JOIN '. DB_TABLE_MEMBER .' member ON member.user_id = flux.author_user_id
		WHERE id_category IN :authorised_categories
		AND published = 1
		ORDER BY flux.title ASC', array(
			'user_id' => AppContext::get_current_user()->get_id(),
			'authorised_categories' => $authorized_categories
		));

        $view->put_all(array(
            'C_NEW_WINDOW'    => $module_config->get_new_window(),
            'MODULE_POSITION' => $home_config->get_module_position_by_id($module_name),
			'MODULE_NAME'     => $module_name,
		    'L_MODULE_TITLE'  => ModulesManager::get_module($module_name)->get_configuration()->get_name(),
        ));

		while ($row = $result->fetch())
		{
			$item = new FluxItem();
			$item->set_properties($row);

			$rss_number = $modules[$module_name]->get_elements_number_displayed();
			$char_number = $modules[$module_name]->get_characters_number_displayed();

			$xml_path = $item->get_xml_path();
			$xml_file = new File(PATH_TO_ROOT . '/' . $xml_path);

			if(!empty($xml_path) && $xml_file->exists())
			{
				$view->put('C_LAST_FEEDS', $result->get_rows_count() > 0);

				$xml = simplexml_load_file(PATH_TO_ROOT . '/' . $item->get_xml_path());
				$xml_items = array();
				$xml_items['title'] = array();
				$xml_items['link']  = array();
				$xml_items['desc']  = array();
				$xml_items['img']   = array();
				$xml_items['date']  = array();

				foreach($xml->channel->item as $i)
				{
					$xml_items['title'][] = $i->title;
					$xml_items['link'][]  = $i->link;
					$xml_items['desc'][]  = $i->description;
					$xml_items['img'][]   = $i->enclosure->url;
					$xml_items['date'][]  = $i->pubDate;
				}

				$xml_items_number = $rss_number <= count($xml_items['title']) ? $rss_number : count($xml_items['title']);

				for($i = 0; $i < $xml_items_number ; $i++)
				{
					$item_host = basename(parse_url($xml_items['link'][$i], PHP_URL_HOST));

					$date = Date::to_format($xml_items['date'][$i], Date::FORMAT_TIMESTAMP);
					$item_date = Date::to_format($date, Date::FORMAT_DAY_MONTH_YEAR);
					$desc = @strip_tags(FormatingHelper::second_parse($xml_items['desc'][$i]));
					$cut_desc = TextHelper::cut_string(@strip_tags(FormatingHelper::second_parse($desc), '<br><br/>'), (int)$modules[$module_name]->get_characters_number_displayed());
					$item_img = $xml_items['img'][$i];
					$words_number = str_word_count($desc) - str_word_count($cut_desc);

					$view->assign_block_vars('feed_items',array(
                        'C_HAS_THUMBNAIL' => !empty($item->get_thumbnail()),
						'C_READ_MORE'     => strlen($desc) > $char_number,

						'TITLE'        => $xml_items['title'][$i],
						'ITEM_HOST'    => $item->get_title(),
						'DATE'         => $item_date,
						'SORT_DATE'    => $date,
						'SUMMARY'      => $cut_desc,
						'WORDS_NUMBER' => $words_number > 0 ? $words_number : '',

						'U_ITEM'      => $xml_items['link'][$i],
						'U_ITEM_HOST' => $item->get_item_url(),
						'U_THUMBNAIL' => Url::to_rel($item->get_thumbnail()),
					));
				}
			}
		}
		$result->dispose();

		return $view;
	}
}
?>
