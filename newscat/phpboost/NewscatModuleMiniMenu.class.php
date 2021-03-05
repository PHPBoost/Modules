<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 03 06
 * @since       PHPBoost 5.2 - 2018 11 27
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class NewscatModuleMiniMenu extends ModuleMiniMenu
{
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
		return 'module-mini-newscat';
	}

	public function is_displayed()
	{
		return (NewscatConfig::load()->get_only_news_module()) ? (Url::is_current_url('/news/') && CategoriesAuthorizationsService::check_authorizations(Category::ROOT_CATEGORY, 'news')->read()) : true;
	}

	public function display($view = false)
	{
		if ($this->is_displayed())
		{
			$view = new FileTemplate('newscat/NewscatModuleMiniMenu.tpl');
			$view->add_lang(LangLoader::get('common', 'newscat'));
			MenuService::assign_positions_conditions($view, $this->get_block());
			$this->assign_common_template_variables($view);

			if (ModulesManager::is_module_installed('news') && ModulesManager::is_module_activated('news'))
			{
				$authorized_categories = CategoriesService::get_authorized_categories(Category::ROOT_CATEGORY, NewsConfig::load()->is_summary_displayed_to_guests(), 'news');
				$categories_number = 0;
				
				foreach (CategoriesService::get_categories_manager('news')->get_categories_cache()->get_categories() as $category)
				{
					if ($category->get_id() != Category::ROOT_CATEGORY && in_array($category->get_id(), $authorized_categories))
					{
						$categories_number++;
						$view->assign_block_vars('items', array(
							'ID'            => $category->get_id(),
							'SUB_ORDER'     => $category->get_order(),
							'ID_PARENT'     => $category->get_id_parent(),
							'CATEGORY_NAME' => $category->get_name(),
							'U_CATEGORY'    => CategoriesUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name(), 'news')->rel()
						));
					}
				}

				$view->put_all(array(
					'C_NEWS'          => true,
					'C_CAT'           => $categories_number > 0,
					'MODULE_ID'       => $this->get_menu_id(),
					'MODULE_TITLE'    => NewscatConfig::load()->get_module_name()
				));
				
				return $view->render();
			}
		}
		return '';
	}
}
?>
