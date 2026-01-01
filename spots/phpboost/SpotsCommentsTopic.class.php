<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2023 01 17
 * @since       PHPBoost 6.0 - 2023 01 17
*/

class SpotsCommentsTopic extends DefaultCommentsTopic
{
	public function __construct(SpotsItem $item = null)
	{
		parent::__construct('spots');
		$this->item = $item;
	}

	protected function get_item_from_manager()
	{
		return SpotsService::get_item($this->get_id_in_module());
	}
}
?>
