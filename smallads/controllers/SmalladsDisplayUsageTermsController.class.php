<?php
/*##################################################
 *		       SmalladsDisplayUsageTermsController.class.php
 *                            -------------------
 *   begin                : March 15, 2018
 *   copyright            : (C) 2018 Sebastien LARTIGUE
 *   email                : babsolune@phpboost.com
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
 * @author Sebastien LARTIGUE <babsolune@phpboost.com>
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
		$graphical_environment->get_seo_meta_data()->set_description($this->config->get_usage_terms());
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
