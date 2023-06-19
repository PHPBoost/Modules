<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2023 06 19
 * @since       PHPBoost 6.0 - 2019 12 27
*/

class LastcomsModuleUpdateVersion extends ModuleUpdateVersion
{
	public function __construct()
	{
		parent::__construct('lastcoms');

		self::$delete_old_files_list = array(
            '/phpboost/LastcomsSetup.php'
        );

		self::$delete_old_folders_list = array(
			'/util'
		);
	}
}
?>
