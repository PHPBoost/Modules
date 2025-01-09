<?php
/**
 * @copyright   &copy; 2005-2025 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2023 01 14
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
			$xml_url = Url::to_absolute($row['website_xml']);
            if (FluxService::is_valid_xml($xml_url))
            {
                $host = parse_url($xml_url, PHP_URL_HOST);
                $lastname = str_replace(".", "-", $host);
                $path = parse_url($xml_url, PHP_URL_PATH);
                $firstname = preg_replace("~[/.#=?]~", "-", $path);

                $filename = '/flux/xml/' . $lastname . $firstname . '.xml';

                $content = file_get_contents($xml_url);
                $content = substr($content, 0, strpos($content, '</rss>'));
                $content .= '</rss>';
                file_put_contents(PATH_TO_ROOT . $filename, $content);
            }
		}
	}
}
?>
