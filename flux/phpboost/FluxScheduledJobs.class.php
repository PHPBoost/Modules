<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 11 13
 * @since       PHPBoost 6.0 - 2022 05 20
*/

class FluxScheduledJobs extends AbstractScheduledJobExtensionPoint
{
	public function on_changeday(Date $yesterday, Date $today)
	{
		$result = PersistenceContext::get_querier()->select('SELECT flux.*
			FROM ' . FluxSetup::$flux_table . ' flux
			WHERE published = 1'
		);

		while ($row = $result->fetch())
		{
			$file = new File(PATH_TO_ROOT . $row['xml_path']);
			if($file->get_path() != '..' && $file->exists())
			{
				// get file from target website
				$xml_url = Url::to_absolute($row['website_xml']);
				// load target feed items
				$content = file_get_contents($xml_url);
				// write target feed items in server file
				file_put_contents($file->get_path(), $content);
			}
		}
	}
}
?>
