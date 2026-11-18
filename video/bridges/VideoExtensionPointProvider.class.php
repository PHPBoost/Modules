<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 11
 * @since       PHPBoost 6.0 - 2022 10 17
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 */

class VideoExtensionPointProvider extends ItemsModuleExtensionPointProvider
{
	public function comments(): mixed
	{
		return new CommentsTopics([new VideoCommentsTopic()]);
	}

	public function home_page(): mixed
	{
		return new DefaultHomePageDisplay($this->get_id(), VideoCategoryController::get_view());
	}

	public function lobby(): mixed
	{
		return [
			new VideoLobbyItemsProvider('video'),
			new VideoLobbyCategoryProvider('video'),
		];
	}
}
?>
