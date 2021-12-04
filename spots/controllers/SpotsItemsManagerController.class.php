<?php
/**
 * @copyright   &copy; 2005-2021 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 04
 * @since       PHPBoost 6.0 - 2021 08 22
*/

class SpotsItemsManagerController extends DefaultAdminModuleController
{
	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();

		$this->build_table();

		return $this->generate_response();
	}

	private function build_table()
	{
		$display_categories = CategoriesService::get_categories_manager()->get_categories_cache()->has_categories();

		$table_model = new SQLHTMLTableModel(SpotsSetup::$spots_table, 'table', array(
			new HTMLTableColumn($this->lang['common.title'], 'title'),
			new HTMLTableColumn($this->lang['common.category'], 'id_category'),
			new HTMLTableColumn($this->lang['common.author'], 'display_name'),
			new HTMLTableColumn($this->lang['common.creation.date'], 'creation_date'),
			new HTMLTableColumn($this->lang['common.status'], 'published'),
			new HTMLTableColumn($this->lang['common.moderation'], '', array('sr-only' => true))
		), new HTMLTableSortingRule('creation_date', HTMLTableSortingRule::DESC));

		$table_model->set_filters_menu_title($this->lang['spots.filter.items']);
		$table_model->add_filter(new HTMLTableDateGreaterThanOrEqualsToSQLFilter('creation_date', 'filter1', $this->lang['common.creation.date'] . ' ' . TextHelper::lcfirst($this->lang['common.minimum'])));
		$table_model->add_filter(new HTMLTableDateLessThanOrEqualsToSQLFilter('creation_date', 'filter2', $this->lang['common.creation.date'] . ' ' . TextHelper::lcfirst($this->lang['common.maximum'])));
		$table_model->add_filter(new HTMLTableAjaxUserAutoCompleteSQLFilter('display_name', 'filter3', $this->lang['common.author']));
		// if ($display_categories)
		// 	$table_model->add_filter(new HTMLTableCategorySQLFilter('filter4'));

		$status_list = array(Item::PUBLISHED => $this->lang['common.status.published.alt'], Item::NOT_PUBLISHED => $this->lang['common.status.draft'], Item::DEFERRED_PUBLICATION => $this->lang['common.status.deffered.date']);
		$table_model->add_filter(new HTMLTableEqualsFromListSQLFilter('published', 'filter5', $this->lang['common.status'], $status_list));

		$table = new HTMLTable($table_model);
		$table->set_filters_fieldset_class_HTML();

		$table_model->set_layout_title($this->lang['spots.management']);

		$results = array();
		$result = $table_model->get_sql_results('spots
			LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = spots.author_user_id',
			array('*', 'spots.id')
		);
		foreach ($result as $row)
		{
			$item = new SpotsItem();
			$item->set_properties($row);
			$category = $item->get_category();
			$user = $item->get_author_user();

			$edit_item = new LinkHTMLElement(SpotsUrlBuilder::edit($item->get_id()), '', array('title' => $this->lang['common.edit']), 'fa fa-edit');
			$delete_item = new LinkHTMLElement(SpotsUrlBuilder::delete($item->get_id()), '', array('title' => $this->lang['common.delete'], 'data-confirmation' => 'delete-element'), 'far fa-trash-alt');

			$user_group_color = User::get_group_color($user->get_groups(), $user->get_level(), true);
			$author = $user->get_id() !== User::VISITOR_LEVEL ? new LinkHTMLElement(UserUrlBuilder::profile($user->get_id()), $user->get_display_name(), (!empty($user_group_color) ? array('style' => 'color: ' . $user_group_color) : array()), UserService::get_level_class($user->get_level())) : $user->get_display_name();

			$row = array(
				new HTMLTableRowCell(new LinkHTMLElement(SpotsUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $item->get_id(), $item->get_rewrited_title()), $item->get_title()), 'left'),
				new HTMLTableRowCell(new LinkHTMLElement(
					SpotsUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name()),
					($category->get_id() == Category::ROOT_CATEGORY ? $this->lang['common.none.alt'] : $category->get_name()),
					array('data-color-surround' => $category->get_id() != Category::ROOT_CATEGORY ? $category->get_color() : SpotsConfig::load()->get_default_color()), 'pinned')
				),
				new HTMLTableRowCell($author),
				new HTMLTableRowCell($item->get_creation_date()->format(Date::FORMAT_DAY_MONTH_YEAR)),
				new HTMLTableRowCell($item->get_status()),
				new HTMLTableRowCell($edit_item->display() . $delete_item->display())
			);

			if (!$display_categories)
				unset($row[1]);

			$results[] = new HTMLTableRow($row);
		}
		$table->set_rows($table_model->get_number_of_matching_rows(), $results);

		$this->view->put('CONTENT', $table->display());
	}

	private function check_authorizations()
	{
		if (!CategoriesAuthorizationsService::check_authorizations()->moderation())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}

	private function generate_response()
	{
		$response = new SiteDisplayResponse($this->view);

		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->lang['spots.management'], $this->lang['spots.module.title']);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(SpotsUrlBuilder::manage());

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['spots.module.title'], SpotsUrlBuilder::home());

		$breadcrumb->add($this->lang['spots.management'], SpotsUrlBuilder::manage());

		return $response;
	}
}
?>
