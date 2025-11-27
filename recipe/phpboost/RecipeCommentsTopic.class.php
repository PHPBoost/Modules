<?php
/**
 * @copyright   &copy; 2005-2025 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2025 11 27
 * @since       PHPBoost 6.0 - 2022 08 26
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 */

class RecipeCommentsTopic extends DefaultCommentsTopic
{
	public function __construct(?RecipeItem $item = null)
	{
		parent::__construct('recipe');
		$this->item = $item;
	}

	protected function get_item_from_manager()
	{
		return RecipeService::get_item($this->get_id_in_module());
	}
}
?>
