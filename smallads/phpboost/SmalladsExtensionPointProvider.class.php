<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 4.0 - 2013 01 29
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @author      xela <xela@phpboost.com>
*/

class SmalladsExtensionPointProvider extends ItemsModuleExtensionPointProvider
{
	public function comments()
	{
		return new CommentsTopics([new SmalladsCommentsTopic()]);
	}

	public function home_page()
	{
		return new DefaultHomePageDisplay($this->get_id(), SmalladsCategoryController::get_view());
	}

	public function lobby(): array
	{
		return [
			new SmalladsLobbyItemsProvider('smallads'),
			new SmalladsLobbyCategoryProvider('smallads'),
		];
	}
}
?>
