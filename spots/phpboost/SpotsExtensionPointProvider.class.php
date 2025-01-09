<?php
/**
 * @copyright   &copy; 2005-2025 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2023 01 17
 * @since       PHPBoost 6.0 - 2021 08 22
*/

class SpotsExtensionPointProvider extends ItemsModuleExtensionPointProvider
{
	public function __construct()
	{
		parent::__construct('spots');
	}

	public function home_page()
	{
		return new DefaultHomePageDisplay($this->get_id(), SpotsCategoryController::get_view());
	}

	public function css_files()
	{
		$module_css_files = new ModuleCssFiles();
		$module_css_files->adding_running_module_displayed_file('leaflet.css');
		$module_css_files->adding_running_module_displayed_file('spots.css');

		return $module_css_files;
	}

	public function comments()
	{
		return new CommentsTopics(array(new SpotsCommentsTopic()));
	}
}
?>
