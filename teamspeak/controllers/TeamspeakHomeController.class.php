<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2015 04 13
 * @since       PHPBoost 4.1 - 2014 09 24
*/

class TeamspeakHomeController extends ModuleController
{
	private $lang;
	private $tpl;
	private $config;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();

		$this->check_authorizations();

		$this->build_view();

		return $this->generate_response();
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'teamspeak');
		$this->tpl = new FileTemplate('teamspeak/TeamspeakHomeController.tpl');
		$this->tpl->add_lang($this->lang);
		$this->config = TeamspeakConfig::load();
	}

	private function build_view()
	{
		$this->tpl->put_all(array(
			'C_REFRESH_ENABLED' => $this->config->get_refresh_delay(),
			'REFRESH_DELAY' => $this->config->get_refresh_delay() * 60000
		));
	}

	private function check_authorizations()
	{
		if (!TeamspeakAuthorizationsService::check_authorizations()->read())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}

		$ts_ip = $this->config->get_ip();
		if (empty($ts_ip))
		{
			if(AppContext::get_current_user()->check_level(User::ADMIN_LEVEL))
			{
				$this->tpl->put('MSG', MessageHelper::display($this->lang['ts_ip_missing'], MessageHelper::WARNING)->render());
			}
			else
			{
				$error_controller = PHPBoostErrors::unexisting_page();
				DispatchManager::redirect($error_controller);
			}
		}
	}

	private function generate_response()
	{
		$response = new SiteDisplayResponse($this->tpl);

		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->lang['ts_title']);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(TeamspeakUrlBuilder::home());

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['ts_title'], TeamspeakUrlBuilder::home());

		return $response;
	}

	public static function get_view()
	{
		$object = new self();
		$object->init();
		$object->check_authorizations();
		$object->build_view();
		return $object->tpl;
	}
}
?>
