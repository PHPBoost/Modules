<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      xela <xela@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class ContactModuleUpdateVersion extends ModuleUpdateVersion
{
	public function __construct()
	{
		parent::__construct('contact');

		self::$delete_old_files_list = [
			'/lang/english/config.php',
			'/lang/french/config.php',
			'/phpboost/ContactHomePageExtensionPoint.class.php'
		];
	}
}
?>
