<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 11
 * @since       PHPBoost 6.0 - 2021 10 30
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class FluxExtensionPointProvider extends ItemsModuleExtensionPointProvider
{
	public function home_page(): mixed
	{
		return new DefaultHomePageDisplay($this->get_id(), FluxCategoryController::get_view());
	}

	public function scheduled_jobs(): mixed
	{
		return new FluxScheduledJobs();
	}

	public function lobby(): mixed
	{
		return [new FluxLobbyProvider()];
	}
}
?>
