<?php
/**
 * @copyright   &copy; 2005-2021 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2016 02 11
 * @since       PHPBoost 4.1 - 2014 09 26
*/

class TeamspeakAjaxViewerController extends AbstractController
{
	private $lang;
	private $view;

	public function execute(HTTPRequestCustom $request)
	{
		$this->build_view();
		return new SiteNodisplayResponse($this->view);
	}

	private function build_view()
	{
		$this->lang = LangLoader::get('common', 'teamspeak');
		$this->view = TeamspeakCache::load()->get_view();
		$this->view->add_lang($this->lang);
	}

	public static function get_view()
	{
		$object = new self();
		$object->build_view();
		return $object->view;
	}
}
?>
