<?php
/*##################################################
 *                         HomeLandingStickyController.class.php
 *                            -------------------
 *   begin                : January 2, 2016
 *   copyright            : (C) 2016 Sebastien LARTIGUE - Julien BRISWALTER
 *   email                : babsolune@phpboost.com - j1.seth@phpboost.com
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

/**
 * @author Sebastien Lartigue <babsolune@phpboost.com>
 * @author Julien Briswalter <j1.seth@phpboost.com>
 */


class HomeLandingStickyController  extends ModuleController
{
    private $lang;
    private $template;

    public function execute(HTTPRequestCustom $request)
    {
        $this->check_authorization();

        $this->init();

        return $this->build_response($this->template);
    }

    private function init()
    {
        $this->template = new FileTemplate('HomeLanding/HomeLandingStickyController.tpl');
        $this->lang = LangLoader::get('sticky', 'HomeLanding');
        $this->template->add_lang($this->lang);
    }

    private function check_authorization()
    {
        if(!AppContext::get_current_user()->check_level(User::MODERATOR_LEVEL))
        {
            $error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
        }
    }

    private function build_response(View $view)
    {
        $config = HomeLandingConfig::load();
        $response = new SiteDisplayResponse($view);
	    $graphical_environment = $response->get_graphical_environment();
	    $graphical_environment->set_page_title($this->lang['homelanding.sticky.title']);
	    $breadcrumb = $graphical_environment->get_breadcrumb();
	    $breadcrumb->add(Langloader::get_message('module_title', 'common', 'HomeLanding'), HomeLandingUrlBuilder::home());
	    $breadcrumb->add($this->lang['homelanding.sticky.title']);
        $this->template->put_all(array(
            'STICKY_TITLE' => $config->get_sticky_title(),
            'STICKY_CONTENT' => FormatingHelper::second_parse($config->get_sticky_text())
        ));
        return $response;
    }
}
