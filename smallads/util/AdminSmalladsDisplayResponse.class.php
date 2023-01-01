<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 07 21
 * @since       PHPBoost 5.0 - 2016 02 02
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminSmalladsDisplayResponse extends AdminMenuDisplayResponse
{
	public function __construct($view, $page_title)
	{
		parent::__construct($view);

		$lang = LangLoader::get_all_langs('smallads');

		$this->add_link($lang['smallads.categories.config'], SmalladsUrlBuilder::categories_configuration());
		$this->add_link($lang['smallads.items.config'], SmalladsUrlBuilder::items_configuration());
		$this->add_link($lang['smallads.mini.config'], SmalladsUrlBuilder::mini_configuration());
		$this->add_link($lang['smallads.usage.terms.management'], SmalladsUrlBuilder::usage_terms_configuration());
		$this->add_link($lang['form.documentation'], $this->module->get_configuration()->get_documentation());

		$this->get_graphical_environment()->set_page_title($page_title);
	}
}
?>
