<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2018 04 11
 * @since       PHPBoost 5.0 - 2016 01 02
*/

class HomeLandingStickyController extends ModuleController
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
	    $breadcrumb->add(Langloader::get_message('module.title', 'common', 'HomeLanding'), HomeLandingUrlBuilder::home());
	    $breadcrumb->add($this->lang['homelanding.sticky.title']);
        $this->template->put_all(array(
            'STICKY_TITLE' => $config->get_sticky_title(),
            'STICKY_CONTENT' => FormatingHelper::second_parse($config->get_sticky_text())
        ));
        return $response;
    }
}
