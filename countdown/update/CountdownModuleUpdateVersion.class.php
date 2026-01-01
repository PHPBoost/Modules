<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2025 02 26
 * @since       PHPBoost 6.0 - 2019 12 27
*/

class CountdownModuleUpdateVersion extends ModuleUpdateVersion
{
	public function __construct()
	{
		parent::__construct('countdown');
		self::$delete_old_files_list = [
			'/templates/js/jquery.countdown.js',
			'/templates/js/jquery.countdown.min.js',
        ];
		self::$delete_old_folders_list = [
			'/util'
        ];
	}
}
?>
