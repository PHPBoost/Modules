<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 12 14
 * @since       PHPBoost 4.1 - 2014 09 24
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class TeamspeakHomeController extends DefaultModuleController
{
	protected function get_template_to_use()
	{
		return new FileTemplate('teamspeak/TeamspeakHomeController.tpl');
	}

	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();

		$this->build_view();

		return $this->generate_response();
	}

	private function build_view()
	{
		$this->view->put_all(array(
			'C_REFRESH_ENABLED' => $this->config->get_refresh_delay(),
			'REFRESH_DELAY'     => $this->config->get_refresh_delay() * 60000
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
			if(AppContext::get_current_user()->check_level(User::ADMINISTRATOR_LEVEL))
			{
				$this->view->put('MESSAGE_HELPER', MessageHelper::display($this->lang['ts.warning.ip'], MessageHelper::WARNING)->render());
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
		$response = new SiteDisplayResponse($this->view);

		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->lang['ts.module.title']);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(TeamspeakUrlBuilder::home());

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['ts.module.title'], TeamspeakUrlBuilder::home());

		return $response;
	}

	public static function get_view()
	{
		$object = new self('teamspeak');
		$object->check_authorizations();
		$object->build_view();
		return $object->view;
	}
}
?>
