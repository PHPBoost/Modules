<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 6.1 - 2026 03 21
*/

class FluxLobbyProvider extends DefaultModuleLobbyProvider
{
	public function get_module_id(): string
	{
		return 'flux';
	}

	public function has_categories(): bool
	{
		return false;
	}

	public function get_view(): FileTemplate
	{
		$module_id     = $this->get_module_id();
		$module        = LobbyModulesList::load()[$module_id];
		$module_config = FluxConfig::load();

		$view = $this->get_lobby_template('FluxLobbyProvider.tpl');
		$view->add_lang(array_merge(LangLoader::get_all_langs(), LangLoader::get_all_langs('lobby'), LangLoader::get_all_langs($module_id)));

		$authorized_categories = CategoriesService::get_authorized_categories(Category::ROOT_CATEGORY, true, $module_id);

		$result = PersistenceContext::get_querier()->select(
			'SELECT flux.*, member.*
			FROM ' . FluxSetup::$flux_table . ' flux
			LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = flux.author_user_id
			WHERE id_category IN :categories AND published = 1
			ORDER BY flux.title ASC',
			['categories' => $authorized_categories]
		);

		$view->put_all([
			'C_NEW_WINDOW'    => $module_config->get_new_window(),
			'MODULE_NAME'     => $module_id,
			'MODULE_POSITION' => LobbyConfig::load()->get_module_position_by_id($module_id),
			'L_MODULE_TITLE'  => ModulesManager::get_module($module_id)->get_configuration()->get_name(),
		]);

		$rss_number  = $module->get_elements_number_displayed();
		$char_number = $module->get_characters_number_displayed();

		while ($row = $result->fetch())
		{
			$item = new FluxItem();
			$item->set_properties($row);

			$xml_path = $item->get_xml_path();
			$xml_file = new File(PATH_TO_ROOT . '/' . $xml_path);

			if (!empty($xml_path) && $xml_file->exists() && !empty(file_get_contents(PATH_TO_ROOT . $xml_path)))
			{
				$view->put('C_LAST_FEEDS', $result->get_rows_count() > 0);

				if (FluxService::is_valid_xml(PATH_TO_ROOT . $xml_path))
				{
					$xml       = simplexml_load_file(PATH_TO_ROOT . $xml_path);
					$xml_items = ['title' => [], 'link' => [], 'desc' => [], 'img' => [], 'date' => []];

					foreach ($xml->channel->item as $xi)
					{
						$xml_items['title'][] = $xi->title;
						$xml_items['link'][]  = $xi->link;
						$xml_items['desc'][]  = $xi->description;
						$xml_items['img'][]   = $xi->enclosure->url;
						$xml_items['date'][]  = $xi->pubDate;
					}

					$count = min($rss_number, count($xml_items['title']));

					for ($i = 0; $i < $count; $i++)
					{
						$desc      = @strip_tags(FormatingHelper::second_parse($xml_items['desc'][$i]));
						$cut_desc  = TextHelper::cut_string($desc, (int) $char_number);
						$date      = Date::to_format($xml_items['date'][$i], Date::FORMAT_TIMESTAMP);

						$view->assign_block_vars('feed_items', [
							'C_HAS_THUMBNAIL' => !empty($xml_items['img'][$i]),
							'C_READ_MORE'     => strlen($desc) > $char_number,
							'TITLE'           => $xml_items['title'][$i],
							'ITEM_HOST'       => $item->get_title(),
							'DATE'            => Date::to_format($date, Date::FORMAT_DAY_MONTH_YEAR),
							'SORT_DATE'       => $date,
							'SUMMARY'         => $cut_desc,
							'WORDS_NUMBER'    => max(0, str_word_count($desc) - str_word_count($cut_desc)),
							'U_ITEM'          => $xml_items['link'][$i],
							'U_ITEM_HOST'     => $item->get_item_url(),
							'U_THUMBNAIL'     => Url::to_rel($xml_items['img'][$i]),
						]);
					}
				}
			}
		}
		$result->dispose();

		return $view;
	}
}
?>
