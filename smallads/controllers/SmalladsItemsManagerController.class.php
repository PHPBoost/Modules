<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 06 24
 * @since       PHPBoost 5.1 - 2018 03 15
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class SmalladsItemsManagerController extends ModuleController
{
	private $lang;
	private $view;

	private $elements_number = 0;
	private $ids = array();

	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();

		$this->init();

		$current_page = $this->build_table();

		$this->execute_multiple_delete_if_needed($request);

		return $this->generate_response($current_page);
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'smallads');
		$this->view = new StringTemplate('# INCLUDE TABLE #');
	}

	private function build_table()
	{
		$common_lang = LangLoader::get('common-lang');
		$display_categories = CategoriesService::get_categories_manager()->get_categories_cache()->has_categories();

		$columns = array(
			new HTMLTableColumn($common_lang['common.title'], 'title'),
			new HTMLTableColumn($common_lang['common.category'], 'id_category'),
			new HTMLTableColumn($common_lang['common.author'], 'display_name'),
			new HTMLTableColumn($common_lang['common.creation.date'], 'creation_date'),
			new HTMLTableColumn($common_lang['common.status'], 'published'),
			new HTMLTableColumn($this->lang['smallads.completed.item'], 'completed'),
			new HTMLTableColumn($common_lang['common.moderation'], '', array('sr-only' => true))
		);

		if (!$display_categories)
			unset($columns[1]);

		$table_model = new SQLHTMLTableModel(SmalladsSetup::$smallads_table, 'items-manager', $columns, new HTMLTableSortingRule('creation_date', HTMLTableSortingRule::DESC));

		$table_model->set_layout_title($this->lang['smallads.items.management']);

		$table_model->set_filters_menu_title($this->lang['smallads.filter.items']);
		$table_model->add_filter(new HTMLTableDateGreaterThanOrEqualsToSQLFilter('creation_date', 'filter1', $common_lang['common.creation.date'] . ' ' . TextHelper::lcfirst($common_lang['common.minimum'])));
		$table_model->add_filter(new HTMLTableDateLessThanOrEqualsToSQLFilter('creation_date', 'filter2', $common_lang['common.creation.date'] . ' ' . TextHelper::lcfirst($common_lang['common.maximum'])));
		$table_model->add_filter(new HTMLTableAjaxUserAutoCompleteSQLFilter('display_name', 'filter3', $common_lang['common.author']));
		if ($display_categories)
			$table_model->add_filter(new HTMLTableCategorySQLFilter('filter4'));

		$status_list = array(Item::PUBLISHED => $common_lang['common.status.approved'], Item::NOT_PUBLISHED => $common_lang['common.status.draft'], Item::DEFERRED_PUBLICATION => $common_lang['common.status.deffered.date']);
		$table_model->add_filter(new HTMLTableEqualsFromListSQLFilter('published', 'filter5', $common_lang['common.status.deffered.date'], $status_list));

		$table = new HTMLTable($table_model);
		$table->set_filters_fieldset_class_HTML();

		$results = array();
		$result = $table_model->get_sql_results('smallads
			LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = smallads.author_user_id',
			array('*', 'smallads.id')
		);
		foreach ($result as $row)
		{
			$item = new SmalladsItem();
			$item->set_properties($row);
			$category = $item->get_category();
			$user = $item->get_author_user();

			$this->elements_number++;
			$this->ids[$this->elements_number] = $item->get_id();

			$edit_link = new EditLinkHTMLElement(SmalladsUrlBuilder::edit_item($item->get_id()));
			$delete_link = new DeleteLinkHTMLElement(SmalladsUrlBuilder::delete_item($item->get_id()));

			$user_group_color = User::get_group_color($user->get_groups(), $user->get_level(), true);
			$author = $user->get_id() !== User::VISITOR_LEVEL ? new LinkHTMLElement(UserUrlBuilder::profile($user->get_id()), $user->get_display_name(), (!empty($user_group_color) ? array('style' => 'color: ' . $user_group_color) : array()), UserService::get_level_class($user->get_level())) : $user->get_display_name();

			$br = new BrHTMLElement();

			$dates = '';
			if ($item->get_publishing_start_date() != null && $item->get_publishing_end_date() != null)
			{
				$dates = LangLoader::get_message('form.start.date', 'form-lang') . ' ' . $item->get_publishing_start_date()->format(Date::FORMAT_DAY_MONTH_YEAR) . $br->display() . LangLoader::get_message('form.end.date', 'form-lang') . ' ' . $item->get_publishing_end_date()->format(Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE);
			}
			else
			{
				if ($item->get_publishing_start_date() != null)
					$dates = $item->get_publishing_start_date()->format(Date::FORMAT_DAY_MONTH_YEAR);
				else
				{
					if ($item->get_publishing_end_date() != null)
						$dates = $common_lang['common.until'] . ' ' . $item->get_publishing_end_date()->format(Date::FORMAT_DAY_MONTH_YEAR);
				}
			}

			$start_and_end_dates = new SpanHTMLElement($dates, array(), 'smaller');

			if($item->is_completed()) {
				$final_status =  $this->lang['smallads.completed.item'];
				$final_status_class = 'bgc-full error';
			}
			else if ($item->is_archived()) {
				$final_status =  $this->lang['smallads.archived.item'];
				$final_status_class = 'bgc-full warning';
			}
			else {
				$final_status = '';
				$final_status_class = '';
			}

			$row = array(
				new HTMLTableRowCell(new LinkHTMLElement(SmalladsUrlBuilder::display_item($category->get_id(), $category->get_rewrited_name(), $item->get_id(), $item->get_rewrited_title()), $item->get_title()), 'left'),
				new HTMLTableRowCell(new LinkHTMLElement(SmalladsUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name()), ($category->get_id() == Category::ROOT_CATEGORY ? $common_lang['common.none.alt'] : $category->get_name()))),
				new HTMLTableRowCell($author),
				new HTMLTableRowCell($item->get_creation_date()->format(Date::FORMAT_DAY_MONTH_YEAR)),
				new HTMLTableRowCell($item->get_status() . $br->display() . ($dates ? $start_and_end_dates->display() : '')),
				new HTMLTableRowCell($final_status, $final_status_class),
				new HTMLTableRowCell($edit_link->display() . $delete_link->display(), 'controls'),
			);

			if (!$display_categories)
				unset($row[1]);

			$results[] = new HTMLTableRow($row);
		}
		$table->set_rows($table_model->get_number_of_matching_rows(), $results);

		$this->view->put('TABLE', $table->display());

		return $table->get_page_number();
	}

	private function execute_multiple_delete_if_needed(HTTPRequestCustom $request)
	{
		if ($request->get_string('delete-selected-elements', false))
		{
			for ($i = 1 ; $i <= $this->elements_number ; $i++)
			{
				if ($request->get_value('delete-checkbox-' . $i, 'off') == 'on')
				{
					if (isset($this->ids[$i]))
					{
						SmalladsService::delete($this->ids[$i]);
					}
				}
			}

			SmalladsService::clear_cache();

			AppContext::get_response()->redirect(SmalladsUrlBuilder::manage_items(), LangLoader::get_message('warning.process.success', 'warning-lang'));
		}
	}

	private function check_authorizations()
	{
		if (!CategoriesAuthorizationsService::check_authorizations()->moderation())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}

	private function generate_response($page = 1)
	{
		$response = new SiteDisplayResponse($this->view);

		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->lang['smallads.items.management'], $this->lang['smallads.module.title'], $page);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(SmalladsUrlBuilder::manage_items());

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['smallads.module.title'], SmalladsUrlBuilder::home());

		$breadcrumb->add($this->lang['smallads.items.management'], SmalladsUrlBuilder::manage_items());

		return $response;
	}
}
?>
