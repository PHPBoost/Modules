<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 06
 * @since       PHPBoost 6.0 - 2022 10 25
 */

class BroadcastCategoriesCache extends CategoriesCache
{
	public function get_table_name(): string
	{
		return BroadcastSetup::$broadcast_cats_table;
	}

	public function get_table_name_containing_items(): string
	{
		return BroadcastSetup::$broadcast_table;
	}
	
	public function get_category_class(): mixed
	{
		return CategoriesManager::RICH_CATEGORY_CLASS;
	}
	
	public function get_module_identifier(): string
	{
		return 'broadcast';
	}

	protected function get_category_elements_number(int $id_category): int
	{
		return BroadcastService::count(
			'WHERE id_category = :id_category AND published = 1',
			[
				'id_category' => $id_category
			]
		);
	}
	
	public function get_root_category(): RichRootCategory
	{
		$root = new RichRootCategory();
		$root->set_authorizations(BroadcastConfig::load()->get_authorizations());
		$root->set_description(
			StringVars::replace_vars(LangLoader::get_message('broadcast.seo.description.root', 'common', 'broadcast'), 
				['site' => GeneralConfig::load()->get_site_name()]
			));
		return $root;
	}
}
?>