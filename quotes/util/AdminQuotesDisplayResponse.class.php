<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2018 12 24
 * @since   	PHPBoost 5.0 - 2016 02 18
 * @contributor mipel <mipel@phpboost.com>
*/

class AdminQuotesDisplayResponse extends AdminMenuDisplayResponse
{
	public function __construct($view, $title_page)
	{
		parent::__construct($view);

		$lang = LangLoader::get('common', 'quotes');
		$this->set_title($lang['module_title']);

		$this->add_link(LangLoader::get_message('configuration', 'admin'), QuotesUrlBuilder::configuration());

		$env = $this->get_graphical_environment();
		$env->set_page_title($title_page, $lang['module_title']);
	}
}
?>
