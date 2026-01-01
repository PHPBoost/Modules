<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2023 01 16
 * @since       PHPBoost 6.0 - 2022 10 17
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 */

class VideoExtensionPointProvider extends ItemsModuleExtensionPointProvider
{
	public function comments()
	{
		return new CommentsTopics(array(new VideoCommentsTopic()));
	}

	public function home_page()
	{
		return new DefaultHomePageDisplay($this->get_id(), VideoCategoryController::get_view());
	}
}
?>
