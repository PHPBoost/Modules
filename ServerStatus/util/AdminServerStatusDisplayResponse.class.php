<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2021 12 16
 * @since       PHPBoost 4.0 - 2013 08 04
*/

class AdminServerStatusDisplayResponse extends AdminMenuDisplayResponse
{
	public function __construct($view, $page_title)
	{
		parent::__construct($view);

		$lang = LangLoader::get_all_langs('ServerStatus');

		$this->add_link($lang['server.management'], ServerStatusUrlBuilder::servers_management());
		$this->add_link($lang['server.add.item'], ServerStatusUrlBuilder::add_server());
		$this->add_link($lang['form.configuration'], $this->module->get_configuration()->get_admin_main_page());

		$this->get_graphical_environment()->set_page_title($page_title);
	}
}
?>
