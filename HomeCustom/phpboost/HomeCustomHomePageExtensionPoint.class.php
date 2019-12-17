<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 10 09
 * @since       PHPBoost 3.0 - 2012 08 25
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class HomeCustomHomePageExtensionPoint implements HomePageExtensionPoint
{
	private $tpl;
	private $lang;

	public function get_home_page()
	{
		return new DefaultHomePage($this->get_title(), $this->get_view());
	}

	private function get_title()
	{
		return LangLoader::get_message('homecustom.module.title', 'common', 'HomeCustom');
	}

	private function get_view()
	{
		$this->lang = LangLoader::get('common', 'HomeCustom');
		$this->tpl = new FileTemplate('HomeCustom/home.tpl');
		$this->tpl->add_lang($this->lang);

		return $this->tpl;
	}
}
?>
