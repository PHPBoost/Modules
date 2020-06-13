<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2019 12 18
 * @since       PHPBoost 5.0 - 2016 01 02
*/

class AdminHomeLandingDisplayResponse extends AdminMenuDisplayResponse
{
	public function __construct($view, $page_title)
	{
		parent::__construct($view);

		$sticky_title = HomeLandingConfig::load()->get_sticky_title();

		$this->add_link(LangLoader::get_message('configuration', 'admin-common'), $this->module->get_configuration()->get_admin_main_page());
		$this->add_link(LangLoader::get_message('admin.elements_position', 'common', 'HomeLanding'), HomeLandingUrlBuilder::positions());
		$this->add_link(LangLoader::get_message('homelanding.sticky.manage', 'sticky', 'HomeLanding') . ': '. $sticky_title, HomeLandingUrlBuilder::sticky_manage());
		$this->add_link($sticky_title, HomeLandingUrlBuilder::sticky());

		$this->get_graphical_environment()->set_page_title($page_title);
	}
}
?>
