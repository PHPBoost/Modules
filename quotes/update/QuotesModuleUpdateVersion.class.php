<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 6.0 - 2019 11 03
 * @author      xela <xela@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class QuotesModuleUpdateVersion extends ModuleUpdateVersion
{
	public function __construct()
	{
		parent::__construct('quotes');

		$this->content_tables = [['name' => PREFIX . 'quotes', 'content_field' => 'content']];
		self::$delete_old_files_list = [
			'/controllers/ajax/AjaxQuoteAuthorAutoCompleteController.class.php',
			'/controllers/AdminQuotesManageController.class.php',
			'/controllers/QuotesDeleteController.class.php',
			'/controllers/QuotesDisplayAuthorQuotesController.class.php',
			'/controllers/QuotesDisplayCategoryController.class.php',
			'/controllers/QuotesDisplayPendingQuotesController.class.php',
			'/controllers/QuotesFormController.class.php',
			'/controllers/QuotesManageController.class.php',
			'/lang/english/config.php',
			'/lang/french/config.php',
			'/phpboost/QuotesHomePageExtensionPoint.class.php',
			'/phpboost/QuotesSitemapExtensionPoint.class.php',
			'/services/Quote.class.php',
			'/services/QuotesAuthorizationsService.class.php',
			'/templates/QuotesDisplaySeveralQuotesController.tpl',
			'/util/AdminQuotesDisplayResponse.class.php'
		];
		self::$delete_old_folders_list = [
			'/controllers/categories'
		];

		$this->database_columns_to_modify = [
			[
				'table_name' => PREFIX . 'quotes',
				'columns' => [
					'author'          => 'writer TEXT',
					'rewrited_author' => 'rewrited_writer TEXT',
					'quote'           => 'content MEDIUMTEXT',
				]
			],
			[
				'table_name' => PREFIX . 'quotes_cats',
				'columns' => [
					'image' => 'thumbnail VARCHAR(255) NOT NULL DEFAULT ""'
				]
			]
		];
	}
}
?>
