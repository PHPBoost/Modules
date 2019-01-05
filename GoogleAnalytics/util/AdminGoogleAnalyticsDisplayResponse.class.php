<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2016 02 11
 * @since   	PHPBoost 4.0 - 2013 12 02
*/

class AdminGoogleAnalyticsDisplayResponse extends AdminMenuDisplayResponse
{
	public function __construct($view, $title_page)
	{
		parent::__construct($view);

		$lang = LangLoader::get('common', 'GoogleAnalytics');
		$picture = '/GoogleAnalytics/GoogleAnalytics.png';
		$this->set_title($lang['name']);
		$this->add_link($lang['configuration'], GoogleAnalyticsUrlBuilder::configuration(), $picture);

		$env = $this->get_graphical_environment();
		$env->set_page_title($title_page);
	}
}
?>
