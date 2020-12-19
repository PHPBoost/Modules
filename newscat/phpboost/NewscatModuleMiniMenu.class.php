<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 12 19
 * @since       PHPBoost 5.2 - 2018 11 27
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
		if(NewscatConfig::load()->get_only_news_module())
			return Url::is_current_url('/news/') && CategoriesAuthorizationsService::check_authorizations(Category::ROOT_CATEGORY, 'news')->read();
		else
			return true;
	}

	public function display($view = false)
	{
		if ($this->is_displayed())
		{
			$config = NewscatConfig::load();

			$view = new FileTemplate('newscat/NewscatModuleMiniMenu.tpl');
			$view->add_lang(LangLoader::get('common', 'newscat'));

			if(ModulesManager::is_module_installed('news') && ModulesManager::is_module_activated('news')) {
				$now = new Date();
				$authorized_categories = CategoriesService::get_authorized_categories(Category::ROOT_CATEGORY, NewsConfig::load()->is_summary_displayed_to_guests(), 'news');

				$result_cat = PersistenceContext::get_querier()->select('SELECT news_cat.*
				FROM '. NewsSetup::$news_cats_table .' news_cat
				WHERE news_cat.id IN :authorized_categories
				ORDER BY news_cat.id ASC', array(
					'authorized_categories' => $authorized_categories
				));

				$newscat_number = 0;
				while ($row_cat = $result_cat->fetch())
				{
					$newscat_number++;
					$view->assign_block_vars('items', array(
						'ID' => $row_cat['id'],
						'SUB_ORDER' => $row_cat['c_order'],
						'ID_PARENT' => $row_cat['id_parent'],
						'CATEGORY_NAME' => $row_cat['name'],
						'U_CATEGORY' => NewsUrlBuilder::display_category($row_cat['id'], $row_cat['rewrited_name'])->rel()
					));

					$view->put_all(array(
						'C_MENU_VERTICAL' => ($this->get_block() == Menu::BLOCK_POSITION__LEFT) || ($this->get_block() == Menu::BLOCK_POSITION__RIGHT),
						'C_MENU_LEFT' => $this->get_block() == Menu::BLOCK_POSITION__LEFT,
						'C_MENU_RIGHT' => $this->get_block() == Menu::BLOCK_POSITION__RIGHT,
						'C_NEWS' => ModulesManager::is_module_installed('news') && ModulesManager::is_module_activated('news'),
						'MODULE_ID' => $this->get_menu_id(),
						'MODULE_TITLE' => $config->get_module_name(),
						'C_CAT' => $newscat_number > 0,
					));
				}
				return $view->render();
			}
		}
		return '';
	}
}
?>
