<?php
/**
 * @copyright   &copy; 2005-2024 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 10 17
 * @since       PHPBoost 6.0 - 2022 10 17
 */

class VideoTreeLinks extends DefaultTreeLinks
{
	protected function get_module_additional_actions_tree_links(&$tree)
	{
		$module_id = 'video';
		$current_user = AppContext::get_current_user()->get_id();

		$tree->add_link(new ModuleLink(LangLoader::get_message('video.my.items', 'common', $module_id), VideoUrlBuilder::display_member_items($current_user), CategoriesAuthorizationsService::check_authorizations()->write() || CategoriesAuthorizationsService::check_authorizations()->contribution() || CategoriesAuthorizationsService::check_authorizations()->moderation()));

	}
}
?>
