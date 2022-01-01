<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 14
 * @since       PHPBoost 5.0 - 2016 01 02
*/

class HomeLandingStickyController extends DefaultModuleController
{
    protected function get_template_to_use()
    {
	    return new FileTemplate('HomeLanding/HomeLandingStickyController.tpl');
    }

    public function execute(HTTPRequestCustom $request)
    {
        $this->check_authorization();

        return $this->generate_response();
    }

    private function check_authorization()
    {
        if(!AppContext::get_current_user()->check_level(User::MODERATOR_LEVEL))
        {
            $error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
        }
    }

    private function generate_response()
    {
        $response = new SiteDisplayResponse($this->view);
	    $graphical_environment = $response->get_graphical_environment();
	    $graphical_environment->set_page_title($this->lang['homelanding.sticky.title']);
	    $breadcrumb = $graphical_environment->get_breadcrumb();
	    $breadcrumb->add(Langloader::get_message('homelanding.module.title', 'common', 'HomeLanding'), HomeLandingUrlBuilder::home());
	    $breadcrumb->add($this->lang['homelanding.sticky.title']);
        $this->view->put_all(array(
            'STICKY_TITLE' => $this->config->get_sticky_title(),
            'STICKY_CONTENT' => FormatingHelper::second_parse($this->config->get_sticky_text())
        ));
        return $response;
    }
}
