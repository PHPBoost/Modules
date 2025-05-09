<?php

/**
 * @copyright   &copy; 2005-2025 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2023 01 27
 * @since       PHPBoost 6.0 - 2022 10 25
 */

class BroadcastSearchable extends DefaultSearchable
{
	public function __construct()
	{
		$module_id = 'broadcast';
		parent::__construct($module_id);

		$this->table_name = BroadcastSetup::$broadcast_table;

		$this->field_title = 'title';
		$this->field_rewrited_title = 'rewrited_title';
		$this->field_content = 'content';

		$this->field_published = 'published';
	}
}
?>
