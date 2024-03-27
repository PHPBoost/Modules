<?php
/**
 * @copyright   &copy; 2005-2024 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 12 03
 * @since       PHPBoost 5.0 - 2016 02 18
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class QuotesItemsManagerController extends DefaultModuleController
{
	private $elements_number = 0;
	private $ids = array();

	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();

		$current_page = $this->build_table();

		$this->execute_multiple_delete_if_needed($request);

		return $this->generate_response($current_page);
	}

	private function build_table()
	{
		$display_categories = CategoriesService::get_categories_manager()->get_categories_cache()->has_categories();

		$table_model = new SQLHTMLTableModel(QuotesSetup::$quotes_table, 'items-manager', array(
			new HTMLTableColumn($this->lang['item'], 'quote'),
			new HTMLTableColumn($this->lang['common.category'], 'id_category'),
			new HTMLTableColumn($this->lang['common.author'], 'author'),
			new HTMLTableColumn($this->lang['common.creation.date'], 'creation_date'),
			new HTMLTableColumn($this->lang['common.status'], 'approved'),
			new HTMLTableColumn($this->lang['common.moderation'], '', array('css_class'=>'col-small', 'sr-only' => true))
		), new HTMLTableSortingRule('creation_date', HTMLTableSortingRule::DESC));

		$table_model->set_filters_menu_title($this->lang['quotes.filter.items']);
		$table_model->add_filter(new HTMLTableDateGreaterThanOrEqualsToSQLFilter('creation_date', 'filter1', $this->lang['common.creation.date'] . ' ' . TextHelper::lcfirst($this->lang['common.minimum'])));
		$table_model->add_filter(new HTMLTableDateLessThanOrEqualsToSQLFilter('creation_date', 'filter2', $this->lang['common.creation.date'] . ' ' . TextHelper::lcfirst($this->lang['common.maximum'])));
		$table_model->add_filter(new HTMLTableAjaxUserAutoCompleteSQLFilter('display_name', 'filter3', $this->lang['common.author']));
		if ($display_categories)
			$table_model->add_filter(new HTMLTableCategorySQLFilter('filter4'));

		$status_list = array(Item::PUBLISHED => $this->lang['common.status.approved'], Item::NOT_PUBLISHED => $this->lang['common.status.draft']);
		$table_model->add_filter(new HTMLTableEqualsFromListSQLFilter('approved', 'filter5', $this->lang['common.status.publication'], $status_list));

		$table = new HTMLTable($table_model);
		$table->set_filters_fieldset_class_HTML();

		$table_model->set_layout_title($this->lang['quotes.items.management']);

		$results = array();
		$result = $table_model->get_sql_results('quotes
			LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = quotes.author_user_id',
			array('*', 'quotes.id')
		);

		foreach ($result as $row)
		{
			$quote = new QuotesItem();
			$quote->set_properties($row);
			$category = $quote->get_category();

			$this->elements_number++;
			$this->ids[$this->elements_number] = $quote->get_id();

			$edit_link = new EditLinkHTMLElement(QuotesUrlBuilder::edit($quote->get_id()));
			$delete_link = new DeleteLinkHTMLElement(QuotesUrlBuilder::delete($quote->get_id()));

			$results[] = new HTMLTableRow(array(
				new HTMLTableRowCell($quote->get_content(), 'left'),
				new HTMLTableRowCell(new LinkHTMLElement(QuotesUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name()), ($category->get_id() == Category::ROOT_CATEGORY ? $this->lang['common.none.alt'] : $category->get_name()))),
				new HTMLTableRowCell(new LinkHTMLElement(QuotesUrlBuilder::display_writer_items($quote->get_rewrited_writer()), $quote->get_writer())),
				new HTMLTableRowCell($quote->get_creation_date()->format(Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE)),
				new HTMLTableRowCell($quote->is_approved() ? $this->lang['common.yes'] : $this->lang['common.no']),
				new HTMLTableRowCell($edit_link->display() . $delete_link->display(), 'controls')
			));
		}
		$table->set_rows($table_model->get_number_of_matching_rows(), $results);

		$this->view->put('CONTENT', $table->display());

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
						$item = QuotesService::get_item($this->ids[$i]);
                        QuotesService::delete($item->get_id());
						HooksService::execute_hook_action('delete', self::$module_id, $item->get_properties());
					}
				}
			}

			QuotesService::clear_cache();

			AppContext::get_response()->redirect(QuotesUrlBuilder::manage(), $this->lang['warning.process.success']);
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
		$graphical_environment->set_page_title($this->lang['quotes.items.management'], $this->lang['quotes.module.title'], $page);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(QuotesUrlBuilder::manage());

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['quotes.module.title'], QuotesUrlBuilder::home());

		$breadcrumb->add($this->lang['quotes.items.management'], QuotesUrlBuilder::manage());

		return $response;
	}
}
?>
