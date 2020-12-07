<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 12 07
 * @since       PHPBoost 5.1 - 2018 03 15
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class SmalladsTagController extends ModuleController
{
	private $lang;
	private $county_lang;
	private $view;
	private $keyword;

	private $config;
	private $comments_config;
	private $content_management_config;

	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();

		$this->init();

		$this->build_view($request);

		return $this->generate_response($request);
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'smallads');
		$this->county_lang = LangLoader::get('counties', 'smallads');
		$this->view = new FileTemplate('smallads/SmalladsSeveralItemsController.tpl');
		$this->view->add_lang($this->lang);
		$this->view->add_lang($this->county_lang);
		$this->config = SmalladsConfig::load();
		$this->comments_config = CommentsConfig::load();
		$this->content_management_config = ContentManagementConfig::load();
	}

	private function get_keyword()
	{
		if ($this->keyword === null)
		{
			$rewrited_name = AppContext::get_request()->get_getstring('tag', '');
			if (!empty($rewrited_name))
			{
				try {
					$this->keyword = KeywordsService::get_keywords_manager()->get_keyword('WHERE rewrited_name=:rewrited_name', array('rewrited_name' => $rewrited_name));
				} catch (RowNotFoundException $e) {
					$error_controller = PHPBoostErrors::unexisting_page();
   					DispatchManager::redirect($error_controller);
				}
			}
			else
			{
				$error_controller = PHPBoostErrors::unexisting_page();
   				DispatchManager::redirect($error_controller);
			}
		}
		return $this->keyword;
	}

	private function build_view(HTTPRequestCustom $request)
	{
		$now = new Date();

		$authorized_categories = CategoriesService::get_authorized_categories(Category::ROOT_CATEGORY, $this->config->are_summaries_displayed_to_guests());

		$condition = 'WHERE relation.id_keyword = :id_keyword
		AND id_category IN :authorized_categories
		AND (published = 1 OR (published = 2 AND publishing_start_date < :timestamp_now AND (publishing_end_date > :timestamp_now OR publishing_end_date = 0)))';
		$parameters = array(
			'id_keyword' => $this->get_keyword()->get_id(),
			'authorized_categories' => $authorized_categories,
			'timestamp_now' => $now->get_timestamp()
		);

		$result = PersistenceContext::get_querier()->select('SELECT smallads.*, member.*, com.number_comments
			FROM ' . SmalladsSetup::$smallads_table . ' smallads
			LEFT JOIN ' . DB_TABLE_KEYWORDS_RELATIONS . ' relation ON relation.module_id = \'smallads\' AND relation.id_in_module = smallads.id
			LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = smallads.author_user_id
			LEFT JOIN ' . DB_TABLE_COMMENTS_TOPIC . ' com ON com.id_in_module = smallads.id AND com.module_id = \'smallads\'
			' . $condition . '
			ORDER BY smallads.creation_date DESC
			', array_merge($parameters)
		);

		$this->build_sorting_smallad_type();

		$columns_number_displayed_per_line = $this->config->get_displayed_cols_number_per_line();

		$this->view->put_all(array(
			'C_ENABLED_FILTERS'		 => $this->config->are_sort_filters_enabled(),
			'C_ITEMS'                => $result->get_rows_count() > 0,
			'C_TAG'					 => true,
			'C_MORE_THAN_ONE_ITEM'   => $result->get_rows_count() > 1,
			'C_NO_ITEM_AVAILABLE'    => $result->get_rows_count() == 0,
			'C_DISPLAY_GRID_VIEW'    => $this->config->get_display_type() == SmalladsConfig::DISPLAY_GRID_VIEW,
			'C_DISPLAY_LIST_VIEW'    => $this->config->get_display_type() == SmalladsConfig::DISPLAY_LIST_VIEW,
			'C_DISPLAY_TABLE_VIEW'   => $this->config->get_display_type() == SmalladsConfig::DISPLAY_TABLE_VIEW,
			'C_ITEMS_CAT'            => false,
			'C_COMMENTS_ENABLED'     => $this->comments_config->are_comments_enabled(),
			'C_PAGINATION'           => $result->get_rows_count() > $this->config->get_items_number_per_page(),

			'CATEGORY_NAME'          => $this->get_keyword()->get_name(),
			'ITEMS_PER_PAGE'         => $this->config->get_items_number_per_page(),
			'C_SEVERAL_COLUMNS'      => $columns_number_displayed_per_line > 1,
			'COLUMNS_NUMBER'         => $columns_number_displayed_per_line,
			'C_USAGE_TERMS'	         => $this->config->are_usage_terms_displayed(),
			'U_USAGE_TERMS' 		 => SmalladsUrlBuilder::usage_terms()->rel()
		));

		while ($row = $result->fetch())
		{
			$smallad = new Smallad();
			$smallad->set_properties($row);

			$this->build_keywords_view($smallad);

			$this->view->assign_block_vars('items', $smallad->get_array_tpl_vars());
			$this->build_sources_view($smallad);
		}
		$result->dispose();
	}

	private function build_sorting_smallad_type()
	{
		$smallad_types = $this->config->get_smallad_types();
		$type_nbr = count($smallad_types);
		if ($type_nbr)
		{
			$this->view->put('C_TYPES_FILTERS', $type_nbr > 0);

			$i = 1;
			foreach ($smallad_types as $name)
			{
				$this->view->assign_block_vars('types', array(
					'C_SEPARATOR'      => $i < $type_nbr,
					'TYPE_NAME'        => $name,
					'TYPE_NAME_FILTER' => Url::encode_rewrite(TextHelper::strtolower($name)),
				));
				$i++;
			}
		}
	}

	private function build_sources_view(Smallad $smallad)
	{
		$sources = $smallad->get_sources();
		$nbr_sources = count($sources);
		if ($nbr_sources)
		{
			$this->view->put('items.C_SOURCES', $nbr_sources > 0);

			$i = 1;
			foreach ($sources as $name => $url)
			{
				$this->view->assign_block_vars('items.sources', array(
					'C_SEPARATOR' => $i < $nbr_sources,
					'NAME'        => $name,
					'URL'         => $url,
				));
				$i++;
			}
		}
	}

	private function build_keywords_view(Smallad $smallad)
	{
		$keywords = $smallad->get_keywords();
		$nbr_keywords = count($keywords);
		$this->view->put('C_KEYWORDS', $nbr_keywords > 0);

		$i = 1;
		foreach ($keywords as $keyword)
		{
			$this->view->assign_block_vars('keywords', array(
				'C_SEPARATOR' => $i < $nbr_keywords,
				'NAME'        => $keyword->get_name(),
				'URL'         => SmalladsUrlBuilder::display_tag($keyword->get_rewrited_name())->rel(),
			));
			$i++;
		}
	}

	private function check_authorizations()
	{
		if (!(CategoriesAuthorizationsService::check_authorizations()->read()))
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}

	private function generate_response(HTTPRequestCustom $request)
	{
		$response = new SiteDisplayResponse($this->view);

		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->get_keyword()->get_name(), $this->lang['module.title']);
		$graphical_environment->get_seo_meta_data()->set_description(StringVars::replace_vars($this->lang['smallads.seo.description.tag'], array('subject' => $this->get_keyword()->get_name())));
		$graphical_environment->get_seo_meta_data()->set_canonical_url(SmalladsUrlBuilder::display_tag($this->get_keyword()->get_rewrited_name()));

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['module.title'], SmalladsUrlBuilder::home());
		$breadcrumb->add($this->get_keyword()->get_name(), SmalladsUrlBuilder::display_tag($this->get_keyword()->get_rewrited_name()));

		return $response;
	}
}
?>
