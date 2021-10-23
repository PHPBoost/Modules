<?php
/**
 * @copyright   &copy; 2005-2021 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 05 28
 * @since       PHPBoost 4.0 - 2013 08 12
 * @contributor mipel <mipel@phpboost.com>
*/

class ServerStatusController extends ModuleController
{
	private $lang;
	private $view;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		$this->build_view();

		return $this->generate_response();
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'ServerStatus');
		$this->view = new FileTemplate('ServerStatus/ServerStatusController.tpl');
		$this->view->add_lang(array_merge($this->lang, LangLoader::get('common-lang')));
	}

	public function build_view()
	{
		$servers_number = 0;

		ServerStatusService::check_servers_status();
		$config = ServerStatusConfig::load();
		$servers_list = $config->get_servers_list();

		foreach ($servers_list as $id => $server)
		{
			if ($server->is_authorized() && $server->is_displayed())
			{
				$this->view->assign_block_vars('servers', array(
					'VIEW' => $server->get_view()
				));
				$servers_number++;
			}
		}

		$this->view->put('C_SERVERS', $servers_number);
	}

	private function generate_response()
	{
		$response = new SiteDisplayResponse($this->view);
		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->lang['server.module.title']);

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['server.module.title'], ServerStatusUrlBuilder::home());
		$graphical_environment->get_seo_meta_data()->set_canonical_url(ServerStatusUrlBuilder::home());

		return $response;
	}
}
?>
