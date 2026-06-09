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

class ForumModuleUpdateVersion extends ModuleUpdateVersion
{
	public function __construct()
	{
		parent::__construct('forum');

		self::$delete_old_files_list = [
			'/controllers/categories/ForumCategoriesManageController.class.php',
			'/controllers/categories/ForumDeleteCategoryController.class.php',
			'/lang/english/config.php',
			'/lang/english/forum_english.php',
			'/lang/french/config.php',
			'/lang/french/forum_french.php',
			'/phpboost/ForumHomePageExtensionPoint.class.php',
			'/phpboost/ForumSitemapExtensionPoint.class.php'
		];

		$this->database_columns_to_add = [
			[
				'table_name' => PREFIX . 'forum_cats',
				'columns' => [
					'thumbnail' => ['type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"],
					'icon' => ['type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"],
					'color' => ['type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"],
				]
			],
			[
				'table_name' => PREFIX . 'forum_msg',
				'columns' => [
					'selected' => ['type' => 'boolean', 'notnull' => 1, 'default' => 0],
				]
			],
		];

		$this->database_columns_to_modify = [
			[
				'table_name' => PREFIX . 'forum_alerts',
				'columns' => [
					'idcat'    => 'id_category INT(11) NOT NULL DEFAULT 0',
					'contents' => 'content MEDIUMTEXT',
				]
			],
			[
				'table_name' => PREFIX . 'forum_msg',
				'columns' => [
					'contents' => 'content MEDIUMTEXT',
				]
			],
			[
				'table_name' => PREFIX . 'forum_topics',
				'columns' => [
					'idcat'    => 'id_category INT(11) NOT NULL DEFAULT 0',
					'title'    => 'title VARCHAR(255) NOT NULL DEFAULT ""',
					'subtitle' => 'subtitle VARCHAR(255) NOT NULL DEFAULT ""'
				]
			]
		];
	}

	protected function update_content()
	{
		UpdateServices::update_table_content(PREFIX . 'forum_alerts', 'content');
		UpdateServices::update_table_content(PREFIX . 'forum_msg', 'content');
		UpdateServices::update_table_content(PREFIX . 'member_extended_fields', 'user_sign', 'user_id');
	}
}
?>
