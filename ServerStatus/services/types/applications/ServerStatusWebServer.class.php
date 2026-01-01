<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2016 02 11
 * @since       PHPBoost 4.0 - 2013 08 09
*/

class ServerStatusWebServer extends AbstractServerStatusServer
{
	public function __construct()
	{
		parent::__construct('Web', 80);
	}
}
?>
