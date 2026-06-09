<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 6.0 - 2019 12 27
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class BirthdayModuleUpdateVersion extends ModuleUpdateVersion
{
	public function __construct()
	{
		parent::__construct('birthday');

		self::$delete_old_files_list = [
			'/lang/english/config.php',
			'/lang/french/config.php',
			'/templates/birthday.css',
		];

		self::$delete_old_folders_list = [
			'/util'
		];
	}
}
?>
