<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 02
 * @since       PHPBoost 4.0 - 2013 08 23
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class CalendarScheduledJobs extends AbstractScheduledJobExtensionPoint
{
	public function on_changeday(Date $yesterday, Date $today): void
	{
		if ($today->get_day() == 1)
        {
			CalendarCache::invalidate();
        }
	}
}
?>
