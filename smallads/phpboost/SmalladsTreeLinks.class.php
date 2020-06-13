<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 01 15
 * @since       PHPBoost 4.0 - 2014 02 03
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class SmalladsTreeLinks extends DefaultTreeLinks
{
	protected function display_configuration_link()
	{
		return false;
	}

	protected function get_module_additional_actions_tree_links(&$tree)
	{
		$lang = LangLoader::get('common', $this->get_module_id());
		
		$tree->add_link(new ModuleLink($lang['smallads.member.items'], SmalladsUrlBuilder::display_member_items(), $this->check_write_authorization() || $this->get_authorizations()->moderation()));
		
		$config_link = new AdminModuleLink(LangLoader::get_message('configuration', 'admin-common'), SmalladsUrlBuilder::categories_configuration());
		$config_link->add_sub_link(new AdminModuleLink($lang['config.categories.title'], SmalladsUrlBuilder::categories_configuration()));
		$config_link->add_sub_link(new AdminModuleLink($lang['config.items.title'], SmalladsUrlBuilder::items_configuration()));
		$config_link->add_sub_link(new AdminModuleLink($lang['config.mini.title'], SmalladsUrlBuilder::mini_configuration()));
		$config_link->add_sub_link(new AdminModuleLink($lang['config.usage.terms'], SmalladsUrlBuilder::usage_terms_configuration()));
		$tree->add_link($config_link);
	}
}
?>
