<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 06
 * @since       PHPBoost 4.0 - 2014 09 02
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class FaqCategoriesCache extends DefaultRichCategoriesCache
{
	public function get_module_identifier(): string
	{
		return 'faq';
	}

	protected function get_category_elements_number(int $id_category): int
	{
		return FaqService::count('WHERE id_category = :id_category AND approved = 1', ['id_category' => $id_category]);
	}

	protected function get_root_category_authorizations(): array
	{
		return FaqConfig::load()->get_authorizations();
	}
	
	protected function get_root_category_description(): string
	{
		$description = FaqConfig::load()->get_root_category_description();
		if (empty($description))
        {
			$description = StringVars::replace_vars(LangLoader::get_message('faq.seo.description.root', 'common', 'faq'), ['site' => GeneralConfig::load()->get_site_name()]);
        }
		return $description;
	}
}
?>
