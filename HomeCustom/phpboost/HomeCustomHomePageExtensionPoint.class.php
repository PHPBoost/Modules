<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2016 09 18
 * @since   	PHPBoost 3.0 - 2012 08 25
*/

class HomeCustomHomePageExtensionPoint implements HomePageExtensionPoint
{
	private $template;

	public function get_home_page()
	{
		return new DefaultHomePage($this->get_title(), $this->get_view());
	}

	private function get_title()
	{
		return LangLoader::get_message('title', 'common', 'HomeCustom');
	}

	private function get_view()
	{
		$this->template = new FileTemplate('HomeCustom/home.tpl');

		return $this->template;
	}

}
?>
