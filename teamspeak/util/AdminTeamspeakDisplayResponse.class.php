<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2016 02 11
 * @since       PHPBoost 4.1 - 2014 09 22
*/

class AdminTeamspeakDisplayResponse extends AdminMenuDisplayResponse
{
	public function __construct($view, $title_page)
	{
		parent::__construct($view);

		$picture = '/teamspeak/teamspeak.png';
		$this->set_title(LangLoader::get_message('ts_title', 'common', 'teamspeak'));

		$this->add_link(LangLoader::get_message('configuration', 'admin'), TeamspeakUrlBuilder::configuration(), $picture);

		$env = $this->get_graphical_environment();
		$env->set_page_title($title_page);
	}
}
?>
