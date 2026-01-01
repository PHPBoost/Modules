<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2025 03 14
 * @since       PHPBoost 6.0 - 2025 03 14
*/

class SpotsModuleUpdateVersion extends ModuleUpdateVersion
{
	public function __construct()
	{
		parent::__construct('spots');

		self::$delete_old_files_list = array(
			'/templates/js/leaflet.google.min.js',
		);
	}
}
?>
