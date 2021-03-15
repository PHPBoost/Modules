<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 03 15
 * @since       PHPBoost 5.1 - 2018 03 15
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class SmalladsMemberItemsController extends ModuleController
{
	private $view;
	private $lang;
	private $category;
	private $config;
	private $comments_config;
	private $content_management_config;
	private $member;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();

		$this->check_authorizations();

		$this->build_view($request);

		return $this->generate_response($request);
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'smallads');
		$county_lang = LangLoader::get('counties', 'smallads');
		$this->view = new FileTemplate('smallads/SmalladsSeveralItemsController.tpl');
		$this->view->add_lang(array_merge($this->lang, $county_lang));
		$this->config = SmalladsConfig::load();
		$this->comments_config = CommentsConfig::load();
		$this->content_management_config = ContentManagementConfig::load();
	}

	private function build_view(HTTPRequestCustom $request)
	{
		$now = new Date();
		$member_items = AppContext::get_current_user()->get_id();

		$this->build_items_listing_view($now, $member_items);
		$this->build_sorting_smallad_type();
	}

	private function build_items_listing_view(Date $now, $member_items)
	{
		if(!empty($member_items))
		{
			$authorized_categories = CategoriesService::get_authorized_categories($this->get_category()->get_id(), $this->config->are_summaries_displayed_to_guests());

			$condition = 'WHERE id_category IN :authorized_categories
			AND smallads.author_user_id = :user_id
			AND smallads.archived = 0
			AND ((published = 0 AND archived = 1) OR (published = 1) OR (published = 2 AND publishing_start_date < :timestamp_now AND (publishing_end_date > :timestamp_now OR publishing_end_date = 0)))';
			$parameters = array(
				'authorized_categories' => $authorized_categories,
				'timestamp_now' => $now->get_timestamp(),
				'user_id' => $this->get_member()->get_id()
			);

			$result = PersistenceContext::get_querier()->select('SELECT smallads.*, member.*, com.comments_number
			FROM ' . SmalladsSetup::$smallads_table . ' smallads
			LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = smallads.author_user_id
			LEFT JOIN ' . DB_TABLE_COMMENTS_TOPIC . ' com ON com.id_in_module = smallads.id AND com.module_id = \'smallads\'
			' . $condition . '
			ORDER BY smallads.creation_date DESC
			', array_merge($parameters, array(
				'user_id' => AppContext::get_current_user()->get_id()
			)));

			$this->view->put_all(array(
				'C_MEMBER_ITEMS'	   => true,
				'C_MY_ITEMS'     => $this->is_current_member_displayed(),
				'C_ITEMS'              => $result->get_rows_count() > 0,
				'C_SEVERAL_ITEMS'      => $result->get_rows_count() > 1,
				'C_ROOT_CATEGORY'	   => false,
				'C_MEMBER_ITEMS' 	   => true,
				'C_ENABLED_FILTERS'	   => $this->config->are_sort_filters_enabled(),
				'C_GRID_VIEW'          => $this->config->get_display_type() == SmalladsConfig::GRID_VIEW,
				'C_LIST_VIEW'          => $this->config->get_display_type() == SmalladsConfig::LIST_VIEW,
				'C_TABLE_VIEW'         => $this->config->get_display_type() == SmalladsConfig::TABLE_VIEW,
				'C_ITEMS_SORT_FILTERS' => $this->config->are_sort_filters_enabled(),
				'C_DISPLAY_CAT_ICONS'  => $this->config->are_cat_icons_enabled(),
				'C_NO_ITEM'            => $result->get_rows_count() == 0,
				'C_MODERATION'         => CategoriesAuthorizationsService::check_authorizations($this->get_category()->get_id())->moderation(),
				'C_USAGE_TERMS'	       => $this->config->are_usage_terms_displayed(),
				'C_PAGINATION'         => $result->get_rows_count() > $this->config->get_items_per_page(),

				'ITEMS_PER_ROW'        => $this->config->get_items_per_row(),
				'ITEMS_PER_PAGE'       => $this->config->get_items_per_page(),
				'ID_CATEGORY'          => $this->get_category()->get_id(),
				'MEMBER_NAME'   	   => $this->get_member()->get_display_name(),
				'U_USAGE_TERMS' 	   => SmalladsUrlBuilder::usage_terms()->rel()
			));

			while($row = $result->fetch())
			{
				$smallad = new SmalladsItem();
				$smallad->set_properties($row);

				$this->build_keywords_view($smallad);

				$this->view->assign_block_vars('items', $smallad->get_array_tpl_vars());
				$this->build_sources_view($smallad);
			}
			$result->dispose();
		}
		else
		{
			AppContext::get_response()->redirect(SmalladsUrlBuilder::home());
		}
	}

	protected function get_member()
	{
		if ($this->member === null)
		{
			$this->member = UserService::get_user(AppContext::get_request()->get_getint('user_id', AppContext::get_current_user()->get_id()));
			if (!$this->member)
				DispatchManager::redirect(PHPBoostErrors::unexisting_element());
		}
		return $this->member;
	}

	protected function is_current_member_displayed()
	{
		return $this->member && $this->member->get_id() == AppContext::get_current_user()->get_id();
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

	private function build_sources_view(SmalladsItem $smallad)
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

	private function get_category()
	{
		if ($this->category === null)
		{
			$id = AppContext::get_request()->get_getstring('id_category', 0);
			if (!empty($id))
			{
				try {
					$this->category = CategoriesService::get_categories_manager()->get_categories_cache()->get_category($id);
				} catch (CategoryNotFoundException $e) {
					$error_controller = PHPBoostErrors::unexisting_page();
					DispatchManager::redirect($error_controller);
				}
			}
			else
			{
				$this->category = CategoriesService::get_categories_manager()->get_categories_cache()->get_category(Category::ROOT_CATEGORY);
			}
		}
		return $this->category;
	}

	private function build_keywords_view(SmalladsItem $smallad)
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
		if (AppContext::get_current_user()->is_guest())
		{
			if (($this->config->are_summaries_displayed_to_guests() && !Authorizations::check_auth(RANK_TYPE, User::MEMBER_LEVEL, $this->get_category()->get_authorizations(), Category::READ_AUTHORIZATIONS)) || (!$this->config->are_summaries_displayed_to_guests() && !CategoriesAuthorizationsService::check_authorizations($this->get_category()->get_id())->read()))
			{
				$error_controller = PHPBoostErrors::user_not_authorized();
				DispatchManager::redirect($error_controller);
			}
		}
		else
		{
			if (!CategoriesAuthorizationsService::check_authorizations($this->get_category()->get_id())->read())
			{
				$error_controller = PHPBoostErrors::user_not_authorized();
				DispatchManager::redirect($error_controller);
			}
		}
	}

	private function generate_response(HTTPRequestCustom $request)
	{
		$page_title = $this->is_current_member_displayed() ? $this->lang['my.items'] : $this->lang['member.items'] . ' ' . $this->get_member()->get_display_name();
		$response = new SiteDisplayResponse($this->view);

		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($page_title, $this->lang['module.title']);
		$graphical_environment->get_seo_meta_data()->set_description(StringVars::replace_vars($this->lang['smallads.seo.description.member'], array('author' => AppContext::get_current_user()->get_display_name())));
		$graphical_environment->get_seo_meta_data()->set_canonical_url(SmalladsUrlBuilder::display_member_items($this->get_member()->get_id()));

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['module.title'], SmalladsUrlBuilder::home());
		$breadcrumb->add($page_title, SmalladsUrlBuilder::display_member_items($this->get_member()->get_id()));

		$categories = array_reverse(CategoriesService::get_categories_manager()->get_parents($this->category->get_id(), true));
		foreach ($categories as $id => $category)
		{
			if ($category->get_id() != Category::ROOT_CATEGORY)
				$breadcrumb->add($category->get_name(), SmalladsUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name()));
		}

		return $response;
	}

	public static function get_view()
	{
		$object = new self();
		$object->init();
		$object->check_authorizations();
		$object->build_view(AppContext::get_request());
		return $object->view;
	}
}
?>
