<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2018 12 29
 * @since       PHPBoost 5.0 - 2016 01 02
*/

class HomeLandingHomePageExtensionPoint implements HomePageExtensionPoint
{
	public function get_home_page()
	{
		$columns_disabled = ThemesManager::get_theme(AppContext::get_current_user()->get_theme())->get_columns_disabled();
		$columns_disabled->set_disable_left_columns(true);
		$columns_disabled->set_disable_right_columns(true);
		$columns_disabled->set_disable_top_central(true);
		$columns_disabled->set_disable_bottom_central(true);
		$columns_disabled->set_disable_top_footer(true);
		return new DefaultHomePage($this->get_title(), HomeLandingHomeController::get_view());
	}

	private function get_title()
	{
		return HomeLandingConfig::load()->get_module_title();
	}
}
?>
