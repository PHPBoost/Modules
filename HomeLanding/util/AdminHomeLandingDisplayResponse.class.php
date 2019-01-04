<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2018 12 29
 * @since   	PHPBoost 5.0 - 2016 01 02
*/

class AdminHomeLandingDisplayResponse extends AdminMenuDisplayResponse
{
	public function __construct($view, $title_page)
	{
		parent::__construct($view);

		$lang = LangLoader::get('common', 'HomeLanding');
		$this->set_title($lang['module_title']);
		$sticky_title = HomeLandingConfig::load()->get_sticky_title();

		$this->add_link(LangLoader::get_message('configuration', 'admin-common'), HomeLandingUrlBuilder::configuration());
		$this->add_link(LangLoader::get_message('admin.elements_position', 'common', 'HomeLanding'), HomeLandingUrlBuilder::positions());
		$this->add_link(LangLoader::get_message('homelanding.sticky.manage', 'sticky', 'HomeLanding').': '. $sticky_title, HomeLandingUrlBuilder::sticky_manage());
		$this->add_link($sticky_title, HomeLandingUrlBuilder::sticky());

		$env = $this->get_graphical_environment();
		$env->set_page_title($title_page, $lang['module_title']);
	}
}
?>
