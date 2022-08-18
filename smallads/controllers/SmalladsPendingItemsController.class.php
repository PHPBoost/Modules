<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 03 06
 * @since       PHPBoost 5.1 - 2018 03 15
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class SmalladsPendingItemsController extends DefaultModuleController
{
	private $comments_config;
	private $content_management_config;

	protected function get_template_to_use()
	{
		return new FileTemplate('smallads/SmalladsSeveralItemsController.tpl');
	}

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		$this->check_authorizations();
		$this->build_view($request);
		return $this->generate_response($request);
	}

	private function init()
	{
		$this->comments_config = CommentsConfig::load();
		$this->content_management_config = ContentManagementConfig::load();
	}

	private function build_view(HTTPRequestCustom $request)
	{
		$now = new Date();
		$authorized_categories = CategoriesService::get_authorized_categories(Category::ROOT_CATEGORY, $this->config->are_summaries_displayed_to_guests());

		$condition = 'WHERE id_category IN :authorized_categories
		' . (!CategoriesAuthorizationsService::check_authorizations()->moderation() ? ' AND author_user_id = :user_id' : '') . '
		AND (published = 0 OR (published = 2 AND (publishing_start_date > :timestamp_now OR (publishing_end_date != 0 AND publishing_end_date < :timestamp_now))))
		AND archived = 0';
		$parameters = array(
			'authorized_categories' => $authorized_categories,
			'user_id' => AppContext::get_current_user()->get_id(),
			'timestamp_now' => $now->get_timestamp()
		);

		$result = PersistenceContext::get_querier()->select('SELECT smallads.*, member.*, com.comments_number
			FROM '. SmalladsSetup::$smallads_table .' smallads
			LEFT JOIN '. DB_TABLE_MEMBER .' member ON member.user_id = smallads.author_user_id
			LEFT JOIN ' . DB_TABLE_COMMENTS_TOPIC . ' com ON com.id_in_module = smallads.id AND com.module_id = "smallads"
			' . $condition . '
			ORDER BY smallads.creation_date DESC
			', array_merge($parameters)
		);

		$pending_items_number = $result->get_rows_count();

		$this->build_sorting_smallad_type();

		$this->view->put_all(array(
			'C_PENDING'         => true,
			'C_ENABLED_FILTERS'	=> $this->config->are_sort_filters_enabled(),
			'C_ITEMS'           => $result->get_rows_count() > 0,
			'C_SEVERAL_ITEMS'   => $result->get_rows_count() > 1,
			'C_GRID_VIEW'       => $this->config->get_display_type() == SmalladsConfig::GRID_VIEW,
			'C_LIST_VIEW'       => $this->config->get_display_type() == SmalladsConfig::LIST_VIEW,
			'C_TABLE_VIEW'      => $this->config->get_display_type() == SmalladsConfig::TABLE_VIEW,
			'C_PAGINATION'      => $result->get_rows_count() > $this->config->get_items_per_page(),
			'C_USAGE_TERMS'	    => $this->config->are_usage_terms_displayed(),

			'ITEMS_PER_ROW'  => $this->config->get_items_per_row(),
			'ITEMS_PER_PAGE' => $this->config->get_items_per_page(),

			'U_USAGE_TERMS' => SmalladsUrlBuilder::usage_terms()->rel()
		));

		if ($pending_items_number > 0)
		{
			$this->view->put('C_COMMENTS_ENABLED', $this->comments_config->are_comments_enabled());

			while($row = $result->fetch())
			{
				$item = new SmalladsItem();
				$item->set_properties($row);

				$this->build_keywords_view($item);

				$this->view->assign_block_vars('items', $item->get_template_vars());
				$this->build_sources_view($item);
			}
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
					'C_SEPARATOR' => $i < $type_nbr,
					'TYPE_NAME'        => $name,
					'TYPE_NAME_FILTER' => Url::encode_rewrite(TextHelper::strtolower($name)),
				));
				$i++;
			}
		}
	}

	private function build_sources_view(SmalladsItem $item)
	{
		$sources = $item->get_sources();
		$nbr_sources = count($sources);
		if ($nbr_sources)
		{
			$this->view->put('items.C_SOURCES', $nbr_sources > 0);

			$i = 1;
			foreach ($sources as $name => $url)
			{
				$this->view->assign_block_vars('items.sources', array(
					'C_SEPARATOR' => $i < $nbr_sources,
					'NAME' => $name,
					'URL'  => $url,
				));
				$i++;
			}
		}
	}

	private function build_keywords_view(SmalladsItem $item)
	{
		$keywords = $item->get_keywords();
		$nbr_keywords = count($keywords);
		$this->view->put('C_KEYWORDS', $nbr_keywords > 0);

		$i = 1;
		foreach ($keywords as $keyword)
		{
			$this->view->assign_block_vars('keywords', array(
				'C_SEPARATOR' => $i < $nbr_keywords,
				'NAME' => $keyword->get_name(),
				'URL'  => SmalladsUrlBuilder::display_tag($keyword->get_rewrited_name())->rel(),
			));
			$i++;
		}
	}

	private function check_authorizations()
	{
		if (!(CategoriesAuthorizationsService::check_authorizations()->write() || CategoriesAuthorizationsService::check_authorizations()->contribution() || CategoriesAuthorizationsService::check_authorizations()->moderation()))
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}

	private function generate_response(HTTPRequestCustom $request)
	{
		$response = new SiteDisplayResponse($this->view);

		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->lang['smallads.pending.items'], $this->lang['smallads.module.title']);
		$graphical_environment->get_seo_meta_data()->set_description($this->lang['smallads.seo.description.pending']);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(SmalladsUrlBuilder::display_pending_items());

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['smallads.module.title'], SmalladsUrlBuilder::home());
		$breadcrumb->add($this->lang['smallads.pending.items'], SmalladsUrlBuilder::display_pending_items());

		return $response;
	}
}
?>
