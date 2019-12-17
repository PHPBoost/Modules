<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2016 02 11
 * @since       PHPBoost 4.0 - 2013 08 04
*/

class AdminServerStatusDisplayResponse extends AdminMenuDisplayResponse
{
	public function __construct($view, $title_page)
	{
		parent::__construct($view);

		$lang = LangLoader::get('common', 'ServerStatus');
		$picture = '/ServerStatus/ServerStatus.png';
		$this->set_title($lang['module_title']);
		$this->add_link($lang['admin.config.servers.management'], ServerStatusUrlBuilder::servers_management(), $picture);
		$this->add_link($lang['admin.config.servers.action.add_server'], ServerStatusUrlBuilder::add_server(), $picture);
		$this->add_link(LangLoader::get_message('configuration', 'admin'), ServerStatusUrlBuilder::configuration(), $picture);

		$env = $this->get_graphical_environment();
		$env->set_page_title($title_page);
	}
}
?>
