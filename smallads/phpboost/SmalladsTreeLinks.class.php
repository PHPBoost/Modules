<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 06 24
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
		$current_user = AppContext::get_current_user()->get_id();

		$tree->add_link(new ModuleLink($lang['smallads.my.items'], SmalladsUrlBuilder::display_member_items($current_user), $this->check_write_authorization() || $this->get_authorizations()->moderation()));
		$tree->add_link(new ModuleLink($lang['smallads.archived.items'], SmalladsUrlBuilder::archived_items(), $this->get_authorizations()->moderation()));

		$config_link = new AdminModuleLink(LangLoader::get_message('form.configuration', 'form-lang'), SmalladsUrlBuilder::categories_configuration());
		$config_link->add_sub_link(new AdminModuleLink($lang['smallads.categories.config'], SmalladsUrlBuilder::categories_configuration()));
		$config_link->add_sub_link(new AdminModuleLink($lang['config.items.title'], SmalladsUrlBuilder::items_configuration()));
		$config_link->add_sub_link(new AdminModuleLink($lang['config.mini.title'], SmalladsUrlBuilder::mini_configuration()));
		$config_link->add_sub_link(new AdminModuleLink($lang['config.usage.terms'], SmalladsUrlBuilder::usage_terms_configuration()));
		$tree->add_link($config_link);
	}
}
?>
