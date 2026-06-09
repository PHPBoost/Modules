<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 5.0 - 2017 04 21
 * @author      xela <xela@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class BugtrackerModuleUpdateVersion extends ModuleUpdateVersion
{
	public function __construct()
	{
		parent::__construct('bugtracker');

		self::$delete_old_files_list = [
			'/phpboost/BugtrackerHomePageExtensionPoint.class.php',
			'/services/Bug.class.php'
		];

		$this->database_columns_to_modify = [
			[
				'table_name' => PREFIX . 'bugtracker',
				'columns' => [
					'contents'    => 'content MEDIUMTEXT'
				]
			]
		];
	}

	protected function update_content()
	{
		UpdateServices::update_table_content(PREFIX . 'bugtracker', 'content');
		UpdateServices::update_table_content(PREFIX . 'bugtracker', 'reproduction_method');
		UpdateServices::update_table_content(PREFIX . 'bugtracker_history', 'old_value');
		UpdateServices::update_table_content(PREFIX . 'bugtracker_history', 'new_value');
	}
}
?>
