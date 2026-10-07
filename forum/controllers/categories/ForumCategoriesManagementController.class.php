<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 10 07
 * @since       PHPBoost 4.1 - 2015 05 15
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class ForumCategoriesManagementController extends DefaultCategoriesManagementController
{
	protected function get_display_category_url(Category $category)
	{
		/** @var ForumCategory $forum_category */
		$forum_category = $category;
		switch ($forum_category->get_type())
		{
			case ForumCategory::TYPE_URL :
				$url = new Url($forum_category->get_url());
				break;

			case ForumCategory::TYPE_FORUM :
				$url = ForumUrlBuilder::display_forum($forum_category->get_id(), $forum_category->get_rewrited_name());
				break;

			default :
				$url = ForumUrlBuilder::display_category($forum_category->get_id(), $forum_category->get_rewrited_name());
				break;
		}

		return $url;
	}

	protected function check_authorizations()
	{
		if (!ForumAuthorizationsService::check_authorizations()->manage())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}
}
?>
