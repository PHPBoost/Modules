<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      xela <xela@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 6.0 - 2020 06 17
*/

class PollItemsManagementController extends DefaultItemsManagementController
{
	protected function get_additional_html_table_columns()
	{
		return [new HTMLTableColumn($this->lang['poll.manage.status'], 'close_poll')];
	}

	protected function get_additional_html_table_row_cells(&$item)
	{
		return [new HTMLTableRowCell(!$item->is_closed() ? $this->lang['poll.manage.in.progress'] : $this->lang['common.status.finished'])];
	}

	protected function get_additional_html_table_filters()
	{
		$map = [$this->lang['poll.manage.in.progress'], $this->lang['poll.manage.completed']];
		return [new HTMLTableEqualsFromListSQLFilter('close_poll', 'filter6', $this->lang['poll.manage.status'], $map)];
	}
}
?>
