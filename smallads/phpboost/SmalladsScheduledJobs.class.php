<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2018 11 09
 * @since   	PHPBoost 5.1 - 2018 03 15
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
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
				Feed::clear_cache('smallads');
				SmalladsCache::invalidate();
				SmalladsCategoriesCache::invalidate();
				SmalladsKeywordsCache::invalidate();

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

		// Delete item at the end of max_weeks
		if ($config->is_max_weeks_number_displayed())
		{
			PersistenceContext::get_querier()->delete(SmalladsSetup::$smallads_table,
				'WHERE (published = 1) AND (DATEDIFF(NOW(), FROM_UNIXTIME(creation_date)) > (7 * max_weeks))');

			Feed::clear_cache('smallads');
			SmalladsCache::invalidate();
			SmalladsCategoriesCache::invalidate();
			SmalladsKeywordsCache::invalidate();
		}

		// Delete item if "ad completed" is checked
		PersistenceContext::get_querier()->delete(SmalladsSetup::$smallads_table,
			'WHERE (published = 1) AND (completed = 1) AND (DATEDIFF(NOW(), FROM_UNIXTIME(updated_date)) > :delay)', array('delay' => (int)$config->get_display_delay_before_delete())
		);

		Feed::clear_cache('smallads');
		SmalladsCache::invalidate();
		SmalladsCategoriesCache::invalidate();
		SmalladsKeywordsCache::invalidate();

	}
}
?>
