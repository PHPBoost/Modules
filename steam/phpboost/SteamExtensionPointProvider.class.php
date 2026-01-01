<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2018 05 20
 * @since       PHPBoost 5.1 - 2018 04 22
*/

class SteamExtensionPointProvider extends ExtensionPointProvider
{
	public function __construct()
	{
		parent::__construct('steam');
	}
}
?>
