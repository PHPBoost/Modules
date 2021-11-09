<?php
/**
 * @copyright   &copy; 2005-2021 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 11 09
 * @since       PHPBoost 6.0 - 2021 10 30
*/

class FluxExtensionPointProvider extends ItemsModuleExtensionPointProvider
{
	public function __construct()
	{
		parent::__construct('flux');
	}

	public function home_page()
	{
		return new DefaultHomePageDisplay($this->get_id(), FluxCategoryController::get_view());
	}

	public function css_files()
	{
		$module_css_files = new ModuleCssFiles();
		$module_css_files->adding_running_module_displayed_file('flux.css');

		return $module_css_files;
	}
}
?>
