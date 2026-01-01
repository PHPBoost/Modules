<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2025 01 12
 * @since       PHPBoost 6.0 - 2021 08 22
*/

class SpotsTreeLinks extends DefaultTreeLinks
{
	protected function get_module_additional_actions_tree_links(&$tree)
	{
		$module_id = 'spots';
		$current_user = AppContext::get_current_user()->get_id();

		$tree->add_link(new ModuleLink(LangLoader::get_message('contribution.members.list', 'contribution-lang'), SpotsUrlBuilder::display_member_items(), $this->get_authorizations()->read()));
		$tree->add_link(new ModuleLink(LangLoader::get_message('spots.my.items', 'common', $module_id), SpotsUrlBuilder::display_member_items($current_user), $this->check_write_authorization() || $this->get_authorizations()->moderation()));
	}
}
?>
