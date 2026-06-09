<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 6.0 - 2022 05 20
*/

class FluxScheduledJobs extends AbstractScheduledJobExtensionPoint
{
	/**
     * Handles the daily change event for RSS flux.
     */
    public function on_changeday(Date $yesterday, Date $today): void
    {
        $result = PersistenceContext::get_querier()->select('
            SELECT flux.*
            FROM ' . FluxSetup::$flux_table . ' flux
            WHERE published = 1
        ');

        while ($row = $result->fetch())
        {
            $xml_url = Url::to_absolute($row['website_xml']);
            if (FluxService::is_valid_xml($xml_url))
            {
                $host = parse_url($xml_url, PHP_URL_HOST);
                $lastname = str_replace(".", "-", $host);
                $path = parse_url($xml_url, PHP_URL_PATH);
                $firstname = preg_replace("~[/.#=?]~", "-", $path);
                $filename = '/modules/flux/xml/' . $lastname . $firstname . '.xml';

                // Use cURL in object-oriented style
                $ch = curl_init($xml_url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3');
                $content = curl_exec($ch);
                if (curl_errno($ch))
                {
                    error_log('Error fetching ' . $xml_url . ': ' . curl_error($ch));
                    if (\PHP_VERSION_ID < 80100)
                        curl_close($ch);
                    continue;
                }
                if (\PHP_VERSION_ID < 80100)
                    curl_close($ch);

                // Truncate and save
                $content = substr($content, 0, strpos($content, '</rss>'));
                $content .= '</rss>';
                file_put_contents(PATH_TO_ROOT . $filename, $content);
            }
        }
    }
}
?>
