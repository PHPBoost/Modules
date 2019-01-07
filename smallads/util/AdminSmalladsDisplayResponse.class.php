<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2018 08 09
 * @since   	PHPBoost 5.0 - 2016 02 02
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminSmalladsDisplayResponse extends AdminMenuDisplayResponse
{
	public function __construct($view, $title_page)
	{
		parent::__construct($view);

		$lang = LangLoader::get('common', 'smallads');
		$this->set_title($lang['smallads.module.title']);

		$this->add_link($lang['config.categories.title'], SmalladsUrlBuilder::categories_configuration());
		$this->add_link($lang['config.items.title'], SmalladsUrlBuilder::items_configuration());
		$this->add_link($lang['config.mini.title'], SmalladsUrlBuilder::mini_configuration());
		$this->add_link($lang['config.usage.terms'], SmalladsUrlBuilder::usage_terms_configuration());
		$this->add_link(LangLoader::get_message('module.documentation', 'admin-modules-common'), ModulesManager::get_module('smallads')->get_configuration()->get_documentation());

		$env = $this->get_graphical_environment();
		$env->set_page_title($title_page, $lang['smallads.module.title']);
	}
}
?>
