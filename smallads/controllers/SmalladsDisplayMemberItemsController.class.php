<?php
/*##################################################
 *                      SmalladsDisplayMemberItemsController.class.php
 *                            -------------------
 *   begin                : March 15, 2018
 *   copyright            : (C) 2018 Sebastien LARTIGUE
 *   email                : babsolune@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

/**
 * @author Sebastien LARTIGUE <babsolune@phpboost.com>
 */

class SmalladsDisplayMemberItemsController extends ModuleController
{
	private $lang;
	private $config;
	private $comments_config;
	private $category;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();

		$this->check_authorizations();

		$this->build_view();

		return $this->generate_response();
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'smallads');
		$this->view = new FileTemplate('smallads/SmalladsDisplayCategoryController.tpl');
		$this->view->add_lang($this->lang);
		$this->config = SmalladsConfig::load();
		$this->comments_config = new SmalladsComments();
	}

	private function build_view()
	{
		$now = new Date();
		$request = AppContext::get_request();
		$mode = $request->get_getstring('sort', $this->config->get_items_default_sort_mode());
		$field = $request->get_getstring('field', Smallad::SORT_FIELDS_URL_VALUES[$this->config->get_items_default_sort_field()]);

		$page = $request->get_getint('page', 1);
		$member_items = AppContext::get_current_user()->get_id();

		$sort_mode = TextHelper::strtoupper($mode);
		$sort_mode = (in_array($sort_mode, array(Smallad::ASC, Smallad::DESC)) ? $sort_mode : $this->config->get_items_default_sort_mode());

		$this->build_items_listing_view($now, $field, TextHelper::strtolower($sort_mode), $page, $member_items);
		$this->build_sorting_form($field, TextHelper::strtolower($sort_mode));
		$this->build_sorting_smallad_type();
	}

	private function build_items_listing_view(Date $now, $field, $sort_mode, $page, $member_items)
	{
		if(!empty($member_items))
		{
			if (in_array($field, Smallad::SORT_FIELDS_URL_VALUES))
				$sort_field = array_search($field, Smallad::SORT_FIELDS_URL_VALUES);
			else
				$sort_field = $this->config->get_items_default_sort_field();

			$authorized_categories = SmalladsService::get_authorized_categories($this->get_category()->get_id());

			$condition = 'WHERE id_category IN :authorized_categories
			AND smallads.author_user_id = :mbr_id
			AND (published = 1 OR (published = 2 AND publication_start_date < :timestamp_now AND (publication_end_date > :timestamp_now OR publication_end_date = 0)))';
			$parameters = array(
				'authorized_categories' => $authorized_categories,
				'timestamp_now' => $now->get_timestamp(),
				'mbr_id' => AppContext::get_current_user()->get_id()
			);

			$result = PersistenceContext::get_querier()->select('SELECT smallads.*, member.*, com.number_comments
			FROM ' . SmalladsSetup::$smallads_table . ' smallads
			LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = smallads.author_user_id
			LEFT JOIN ' . DB_TABLE_COMMENTS_TOPIC . ' com ON com.id_in_module = smallads.id AND com.module_id = \'smallads\'
			' . $condition . '
			ORDER BY ' . $sort_field . ' ' . $sort_mode . '
			', array_merge($parameters, array(
				'user_id' => AppContext::get_current_user()->get_id()
			)));

			$columns_number_displayed_per_line = $this->config->get_displayed_cols_number_per_line();

			$this->view->put_all(array(
				'C_ITEMS'                => $result->get_rows_count() > 0,
				'C_MORE_THAN_ONE_ITEM'   => $result->get_rows_count() > 1,

				'C_MOSAIC'               => $this->config->get_display_type() == SmalladsConfig::MOSAIC_DISPLAY,
				'C_LIST'                 => $this->config->get_display_type() == SmalladsConfig::LIST_DISPLAY,
				'C_TABLE'                => $this->config->get_display_type() == SmalladsConfig::TABLE_DISPLAY,
				'C_TABLE'                => $this->config->get_display_type() == SmalladsConfig::TABLE_DISPLAY,
				'C_MEMBER'			     => true,
				'C_ITEMS_SORT_FILTERS'   => $this->config->are_sort_filters_enabled(),
				'C_DISPLAY_CAT_ICONS'    => $this->config->are_cat_icons_enabled(),
				'C_NO_ITEM_AVAILABLE'    => $result->get_rows_count() == 0,
				'C_SEVERAL_COLUMNS'      => $columns_number_displayed_per_line > 1,
				'C_MODERATION'           => SmalladsAuthorizationsService::check_authorizations($this->get_category()->get_id())->moderation(),
				'COLUMNS_NUMBER'         => $columns_number_displayed_per_line,
				'C_ONE_ITEM_AVAILABLE'   => $result->get_rows_count() == 1,
				'C_TWO_ITEMS_AVAILABLE'  => $result->get_rows_count() == 2,
				'C_USAGE_TERMS'	         => $this->config->are_usage_terms_displayed(),
				'ITEMS_PER_PAGE'         => $this->config->get_items_number_per_page(),
				'ID_CATEGORY'            => $this->get_category()->get_id(),
				'U_USAGE_TERMS' 		 => SmalladsUrlBuilder::usage_terms()->rel()
			));

			while($row = $result->fetch())
			{
				$smallad = new Smallad();
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

	private function build_sorting_smallad_type()
	{
		$this->config = SmalladsConfig::load();
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

	private function build_sorting_form($field, $mode)
	{
		$common_lang = LangLoader::get('common');
		$lang = LangLoader::get('common', 'smallads');

		$form = new HTMLForm(__CLASS__, '', false);
		$form->set_css_class('options no-style');

		$fieldset = new FormFieldsetHorizontal('filters', array('description' => $common_lang['sort_by']));
		$form->add_fieldset($fieldset);

		$sort_options = array(
			new FormFieldSelectChoiceOption($common_lang['form.date.creation'], Smallad::SORT_FIELDS_URL_VALUES[Smallad::SORT_DATE]),
			new FormFieldSelectChoiceOption($common_lang['form.title'], Smallad::SORT_FIELDS_URL_VALUES[Smallad::SORT_ALPHABETIC]),
			new FormFieldSelectChoiceOption($lang['smallads.sort.field.views'], Smallad::SORT_FIELDS_URL_VALUES[Smallad::SORT_NUMBER_VIEWS])
		);

		if ($this->comments_config->are_comments_enabled())
			$sort_options[] = new FormFieldSelectChoiceOption($common_lang['sort_by.number_comments'], Smallad::SORT_FIELDS_URL_VALUES[Smallad::SORT_NUMBER_COMMENTS]);

		$fieldset->add_field(new FormFieldSimpleSelectChoice('sort_fields', '', $field, $sort_options,
			array('events' => array('change' => 'document.location = "'. SmalladsUrlBuilder::display_category($this->category->get_id(), $this->category->get_rewrited_name())->rel() .'" + HTMLForms.getField("sort_fields").getValue() + "/" + HTMLForms.getField("sort_mode").getValue();'))
		));

		$fieldset->add_field(new FormFieldSimpleSelectChoice('sort_mode', '', $mode,
			array(
				new FormFieldSelectChoiceOption($common_lang['sort.asc'], 'asc'),
				new FormFieldSelectChoiceOption($common_lang['sort.desc'], 'desc')
			),
			array('events' => array('change' => 'document.location = "' . SmalladsUrlBuilder::display_category($this->category->get_id(), $this->category->get_rewrited_name())->rel() . '" + HTMLForms.getField("sort_fields").getValue() + "/" + HTMLForms.getField("sort_mode").getValue();'))
		));

		$this->view->put('FORM', $form->display());
	}

	private function get_category()
	{
		if ($this->category === null)
		{
			$id = AppContext::get_request()->get_getstring('id_category', 0);
			if (!empty($id))
			{
				try {
					$this->category = SmalladsService::get_categories_manager()->get_categories_cache()->get_category($id);
				} catch (CategoryNotFoundException $e) {
					$error_controller = PHPBoostErrors::unexisting_page();
					DispatchManager::redirect($error_controller);
				}
			}
			else
			{
				$this->category = SmalladsService::get_categories_manager()->get_categories_cache()->get_category(Category::ROOT_CATEGORY);
			}
		}
		return $this->category;
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
		if (AppContext::get_current_user()->is_guest())
		{
			if (($this->config->are_descriptions_displayed_to_guests() && !Authorizations::check_auth(RANK_TYPE, User::MEMBER_LEVEL, $this->get_category()->get_authorizations(), Category::READ_AUTHORIZATIONS)) || (!$this->config->are_descriptions_displayed_to_guests() && !SmalladsAuthorizationsService::check_authorizations($this->get_category()->get_id())->read()))
			{
				$error_controller = PHPBoostErrors::user_not_authorized();
				DispatchManager::redirect($error_controller);
			}
		}
		else
		{
			if (!SmalladsAuthorizationsService::check_authorizations($this->get_category()->get_id())->read())
			{
				$error_controller = PHPBoostErrors::user_not_authorized();
				DispatchManager::redirect($error_controller);
			}
		}
	}

	private function generate_response()
	{
		$response = new SiteDisplayResponse($this->view);

		$graphical_environment = $response->get_graphical_environment();

		if ($this->category->get_id() != Category::ROOT_CATEGORY)
			$graphical_environment->set_page_title($this->category->get_name(), $this->lang['smallads.module.title']);
		else
			$graphical_environment->set_page_title($this->lang['smallads.module.title']);

		$graphical_environment->get_seo_meta_data()->set_description($this->category->get_description());
		$graphical_environment->get_seo_meta_data()->set_canonical_url(SmalladsUrlBuilder::display_category($this->category->get_id(), $this->category->get_rewrited_name(), AppContext::get_request()->get_getstring('field', 'date'), AppContext::get_request()->get_getstring('sort', 'desc'), AppContext::get_request()->get_getint('page', 1)));

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['smallads.module.title'], SmalladsUrlBuilder::home());
		$breadcrumb->add($this->lang['smallads.member.items'], SmalladsUrlBuilder::display_member_items());

		$categories = array_reverse(SmalladsService::get_categories_manager()->get_parents($this->category->get_id(), true));
		foreach ($categories as $id => $category)
		{
			if ($category->get_id() != Category::ROOT_CATEGORY)
				$breadcrumb->add($category->get_name(), SmalladsUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name(), AppContext::get_request()->get_getstring('field', 'date'), AppContext::get_request()->get_getstring('sort', 'desc'), AppContext::get_request()->get_getint('page', 1)));
		}

		return $response;
	}

	public static function get_view()
	{
		$object = new self();
		$object->init();
		$object->check_authorizations();
		$object->build_view();
		return $object->view;
	}
}
?>
