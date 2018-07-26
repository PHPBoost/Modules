<?php
/*##################################################
 *		    SmalladsDisplayPendingItemsController.class.php
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

class SmalladsDisplayPendingItemsController extends ModuleController
{
	private $lang;
	private $view;
	private $form;
	private $config;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		$this->check_authorizations();
		$this->build_view($request);
		return $this->generate_response();
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'smallads');
		$this->view = new FileTemplate('smallads/SmalladsDisplayCategoryController.tpl');
		$this->view->add_lang($this->lang);
	}

	private function build_sorting_form($field, $mode)
	{
		$common_lang = LangLoader::get('common');

		$form = new HTMLForm(__CLASS__, '', false);
		$form->set_css_class('options no-style');

		$fieldset = new FormFieldsetHorizontal('filters', array('description' => $common_lang['sort_by']));
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldSimpleSelectChoice('sort_fields', '', $field, array(
			new FormFieldSelectChoiceOption($common_lang['form.date.creation'], Smallad::SORT_FIELDS_URL_VALUES[Smallad::SORT_DATE]),
			new FormFieldSelectChoiceOption($common_lang['form.title'], Smallad::SORT_FIELDS_URL_VALUES[Smallad::SORT_ALPHABETIC]),
			new FormFieldSelectChoiceOption($common_lang['author'], Smallad::SORT_FIELDS_URL_VALUES[Smallad::SORT_AUTHOR])
			), array('events' => array('change' => 'document.location = "'. SmalladsUrlBuilder::display_pending_items()->rel() . '" + HTMLForms.getField("sort_fields").getValue() + "/" + HTMLForms.getField("sort_mode").getValue();'))
		));

		$fieldset->add_field(new FormFieldSimpleSelectChoice('sort_mode', '', $mode,
			array(
				new FormFieldSelectChoiceOption($common_lang['sort.asc'], 'asc'),
				new FormFieldSelectChoiceOption($common_lang['sort.desc'], 'desc')
			),
			array('events' => array('change' => 'document.location = "' . SmalladsUrlBuilder::display_pending_items()->rel() . '" + HTMLForms.getField("sort_fields").getValue() + "/" + HTMLForms.getField("sort_mode").getValue();'))
		));

		$this->form = $form;
	}

	private function build_view($request)
	{
		$now = new Date();
		$authorized_categories = SmalladsService::get_authorized_categories(Category::ROOT_CATEGORY);
		$this->config = SmalladsConfig::load();
		$comments_config = new SmalladsComments();

		$mode = $request->get_getstring('sort', $this->config->get_items_default_sort_mode());
		$field = $request->get_getstring('field', Smallad::SORT_FIELDS_URL_VALUES[$this->config->get_items_default_sort_field()]);

		$sort_mode = TextHelper::strtoupper($mode);
		$sort_mode = (in_array($sort_mode, array(Smallad::ASC, Smallad::DESC)) ? $sort_mode : $this->config->get_items_default_sort_mode());

		if (in_array($field, array(Smallad::SORT_FIELDS_URL_VALUES[Smallad::SORT_ALPHABETIC], Smallad::SORT_FIELDS_URL_VALUES[Smallad::SORT_AUTHOR], Smallad::SORT_FIELDS_URL_VALUES[Smallad::SORT_DATE])))
			$sort_field = array_search($field, Smallad::SORT_FIELDS_URL_VALUES);
		else
			$sort_field = Smallad::SORT_DATE;

		$condition = 'WHERE id_category IN :authorized_categories
		' . (!SmalladsAuthorizationsService::check_authorizations()->moderation() ? ' AND author_user_id = :user_id' : '') . '
		AND (published = 0 OR (published = 2 AND (publication_start_date > :timestamp_now OR (publication_end_date != 0 AND publication_end_date < :timestamp_now))))';
		$parameters = array(
			'authorized_categories' => $authorized_categories,
			'user_id' => AppContext::get_current_user()->get_id(),
			'timestamp_now' => $now->get_timestamp()
		);

		$page = AppContext::get_request()->get_getint('page', 1);

		$result = PersistenceContext::get_querier()->select('SELECT smallads.*, member.*, com.number_comments
		FROM '. SmalladsSetup::$smallads_table .' smallads
		LEFT JOIN '. DB_TABLE_MEMBER .' member ON member.user_id = smallads.author_user_id
		LEFT JOIN ' . DB_TABLE_COMMENTS_TOPIC . ' com ON com.id_in_module = smallads.id AND com.module_id = "smallads"
		' . $condition . '
		ORDER BY ' . $sort_field . ' ' . $sort_mode . '
		', array_merge($parameters, array(
		)));

		$nbr_items_pending = $result->get_rows_count();

		$this->build_sorting_form($field, TextHelper::strtolower($sort_mode));
		$this->build_sorting_smallad_type();

		$this->view->put_all(array(
			'C_ITEMS'                => $result->get_rows_count() > 0,
			'C_MORE_THAN_ONE_ITEM'   => $result->get_rows_count() > 1,
			'C_PENDING'              => true,
			'C_MOSAIC'               => $this->config->get_display_type() == SmalladsConfig::MOSAIC_DISPLAY,
			'C_LIST'                 => $this->config->get_display_type() == SmalladsConfig::LIST_DISPLAY,
			'C_TABLE'                => $this->config->get_display_type() == SmalladsConfig::TABLE_DISPLAY,
			'C_NO_ITEM_AVAILABLE'    => $nbr_items_pending == 0,
			'ITEMS_PER_PAGE'         => $this->config->get_items_number_per_page(),
			'C_USAGE_TERMS'	         => $this->config->are_usage_terms_displayed(),
			'U_USAGE_TERMS' 		 => SmalladsUrlBuilder::usage_terms()->rel()
		));

		if ($nbr_items_pending > 0)
		{
			$columns_number_displayed_per_line = $this->config->get_displayed_cols_number_per_line();

			$this->view->put_all(array(
				'C_ITEMS_SORT_FILTERS' => $this->config->are_sort_filters_enabled(),
				'C_COMMENTS_ENABLED'   => $comments_config->are_comments_enabled(),
				'C_SEVERAL_COLUMNS'    => $columns_number_displayed_per_line > 1,
				'COLUMNS_NUMBER'       => $columns_number_displayed_per_line
			));

			while($row = $result->fetch())
			{
				$smallad = new Smallad();
				$smallad->set_properties($row);

				$this->build_keywords_view($smallad);

				$this->view->assign_block_vars('items', $smallad->get_array_tpl_vars());
				$this->build_sources_view($smallad);
			}
		}
		$result->dispose();

		$this->view->put('FORM', $this->form->display());
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
		if (!(SmalladsAuthorizationsService::check_authorizations()->write() || SmalladsAuthorizationsService::check_authorizations()->contribution() || SmalladsAuthorizationsService::check_authorizations()->moderation()))
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}

	private function generate_response()
	{
		$response = new SiteDisplayResponse($this->view);

		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->lang['smallads.pending.items'], $this->lang['smallads.module.title']);
		$graphical_environment->get_seo_meta_data()->set_description($this->lang['smallads.seo.description.pending']);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(SmalladsUrlBuilder::display_pending_items(AppContext::get_request()->get_getstring('field', 'date'), AppContext::get_request()->get_getstring('sort', 'desc'), AppContext::get_request()->get_getint('page', 1)));

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['smallads.module.title'], SmalladsUrlBuilder::home());
		$breadcrumb->add($this->lang['smallads.pending.items'], SmalladsUrlBuilder::display_pending_items(AppContext::get_request()->get_getstring('field', 'date'), AppContext::get_request()->get_getstring('sort', 'desc'), AppContext::get_request()->get_getint('page', 1)));

		return $response;
	}
}
?>
