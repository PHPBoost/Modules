<?php
/**
 * @copyright   &copy; 2005-2021 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 05 27
 * @since       PHPBoost 6.0 - 2021 03 03
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class CategoriesMenusModuleMiniMenu extends ModuleMiniMenu
{
	private $module_id;
	private $module_configuration;

	public function __construct($module_id, $module_configuration)
	{
		$this->module_id = $module_id;
		$this->module_configuration = $module_configuration;
		parent::__construct($this->get_formated_title());
	}

	public function get_default_block()
	{
		return self::BLOCK_POSITION__RIGHT;
	}

	public function admin_display()
	{
		return '';
	}

	public function get_menu_id()
	{
		return 'module-mini-' . $this->module_id . '-categories';
	}

	public function get_menu_title()
	{
		return $this->module_configuration->get_name() . ' - ' . LangLoader::get_message('category.categories', 'category-lang');
	}

	public function get_formated_title()
	{
		return $this->get_menu_title();
	}

	public function is_displayed()
	{
		return ModulesManager::is_module_installed($this->module_id) && ModulesManager::is_module_activated($this->module_id) && CategoriesService::get_categories_manager($this->module_id)->get_categories_cache()->has_categories() && CategoriesAuthorizationsService::check_authorizations(Category::ROOT_CATEGORY, $this->module_id)->read();
	}

	public function get_menu_content()
	{
		$view = new FileTemplate('CategoriesMenus/CategoriesMenusModuleMiniMenu.tpl');
		MenuService::assign_positions_conditions($view, $this->get_block());
		$this->assign_common_template_variables($view);

		$authorized_categories = CategoriesService::get_authorized_categories(Category::ROOT_CATEGORY, ($this->module_configuration->has_rich_config_parameters() ? $this->module_configuration->get_configuration_parameters()->get_summary_displayed_to_guests() : true), $this->module_id);
		$categories_number = 0;

		foreach (CategoriesService::get_categories_manager($this->module_id)->get_categories_cache()->get_categories() as $category)
		{
			if ($category->get_id() != Category::ROOT_CATEGORY && in_array($category->get_id(), $authorized_categories))
			{
				$categories_number++;
				$view->assign_block_vars('items', array(
					'ID'            => $category->get_id(),
					'SUB_ORDER'     => $category->get_order(),
					'ID_PARENT'     => $category->get_id_parent(),
					'CATEGORY_NAME' => $category->get_name(),
					'U_CATEGORY'    => CategoriesUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $this->module_id)->rel()
				));
			}
		}

		$view->put_all(array(
			'C_CATEGORIES' => $categories_number > 0,
			'MODULE_ID'    => $this->module_id,
			'MENU_ID'      => $this->get_menu_id(),
			'MENU_TITLE'   => $this->get_menu_title()
		));

		return $view->render();
	}
}
?>
