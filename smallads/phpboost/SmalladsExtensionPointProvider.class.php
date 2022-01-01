<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 12 08
 * @since       PHPBoost 4.0 - 2013 01 29
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
 * @contributor xela <xela@phpboost.com>
*/

class SmalladsExtensionPointProvider extends ItemsModuleExtensionPointProvider
{
	public function home_page()
	{
		return new DefaultHomePageDisplay($this->get_id(), SmalladsCategoryController::get_view());
	}
}
?>
