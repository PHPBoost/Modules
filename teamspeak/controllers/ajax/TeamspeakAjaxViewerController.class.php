<?php
/*##################################################
 *                          TeamspeakAjaxViewerController.class.php
 *                            -------------------
 *   begin                : September 26, 2014
 *   copyright            : (C) 2014 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

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
