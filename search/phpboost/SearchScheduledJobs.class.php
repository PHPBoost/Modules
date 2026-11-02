<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 02
 * @since       PHPBoost 3.0 - 2010 10 16
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class SearchScheduledJobs extends AbstractScheduledJobExtensionPoint
{
	public function on_changeday(Date $yesterday, Date $today): void
	{
		$querier = PersistenceContext::get_querier();
		$querier->truncate(PREFIX . 'search_results');
		$querier->truncate(PREFIX . 'search_index');
	}
}
?>
