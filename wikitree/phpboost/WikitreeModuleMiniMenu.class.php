<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Xela <xela@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 02 19
 * @since       PHPBoost 5.1 - 2017 09 11
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class WikitreeModuleMiniMenu extends ModuleMiniMenu
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
		return 'module-mini-wikitree';
	}

	public function get_menu_title()
	{
		return LangLoader::get_message('wikitree.menu.title', 'common', 'wikitree');
	}

	public function is_displayed()
	{
		return ModulesManager::is_module_installed('wiki') && ModulesManager::is_module_activated('wiki');
	}

	public function get_menu_content()
	{
		$view = new FileTemplate('wikitree/WikitreeModuleMiniMenu.tpl');
		$view->add_lang(LangLoader::get_all_langs('wikitree'));
		MenuService::assign_positions_conditions($view, $this->get_block());
		Menu::assign_common_template_variables($view);

		$querier = PersistenceContext::get_querier();

		// root items
		$root_results = $querier->select('SELECT
				id, title, encoded_title, id_cat, is_cat
			FROM ' . PREFIX . 'wiki_articles a
			WHERE id_cat = 0
			AND is_cat = 0
			ORDER BY title ASC'
		);

		$categories = WikiCategoriesCache::load()->get_number_categories();

		$view->put_all(array(
			'C_HAS_ITEMS'  => $root_results->get_rows_count() > 0 || $categories > 0,
			'C_ROOT_ITEMS' => $root_results->get_rows_count() > 0,
			'C_CATEGORIES' => $categories > 0,
		));

		while($root_row = $root_results->fetch())
		{
			$view->assign_block_vars('root_items', array(
				'ITEM_ID'    => $root_row['id'],
				'ITEM_TITLE' => stripslashes($root_row['title']),

				'U_ITEM' => url('wiki.php?title=' . $root_row['encoded_title'], $root_row['encoded_title']),
			));
		}

		// Categories & category's items
		foreach (WikiCategoriesCache::load()->get_categories() as $category)
		{
	        $results = $querier->select('SELECT
					id, title, encoded_title, id_cat, is_cat
				FROM ' . PREFIX . 'wiki_articles a
				WHERE a.id_cat = :cat_id
				AND a.is_cat = 0
				ORDER BY a.title ASC', array(
					'cat_id' => $category['id']
				)
			);

			$view->assign_block_vars('categories', array(
				'C_ITEMS' => $results->get_rows_count() > 0,

				'CATEGORY_ID'         => $category['id'],
	            'CATEGORY_PARENT_ID'  => $category['id_parent'],
	            'CATEGORY_ARTICLE_ID' => $category['article_id'],
	            'CATEGORY_NAME'       => $category['title'] ,

	            'U_CATEGORY' => url('wiki.php?title=' . $category['encoded_title'], $category['encoded_title']),
			));

			while($row = $results->fetch())
			{
				$view->assign_block_vars('categories.items', array(
	            	'ITEM_ID'         => $row['id'],
	                'ITEM_TITLE'      => stripslashes($row['title']),

                	'U_ITEM'       	  => url('wiki.php?title=' . $row['encoded_title'], $row['encoded_title']),
				));
			}
		}

        return $view->render();
	}
}
?>
