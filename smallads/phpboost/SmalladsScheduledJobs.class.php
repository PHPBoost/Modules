<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 02 11
 * @since       PHPBoost 4.0 - 2013 02 11
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class SmalladsScheduledJobs extends AbstractScheduledJobExtensionPoint
{
	public function on_changepage()
	{
		$config = SmalladsConfig::load();
		$deferred_operations = $config->get_deferred_operations();

		if (!empty($deferred_operations))
		{
			$now = new Date();
			$is_modified = false;

			foreach ($deferred_operations as $id => $timestamp)
			{
				if ($timestamp <= $now->get_timestamp())
				{
					unset($deferred_operations[$id]);
					$is_modified = true;
				}
			}

			if ($is_modified)
			{
				SmalladsService::clear_cache();

				$config->set_deferred_operations($deferred_operations);
				SmalladsConfig::save();
			}
		}
	}

	/**
	 * {@inheritDoc}
	 */
	public function on_changeday(Date $yesterday, Date $today)
	{
		$config = SmalladsConfig::load();

		// Archiving item at the end of max_weeks
		if ($config->is_max_weeks_number_displayed())
		{
			PersistenceContext::get_querier()->inject('UPDATE ' . SmalladsSetup::$smallads_table . '
				SET archived = 1, published = 0
				WHERE published = 1
				AND (DATEDIFF(NOW(), FROM_UNIXTIME(creation_date)) > (7 * max_weeks))'
			);
		}

		// Deleting item if "completed" is checked
		PersistenceContext::get_querier()->delete(SmalladsSetup::$smallads_table,
			'WHERE (published = 1) AND (completed = 1) AND (DATEDIFF(NOW(), FROM_UNIXTIME(update_date)) > :delay)', array('delay' => (int)$config->get_display_delay_before_delete())
		);

		SmalladsService::clear_cache();
	}
}
?>
