<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2023 01 16
 * @since       PHPBoost 6.0 - 2022 08 26
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 */

class RecipeExtensionPointProvider extends ItemsModuleExtensionPointProvider
{
	public function comments()
	{
		return new CommentsTopics(array(new RecipeCommentsTopic()));
	}

	public function home_page()
	{
		return new DefaultHomePageDisplay($this->get_id(), RecipeCategoryController::get_view());
	}
}
?>
