<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 01 16
 * @since       PHPBoost 3.0 - 2016 10 12
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class HomeCustomSetup extends DefaultModuleSetup
{
	public function upgrade($installed_version)
	{
		return '6.1.0';
	}
}
?>
