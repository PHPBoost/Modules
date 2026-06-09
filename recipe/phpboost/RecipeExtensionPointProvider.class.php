<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 6.0 - 2022 08 26
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 */

class RecipeExtensionPointProvider extends ItemsModuleExtensionPointProvider
{
	public function comments()
	{
		return new CommentsTopics([new RecipeCommentsTopic()]);
	}

	public function home_page()
	{
		return new DefaultHomePageDisplay($this->get_id(), RecipeCategoryController::get_view());
	}

	public function lobby(): array
	{
		return [
			new RecipeLobbyItemsProvider('recipe'),
			new RecipeLobbyCategoryProvider('recipe'),
		];
	}
}
?>
