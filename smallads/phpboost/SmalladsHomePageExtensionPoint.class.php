<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2018 08 09
 * @since   	PHPBoost 5.1 - 2018 03 15
*/

class SmalladsHomePageExtensionPoint implements HomePageExtensionPoint
{
	public function get_home_page()
	{
		return new DefaultHomePage($this->get_title(), SmalladsDisplayCategoryController::get_view());
	}

	private function get_title()
	{
		return LangLoader::get_message('smallads.module.title', 'common', 'smallads');
	}
}
?>
