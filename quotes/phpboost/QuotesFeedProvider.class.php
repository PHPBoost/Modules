<?php
/**
 * @copyright   &copy; 2005-2025 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 06 01
 * @since       PHPBoost 5.0 - 2016 02 18
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class QuotesFeedProvider implements FeedProvider
{
	public function get_feeds_list()
	{
		return CategoriesService::get_categories_manager('quotes')->get_feeds_categories_module()->get_feed_list();
	}

	public function get_feed_data_struct($idcat = 0, $name = '')
	{
		$module_id = 'quotes';
		if (CategoriesService::get_categories_manager($module_id)->get_categories_cache()->category_exists($idcat))
		{
			$now = new Date();
			$querier = PersistenceContext::get_querier();

			$category = CategoriesService::get_categories_manager($module_id)->get_categories_cache()->get_category($idcat);

			$site_name = GeneralConfig::load()->get_site_name();
			$site_name = $idcat != Category::ROOT_CATEGORY ? $site_name . ' : ' . $category->get_name() : $site_name;

			$feed_module_name = LangLoader::get_message('quotes.feed.name', 'common', 'quotes');
			$data = new FeedData();
			$data->set_title($feed_module_name . ' - ' . $site_name);
			$data->set_date(new Date());
			$data->set_link(SyndicationUrlBuilder::rss('quotes', $idcat));
			$data->set_host(HOST);
			$data->set_desc($feed_module_name . ' - ' . $site_name);
			$data->set_lang(LangLoader::get_message('common.xml.lang', 'common-lang'));
			$data->set_auth_bit(Category::READ_AUTHORIZATIONS);

			$categories = CategoriesService::get_categories_manager($module_id)->get_children($idcat, new SearchCategoryChildrensOptions(), true);
			$ids_categories = array_keys($categories);

			$result = $querier->select('SELECT *
			FROM ' . QuotesSetup::$quotes_table . ' quote
			LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = quote.author_user_id
			LEFT JOIN '. QuotesSetup::$quotes_cats_table .' cat ON cat.id = quote.id_category
			WHERE approved = 1
			AND id_category IN :cats_ids
			ORDER BY writer ASC', array(
				'cats_ids' => $ids_categories,
				'timestamp_now' => $now->get_timestamp()
			));

			while ($row = $result->fetch())
			{
				$item = new QuotesItem();
				$item->set_properties($row);

				$category = $categories[$item->get_id_category()];

				$link = QuotesUrlBuilder::display_writer_items($category->get_id(), $category->get_rewrited_name() ? $category->get_rewrited_name() : 'root', $item->get_id(), $item->get_rewrited_writer());

				$feed_item = new FeedItem();
				$feed_item->set_title($item->get_writer());
				$feed_item->set_link($link);
				$feed_item->set_guid($link);
				$feed_item->set_desc(FormatingHelper::second_parse($item->get_content()));
				$feed_item->set_date($item->get_creation_date());
				$feed_item->set_auth(CategoriesService::get_categories_manager($module_id)->get_heritated_authorizations($category->get_id(), Category::READ_AUTHORIZATIONS, Authorizations::AUTH_PARENT_PRIORITY));
				$data->add_item($feed_item);
			}
			$result->dispose();

			return $data;
		}
	}
}
?>
