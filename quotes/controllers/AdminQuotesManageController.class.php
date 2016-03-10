<?php
/*##################################################
 *                      AdminQuotesManageController.class.php
 *                            -------------------
 *   begin                : February 18, 2016
 *   copyright            : (C) 2016 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
 *
 *
 ###################################################
 *
 * This program is a free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

 /**
 * @author Julien BRISWALTER <j1.seth@phpboost.com>
 */

class AdminQuotesManageController extends AdminModuleController
{
	private $lang;
	private $view;
	
	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		
		$this->build_table();
		
		return new AdminQuotesDisplayResponse($this->view, $this->lang['quotes.management']);
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('common', 'quotes');
		$this->view = new StringTemplate('# INCLUDE table #');
	}
	
	private function build_table()
	{
		$table_model = new SQLHTMLTableModel(QuotesSetup::$quotes_table, 'table', array(
			new HTMLTableColumn($this->lang['quote'], 'quote'),
			new HTMLTableColumn(LangLoader::get_message('category', 'categories-common'), 'id_category'),
			new HTMLTableColumn(LangLoader::get_message('author', 'common'), 'author'),
			new HTMLTableColumn(LangLoader::get_message('form.date.creation', 'common'), 'creation_date'),
			new HTMLTableColumn(LangLoader::get_message('status.approved', 'common'), 'approved'),
			new HTMLTableColumn('')
		), new HTMLTableSortingRule('creation_date', HTMLTableSortingRule::DESC));
		
		$table = new HTMLTable($table_model);
		
		$table_model->set_caption($this->lang['quotes.management']);
		
		$results = array();
		$result = $table_model->get_sql_results('quotes
			LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = quotes.author_user_id',
			array('*', 'quotes.id')
		);
		foreach ($result as $row)
		{
			$quote = new Quote();
			$quote->set_properties($row);
			$category = $quote->get_category();

			$edit_link = new LinkHTMLElement(QuotesUrlBuilder::edit($quote->get_id()), '', array('title' => LangLoader::get_message('edit', 'common')), 'fa fa-edit');
			$delete_link = new LinkHTMLElement(QuotesUrlBuilder::delete($quote->get_id()), '', array('title' => LangLoader::get_message('delete', 'common'), 'data-confirmation' => 'delete-element'), 'fa fa-delete');

			$results[] = new HTMLTableRow(array(
				new HTMLTableRowCell($quote->get_quote(), 'left'),
				new HTMLTableRowCell(new LinkHTMLElement(QuotesUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name()), $category->get_name())),
				new HTMLTableRowCell(new LinkHTMLElement(QuotesUrlBuilder::display_author_quotes($quote->get_rewrited_author()), $quote->get_author())),
				new HTMLTableRowCell($quote->get_creation_date()->format(Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE)),
				new HTMLTableRowCell($quote->is_approved() ? LangLoader::get_message('yes', 'common') : LangLoader::get_message('no', 'common')),
				new HTMLTableRowCell($edit_link->display() . $delete_link->display())
			));
		}
		$table->set_rows($table_model->get_number_of_matching_rows(), $results);

		$this->view->put('table', $table->display());
	}
}
?>
