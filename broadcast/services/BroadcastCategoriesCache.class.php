<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2022 10 25
 * @since       PHPBoost 6.0 - 2022 10 25
 */

class BroadcastCategoriesCache extends CategoriesCache
{
	public function get_table_name()
	{
		return BroadcastSetup::$broadcast_cats_table;
	}

	public function get_table_name_containing_items()
	{
		return BroadcastSetup::$broadcast_table;
	}
	
	public function get_category_class()
	{
		return CategoriesManager::RICH_CATEGORY_CLASS;
	}
	
	public function get_module_identifier()
	{
		return 'broadcast';
	}

	protected function get_category_elements_number($id_category)
	{
		$now = new Date();
		return BroadcastService::count(
			'WHERE id_category = :id_category AND published = 1',
			array(
				'id_category' => $id_category
			)
		);
	}
	
	public function get_root_category()
	{
		$root = new RichRootCategory();
		$root->set_authorizations(BroadcastConfig::load()->get_authorizations());
		$root->set_description(
			StringVars::replace_vars(LangLoader::get_message('broadcast.seo.description.root', 'common', 'broadcast'), 
				array('site' => GeneralConfig::load()->get_site_name())
			));
		return $root;
	}
}
?>