<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 12 18
 * @since       PHPBoost 5.0 - 2016 02 02
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminSmalladsDisplayResponse extends AdminMenuDisplayResponse
{
	public function __construct($view, $page_title)
	{
		parent::__construct($view);

		$lang = LangLoader::get('common', 'smallads');
		
		$this->add_link($lang['config.categories.title'], SmalladsUrlBuilder::categories_configuration());
		$this->add_link($lang['config.items.title'], SmalladsUrlBuilder::items_configuration());
		$this->add_link($lang['config.mini.title'], SmalladsUrlBuilder::mini_configuration());
		$this->add_link($lang['config.usage.terms'], SmalladsUrlBuilder::usage_terms_configuration());
		$this->add_link(LangLoader::get_message('module.documentation', 'admin-modules-common'), $this->module->get_configuration()->get_documentation());

		$this->get_graphical_environment()->set_page_title($page_title);
	}
}
?>
