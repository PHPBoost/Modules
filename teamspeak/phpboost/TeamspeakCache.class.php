<?php
/**
 * @copyright   &copy; 2005-2024 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 05 29
 * @since       PHPBoost 4.1 - 2014 08 27
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class TeamspeakCache implements CacheData
{
	private $view = '';
	private $last_update = 0;

	public function synchronize()
	{
		$now = new Date();

		$this->view = new FileTemplate('teamspeak/TeamspeakAjaxViewerController.tpl');

		/* load framework library */
		require_once(PATH_TO_ROOT . '/teamspeak/lib/teamspeak3/teamspeak3.php');

		$config = TeamspeakConfig::load();

		try
		{
			/* connect to server, authenticate and get TeamSpeak3_Node_Server object by URI */
			$ts3 = TeamSpeak3::factory('serverquery://' . $config->get_user() . ':' . $config->get_pass() . '@' . $config->get_ip() . ':' . $config->get_query() . '/?server_port=' . $config->get_voice() . '#no_query_clients');

			/* enable new display mode */
			$ts3->setLoadClientlistFirst(true);

			$number_clients = $ts3->clientCount();

			$viewer_pictures = new Url(PATH_TO_ROOT . '/teamspeak/templates/images/viewer/'); /* Specific Flags used by teamspeak */
			$flags_pictures = new Url(PATH_TO_ROOT . '/images/stats/countries/'); /* Flags used for PHPBoost stats */

			/* display viewer for selected TeamSpeak3_Node_Server */
			$viewer = $ts3->getViewer(new TeamSpeak3_Viewer_Html($viewer_pictures->rel(), $flags_pictures->rel()));

			$this->view->put_all(array(
				'C_NUMBER_CLIENTS_DISPLAYED' => $config->is_clients_number_displayed(),
				'C_SEVERAL_CLIENTS'          => $number_clients > 1,
				'VIEWER'         => $viewer,
				'NUMBER_CLIENTS' => $number_clients
			));
		}
		catch(Exception $e)
		{
			$this->view->put_all(array(
				'C_ERROR'       => true,
				'ERROR_CODE'    => dechex($e->getCode()),
				'ERROR_MESSAGE' => TextHelper::htmlspecialchars($e->getMessage())
			));
		}

		$this->last_update = $now->get_timestamp();
	}

	public function get_view()
	{
		$now = new Date();

		if ($now->get_timestamp() > $this->last_update + (TeamspeakConfig::load()->get_refresh_delay() * 60))
			self::invalidate();

		return $this->view;
	}

	/**
	 * Loads and returns the teamspeak viewer cached data.
	 * @return TeamspeakCache The cached data
	 */
	public static function load()
	{
		return CacheManager::load(__CLASS__, 'teamspeak', 'viewer');
	}

	/**
	 * Invalidates the teamspeak cached data.
	 */
	public static function invalidate()
	{
		CacheManager::invalidate('teamspeak', 'viewer');
	}
}
?>
