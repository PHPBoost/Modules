<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 12 23
 * @since       PHPBoost 6.0 - 2022 10 25
 */

class BroadcastItemsManagerController extends DefaultModuleController
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

		$columns = array(
			new HTMLTableColumn($this->lang['common.title'], 'aria-label'),
			new HTMLTableColumn($this->lang['common.category'], 'id_category'),
			new HTMLTableColumn($this->lang['broadcast.announcer'], 'display_name'),
			// new HTMLTableColumn($this->lang['date.day'], 'release_days'),
			new HTMLTableColumn($this->lang['common.status'], 'published'),
			new HTMLTableColumn($this->lang['common.moderation'], '', array('sr-only' => true))
		);

		if (!$display_categories)
			unset($columns[1]);

		$table_model = new SQLHTMLTableModel(BroadcastSetup::$broadcast_table, 'broadcast-manager', $columns, new HTMLTableSortingRule('title', HTMLTableSortingRule::ASC));

		$table_model->set_layout_title($this->lang['broadcast.management']);

		$table_model->set_filters_menu_title($this->lang['broadcast.filter.items']);
		$table_model->add_filter(new HTMLTableAjaxUserAutoCompleteSQLFilter('display_name', 'filter', $this->lang['common.author']));
		if ($display_categories)
			$table_model->add_filter(new HTMLTableCategorySQLFilter('filter2'));

		$status_list = array(Item::PUBLISHED => $this->lang['common.status.published.alt'], Item::NOT_PUBLISHED => $this->lang['common.status.draft'], Item::DEFERRED_PUBLICATION => $this->lang['common.status.deffered.date']);
		$table_model->add_filter(new HTMLTableEqualsFromListSQLFilter('published', 'filter3', $this->lang['common.status.publication'], $status_list));

		$table = new HTMLTable($table_model);
		$table->set_filters_fieldset_class_HTML();

		$results = array();
		$result = $table_model->get_sql_results('broadcast 
			LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = broadcast.author_user_id'
		);
		foreach ($result as $row)
		{
			$item = new BroadcastItem();
			$item->set_properties($row);
			$category = $item->get_category();
			$user = $item->get_author_user();

			$this->elements_number++;
			$this->ids[$this->elements_number] = $item->get_id();

			$edit_item = new LinkHTMLElement(BroadcastUrlBuilder::edit($item->get_id()), '', array('aria-label' => $this->lang['common.edit']), 'fa fa-edit');
			$delete_item = new LinkHTMLElement(BroadcastUrlBuilder::delete($item->get_id()), '', array('aria-label' => $this->lang['common.delete'], 'data-confirmation' => 'delete-element'), 'fa fa-trash-alt');

			$user_group_color = User::get_group_color($user->get_groups(), $user->get_level(), true);
			$author = $user->get_id() !== User::VISITOR_LEVEL ? new LinkHTMLElement(UserUrlBuilder::profile($user->get_id()), $user->get_display_name(), (!empty($user_group_color) ? array('style' => 'color: ' . $user_group_color) : array()), UserService::get_level_class($user->get_level())) : $user->get_display_name();

			// $release_days_list = array();
			// Debug::stop($item->get_release_days());
			// foreach ((array)TextHelper::unserialize($item->get_release_days()) as $value) {
			// 	$release_days_list[] = LangLoader::get_message('date.' . $value['id'] . '.short', 'date-lang') . ', ';
			// }

			$row = array(
				new HTMLTableRowCell(new LinkHTMLElement(BroadcastUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $item->get_id(), $item->get_rewrited_title()), $item->get_title()), 'align-left'),
				new HTMLTableRowCell(new LinkHTMLElement(BroadcastUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name()), $category->get_name())),
				new HTMLTableRowCell($author),
				// new HTMLTableRowCell(''),
				new HTMLTableRowCell($item->get_status()),
				new HTMLTableRowCell($edit_item->display() . $delete_item->display())
			);

			if (!$display_categories)
				unset($row[1]);

			$results[] = new HTMLTableRow($row);
		}
		$table->set_rows($table_model->get_number_of_matching_rows(), $results);

		$this->view->put('CONTENT', $table->display());

		return $table->get_page_number();
	}

	// public function unarray($days)
	// {
	// 	foreach (TextHelper::unserialize($days->get_release_days()) as $id => $value) {
	// 		echo LangLoader::get_message('date.' . $value . '.short', 'date-lang') . ', ';
	// 	}
	// } 

	private function execute_multiple_delete_if_needed(HTTPRequestCustom $request)
	{
		if ($request->get_string('delete-selected-elements', false)) {
			for ($i = 1; $i <= $this->elements_number; $i++) {
				if ($request->get_value('delete-checkbox-' . $i, 'off') == 'on') {
					if (isset($this->ids[$i])) {
						$item = BroadcastService::get_item('WHERE broadcast.id=:id', array('id' => $this->ids[$i]));
						BroadcastService::delete($this->ids[$i]);
						HooksService::execute_hook_action('delete', self::$module_id, $item->get_properties());
					}
				}
			}

			BroadcastService::clear_cache();

			AppContext::get_response()->redirect(BroadcastUrlBuilder::manage(), $this->lang['warning.process.success']);
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
		$graphical_environment->set_page_title($this->lang['broadcast.management'], $this->lang['broadcast.module.title'], $page);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(BroadcastUrlBuilder::manage());

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['broadcast.module.title'], BroadcastUrlBuilder::home());

		$breadcrumb->add($this->lang['broadcast.management'], BroadcastUrlBuilder::manage());

		return $response;
	}
}
?>
