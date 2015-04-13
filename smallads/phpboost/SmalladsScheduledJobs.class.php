<?php
/*##################################################
 *                         SmalladsScheduledJobs.class.php
 *                            -------------------
 *   begin                : February 11, 2013
 *   copyright            : (C) 2013 Julien BRISWALTER
 *   email                : julienseth78@phpboost.com
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
		global $Cache, $CONFIG_SMALLADS;

		$Cache->load('smallads');

		$delay = empty($CONFIG_SMALLADS['max_weeks']) ? 0 : abs(intval($CONFIG_SMALLADS['max_weeks']));
		if (!empty($delay))
			PersistenceContext::get_querier()->delete(PREFIX . 'smallads',
				'WHERE (approved = 1) AND (DATEDIFF(NOW(), FROM_UNIXTIME(date_approved)) > 7 * IFNULL(max_weeks, :delay))', array('delay' => $delay));

		$Cache->generate_module_file('smallads');
	}
}
?>