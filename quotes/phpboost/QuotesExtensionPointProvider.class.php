<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 05 12
 * @since       PHPBoost 5.0 - 2016 02 18
 * @contributor mipel <mipel@phpboost.com>
 * @contributor xela <xela@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class QuotesExtensionPointProvider extends ItemsModuleExtensionPointProvider
{
	public function home_page()
	{
		return new DefaultHomePageDisplay($this->get_id(), QuotesCategoryController::get_view());
	}
}
?>
