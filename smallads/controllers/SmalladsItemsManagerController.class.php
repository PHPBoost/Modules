<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 12 20
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
		$this->view = new StringTemplate('# INCLUDE table #');
	}

	private function build_table()
	{
		$display_categories = CategoriesService::get_categories_manager()->get_categories_cache()->has_categories();

		$columns = array(
			new HTMLTableColumn(LangLoader::get_message('form.title', 'common'), 'title'),
			new HTMLTableColumn(LangLoader::get_message('category', 'categories-common'), 'id_category'),
			new HTMLTableColumn(LangLoader::get_message('author', 'common'), 'display_name'),
			new HTMLTableColumn(LangLoader::get_message('form.date.creation', 'common'), 'creation_date'),
			new HTMLTableColumn(LangLoader::get_message('status', 'common'), 'published'),
			new HTMLTableColumn(LangLoader::get_message('smallads.completed.item', 'common', 'smallads'), 'completed'),
			new HTMLTableColumn(LangLoader::get_message('actions', 'admin-common'), '', array('sr-only' => true))
		);

		if (!$display_categories)
			unset($columns[1]);

		$table_model = new SQLHTMLTableModel(SmalladsSetup::$smallads_table, 'table', $columns, new HTMLTableSortingRule('creation_date', HTMLTableSortingRule::DESC));

		$table_model->set_caption($this->lang['smallads.management']);

		$table = new HTMLTable($table_model);

		$results = array();
		$result = $table_model->get_sql_results('smallads
			LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = smallads.author_user_id',
			array('*', 'smallads.id')
		);
		foreach ($result as $row)
		{
			$smallad = new SmalladsItem();
			$smallad->set_properties($row);
			$category = $smallad->get_category();
			$user = $smallad->get_author_user();

			$this->elements_number++;
			$this->ids[$this->elements_number] = $smallad->get_id();

			$edit_link = new EditLinkHTMLElement(SmalladsUrlBuilder::edit_item($smallad->get_id()));
			$delete_link = new DeleteLinkHTMLElement(SmalladsUrlBuilder::delete_item($smallad->get_id()));

			$user_group_color = User::get_group_color($user->get_groups(), $user->get_level(), true);
			$author = $user->get_id() !== User::VISITOR_LEVEL ? new LinkHTMLElement(UserUrlBuilder::profile($user->get_id()), $user->get_display_name(), (!empty($user_group_color) ? array('style' => 'color: ' . $user_group_color) : array()), UserService::get_level_class($user->get_level())) : $user->get_display_name();

			$br = new BrHTMLElement();

			$dates = '';
			if ($smallad->get_publishing_start_date() != null && $smallad->get_publishing_end_date() != null)
			{
				$dates = LangLoader::get_message('form.date.start', 'common') . ' ' . $smallad->get_publishing_start_date()->format(Date::FORMAT_DAY_MONTH_YEAR) . $br->display() . LangLoader::get_message('form.date.end', 'common') . ' ' . $smallad->get_publishing_end_date()->format(Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE);
			}
			else
			{
				if ($smallad->get_publishing_start_date() != null)
					$dates = $smallad->get_publishing_start_date()->format(Date::FORMAT_DAY_MONTH_YEAR);
				else
				{
					if ($smallad->get_publishing_end_date() != null)
						$dates = LangLoader::get_message('until', 'main') . ' ' . $smallad->get_publishing_end_date()->format(Date::FORMAT_DAY_MONTH_YEAR);
				}
			}

			$start_and_end_dates = new SpanHTMLElement($dates, array(), 'smaller');

			$completed = $smallad->is_completed() ? LangLoader::get_message('smallads.completed.item', 'common', 'smallads') : '';

			$row = array(
				new HTMLTableRowCell(new LinkHTMLElement(SmalladsUrlBuilder::display_item($category->get_id(), $category->get_rewrited_name(), $smallad->get_id(), $smallad->get_rewrited_title()), $smallad->get_title()), 'left'),
				new HTMLTableRowCell(new LinkHTMLElement(SmalladsUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name()), ($category->get_id() == Category::ROOT_CATEGORY ? LangLoader::get_message('none_e', 'common') : $category->get_name()))),
				new HTMLTableRowCell($author),
				new HTMLTableRowCell($smallad->get_creation_date()->format(Date::FORMAT_DAY_MONTH_YEAR)),
				new HTMLTableRowCell($smallad->get_status() . $br->display() . ($dates ? $start_and_end_dates->display() : '')),
				new HTMLTableRowCell($completed),
				new HTMLTableRowCell($edit_link->display() . $delete_link->display(), 'controls'),
			);

			if (!$display_categories)
				unset($row[1]);

			$results[] = new HTMLTableRow($row);
		}
		$table->set_rows($table_model->get_number_of_matching_rows(), $results);

		$this->view->put('table', $table->display());

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

			AppContext::get_response()->redirect(SmalladsUrlBuilder::manage_items(), LangLoader::get_message('process.success', 'status-messages-common'));
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
		$graphical_environment->set_page_title($this->lang['smallads.management'], $this->lang['module.title'], $page);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(SmalladsUrlBuilder::manage_items());

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['module.title'], SmalladsUrlBuilder::home());

		$breadcrumb->add($this->lang['smallads.management'], SmalladsUrlBuilder::manage_items());

		return $response;
	}
}
?>
