<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 5.0 - 2017 03 09
 * @author      xela <xela@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class GuestbookModuleUpdateVersion extends ModuleUpdateVersion
{
	public function __construct()
	{
		parent::__construct('guestbook');

		self::$delete_old_files_list = [
			'/phpboost/GuestbookHomePageExtensionPoint.class.php',
			'/phpboost/GuestbookMessagesCache.class.php',
			'/phpboost/GuestbookTreeLinks.class.php',
			'/services/GuestbookMessage.class.php',
			'/util/AdminGuestbookDisplayResponse.class.php'
		];

		$this->content_tables = [PREFIX . 'guestbook'];
		$this->database_columns_to_modify = [
			[
				'table_name' => PREFIX . 'guestbook',
				'columns' => [
					'contents' => 'content MEDIUMTEXT',
				]
			]
		];
	}
}
?>
