<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2016 11 11
 * @since       PHPBoost 4.1 - 2014 12 12
*/

class AdminCountdownDisplayResponse extends AdminMenuDisplayResponse
{
	public function __construct($view, $title_page)
	{
		parent::__construct($view);

		$lang = LangLoader::get('common', 'countdown');
		$this->set_title($lang['title']);
		$img = 'countdown.png';

		$this->add_link(LangLoader::get_message('configuration', 'admin'), CountdownUrlBuilder::configuration(), $img);

		$env = $this->get_graphical_environment();
		$env->set_page_title($title_page);
	}
}
?>
