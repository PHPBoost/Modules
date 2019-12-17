<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2018 10 31
 * @since       PHPBoost 5.1 - 2018 03 15
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class SmalladsDisplayUsageTermsController extends ModuleController
{
	private $lang;
	private $tpl;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();

		return $this->build_response($this->tpl);
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'smallads');
		$this->tpl = new FileTemplate('smallads/SmalladsDisplayUsageTermsController.tpl');
		$this->tpl->add_lang($this->lang);
	}

	private function build_response(View $view)
	{
		$this->config = SmalladsConfig::load();
		$response = new SiteDisplayResponse($this->tpl);

		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->lang['smallads.module.title']);
		$graphical_environment->get_seo_meta_data()->set_description(StringVars::replace_vars($this->lang['smallads.seo.description.usage.terms'], array('site' => GeneralConfig::load()->get_site_name())));
		$graphical_environment->get_seo_meta_data()->set_canonical_url(SmalladsUrlBuilder::usage_terms());

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['smallads.module.title'], SmalladsUrlBuilder::home());
		$breadcrumb->add($this->lang['smallads.usage.terms'], SmalladsUrlBuilder::usage_terms());

		$this->tpl->put_all(array(
			'USAGE_TERMS_TITLE' => $this->lang['smallads.usage.terms'],
			'USAGE_TERMS_CONTENT' => FormatingHelper::second_parse($this->config->get_usage_terms())
		));


		return $response;
	}
}
?>
