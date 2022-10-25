<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 10 17
 * @since       PHPBoost 6.0 - 2022 08 26
 */

class RecipeCategoriesCache extends DefaultRichCategoriesCache
{
	public function get_module_identifier()
	{
		return 'recipe';
	}

	protected function get_category_elements_number($id_category)
	{
		return 0;
	}

	protected function get_root_category_authorizations()
	{
		return RecipeConfig::load()->get_authorizations();
	}
	
	protected function get_root_category_description()
	{
		return StringVars::replace_vars(LangLoader::get_message('recipe.seo.description.root', 'common', 'recipe'), array('site' => GeneralConfig::load()->get_site_name()));
	}
}
?>
