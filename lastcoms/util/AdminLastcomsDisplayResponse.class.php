<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Geoffrey ROGUELON <liaght@gmail.com>
 * @version     PHPBoost 5.3 - last update: 2017 06 15
 * @since       PHPBoost 3.0 - 2009 07 26
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminLastcomsDisplayResponse extends AdminMenuDisplayResponse
{
	public function __construct($view, $title_page)
	{
		parent::__construct($view);

		$lang = LangLoader::get('common', 'lastcoms');
		$this->set_title($lang['lastcoms.title']);
		$img = 'lastcoms.png';

		$this->add_link(LangLoader::get_message('configuration', 'admin'), LastcomsUrlBuilder::config(), $img);

		$env = $this->get_graphical_environment();
		$env->set_page_title($title_page);
	}
}
?>
