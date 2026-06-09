<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 3.0 - 2009 12 09
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      xela <xela@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminSitemapResponse extends AdminMenuDisplayResponse
{
	public function __construct($view)
	{
		parent::__construct($view);

		$lang = LangLoader::get_all_langs('sitemap');
		$this->set_title($lang['sitemap.module.title']);

		$this->add_link($lang['form.configuration'], SitemapUrlBuilder::get_general_config());
		$this->add_link($lang['sitemap.generate.xml'], SitemapUrlBuilder::get_xml_file_generation());
		$this->add_link($lang['form.documentation'], ModulesManager::get_module('sitemap')->get_configuration()->get_documentation());
	}
}
?>
