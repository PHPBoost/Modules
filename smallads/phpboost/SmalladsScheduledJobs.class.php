<?php
/*##################################################
 *                         SmalladsScheduledJobs.class.php
 *                            -------------------
 *   begin                : February 11, 2013
 *   copyright            : (C) 2013 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
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

class SmalladsScheduledJobs extends AbstractScheduledJobExtensionPoint
{
	/**
	 * {@inheritDoc}
	 */
	public function on_changeday(Date $yesterday, Date $today)
	{
		$config = SmalladsConfig::load();
		
		if ($config->is_max_weeks_number_displayed())
		{
			PersistenceContext::get_querier()->delete(SmalladsSetup::$smallads_table,
				'WHERE (approved = 1) AND (DATEDIFF(NOW(), FROM_UNIXTIME(date_approved)) > 7 * IF(max_weeks IS NULL OR max_weeks = 0, :delay, max_weeks))', array('delay' => (int)$config->get_max_weeks_number()));
			
			SmalladsCache::invalidate();
		}
	}
}
?>