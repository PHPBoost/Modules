<?php
/*##################################################
 *                           TeamspeakCache.class.php
 *                            -------------------
 *   begin                : August 27, 2013
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

/**
 * @author Julien BRISWALTER <julienseth78@phpboost.com>
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
			
			$viewer_pictures = new Url(PATH_TO_ROOT . '/teamspeak/templates/images/viewer/');
			$flags_pictures = new Url(PATH_TO_ROOT . '/teamspeak/templates/images/flags/');
			
			/* display viewer for selected TeamSpeak3_Node_Server */
			$viewer = $ts3->getViewer(new TeamSpeak3_Viewer_Html($viewer_pictures->rel(), $flags_pictures->rel()));
			
			$this->view->put_all(array(
				'C_NUMBER_CLIENTS_DISPLAYED' => $config->is_clients_number_displayed(),
				'C_MORE_THAN_ONE_CLIENT' => $number_clients > 1,
				'VIEWER' => $viewer,
				'NUMBER_CLIENTS' => $number_clients
			));
		}
		catch(Exception $e)
		{
			$this->view->put_all(array(
				'C_ERROR' => true,
				'ERROR_CODE' => dechex($e->getCode()),
				'ERROR_MESSAGE' => TextHelper::htmlentities($e->getMessage())
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
