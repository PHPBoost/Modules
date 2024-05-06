<?php
/**
 * @copyright   &copy; 2005-2024 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2023 01 16
 * @since       PHPBoost 5.1 - 2018 03 15
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class SmalladsCommentsTopic extends DefaultCommentsTopic
{
	public function __construct(SmalladsItem $item = null)
	{
		parent::__construct('smallads');
		$this->item = $item;
	}

	protected function get_item_from_manager()
	{
		return SmalladsService::get_item($this->get_id_in_module());
	}
}
?>
