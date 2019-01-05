<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2015 04 13
 * @since   	PHPBoost 4.1 - 2014 09 24
*/

class WebHomePageExtensionPoint implements HomePageExtensionPoint
{
	public function get_home_page()
	{
		return new DefaultHomePage($this->get_title(), TeamspeakHomeController::get_view());
	}

	private function get_title()
	{
		return LangLoader::get_message('ts_title', 'common', 'teamspeak');
	}
}
?>
