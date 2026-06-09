<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 3.0 - 2011 08 29
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      xela <xela@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminCustomizationDisplayResponse extends AdminMenuDisplayResponse
{
	public function __construct($view, $page_title)
	{
		parent::__construct($view);

		$lang = LangLoader::get_all_langs('customization');
		$this->set_title(StringVars::replace_vars($lang['form.module.title'], ['module_name' => $this->module->get_configuration()->get_name()]));

		$this->add_link($lang['customization.interface.title'], AdminCustomizeUrlBuilder::customize_interface());
		$this->add_link($lang['customization.favicon.title'], AdminCustomizeUrlBuilder::customize_favicon());
		$this->add_link($lang['customization.editor.css.files'], AdminCustomizeUrlBuilder::editor_css_file());
		$this->add_link($lang['customization.editor.tpl.files'], AdminCustomizeUrlBuilder::editor_tpl_file());
		$this->add_link($lang['form.documentation'], $this->module->get_configuration()->get_documentation());

		$this->get_graphical_environment()->set_page_title($page_title);
	}
}
?>
