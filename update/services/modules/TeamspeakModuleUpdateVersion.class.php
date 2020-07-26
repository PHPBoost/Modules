<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      xela <xela@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2019 12 28
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/
#################################################*/

class TeamspeakModuleUpdateVersion extends ModuleUpdateVersion
{
	public function __construct()
	{
		parent::__construct('teamspeak');
		
		self::$delete_old_files_list = array(
			'/phpboost/TeamspeakHomePageExtensionPoint.class.php',
			'/util/AdminTeamspeakDisplayResponse.class.php'
		);
	}
}
?>
