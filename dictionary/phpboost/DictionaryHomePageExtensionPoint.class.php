<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2016 02 17
 * @since   	PHPBoost 3.0 - 2012 11 15
*/

class DictionaryHomePageExtensionPoint implements HomePageExtensionPoint
{
	public function get_home_page()
	{
		return new DefaultHomePage($this->get_title(), DictionaryHomeController::get_view());
	}

	private function get_title()
	{
		$title = LangLoader::get_message('module_title', 'common', 'dictionary');

		if (!defined('TITLE'))
			define('TITLE', $title);

		return $title;
	}
}
?>
