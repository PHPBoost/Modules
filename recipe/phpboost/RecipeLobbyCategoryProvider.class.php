<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 6.1 - 2026 03 21
*/

class RecipeLobbyCategoryProvider extends DefaultCategoryLobbyProvider
{
	public function get_view(): FileTemplate
	{
		$phpboost_id   = $this->get_phpboost_module_id();
		$module_id     = $this->get_module_id();
		$module_item   = TextHelper::ucfirst($phpboost_id) . 'Item';
		$lobby_module  = LobbyModulesList::load()[$module_id];
		$module        = ModulesManager::get_module($phpboost_id);
		$module_config = $module->get_configuration()->get_configuration_parameters();
		$now           = new Date();

		$view = $this->get_lobby_template('ItemsLobbyProvider.tpl');
		$view->add_lang(array_merge(
            LangLoader::get_all_langs('lobby'),
            LangLoader::get_module_langs($phpboost_id)
        ));

		$categories = $lobby_module->is_subcategories_content_displayed() ? CategoriesService::get_authorized_categories($lobby_module->get_id_category(), $module_config->are_descriptions_displayed_to_guests(), $phpboost_id) : [$lobby_module->get_id_category()];

        $sql_conditions = '
            WHERE id_category IN :categories
            AND (published = 1 OR (published = 2 AND publishing_start_date < :now AND (publishing_end_date > :now OR publishing_end_date = 0)))
        ';

		$result = PersistenceContext::get_querier()->select('
                SELECT 
                    item.*,
                    member.*,
                    cat.rewrited_name AS rewrited_name_cat,
                    notes.average_notes, notes.notes_number, note.note
                FROM ' . PREFIX . $phpboost_id . ' item
                LEFT JOIN ' . PREFIX . $phpboost_id . '_cats cat ON cat.id = item.id_category
                LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = item.author_user_id
                LEFT JOIN ' . DB_TABLE_AVERAGE_NOTES . ' notes ON notes.id_in_module = item.id AND notes.module_name = \'' . $phpboost_id . '\'
                LEFT JOIN ' . DB_TABLE_NOTE . ' note ON note.id_in_module = item.id AND note.module_name = \'' . $phpboost_id . '\'
                ' . $sql_conditions . '
                ORDER BY item.rewrited_title ASC
                LIMIT :limit
            ', [
                'categories'    => $categories,
                'now' => $now->get_timestamp(),
                'limit'     => $lobby_module->get_elements_number_displayed()
            ]
        );

		$category = CategoriesService::get_categories_manager($phpboost_id)
			->get_categories_cache()
			->get_category($lobby_module->get_id_category());

		$view->put_all([
			'C_NO_ITEM'          => $result->get_rows_count() == 0,
            'C_CATEGORY'         => true,
			'C_VIEWS_NUMBER'     => true,
			'C_LIST_VIEW'        => $module_config->get_display_type() == DefaultRichModuleConfig::LIST_VIEW,
			'C_GRID_VIEW'        => $module_config->get_display_type() == DefaultRichModuleConfig::GRID_VIEW,
			'C_TABLE_VIEW'       => $module_config->get_display_type() == DefaultRichModuleConfig::TABLE_VIEW,
			'C_AUTHOR_DISPLAYED' => true,
			'MODULE_NAME'        => $phpboost_id,
			'MODULE_POSITION'    => LobbyConfig::load()->get_module_position_by_id($module_id),
			'ITEMS_PER_ROW'      => $module_config->get_items_per_row(),
			'L_MODULE_TITLE'     => $module->get_configuration()->get_name(),
            'L_CATEGORY_NAME'    => $category->get_name(),
		]);

		while ($row = $result->fetch())
        {
            $item = new $module_item();
            $item->set_properties($row);

            $view->assign_block_vars('items', array_merge($item->get_template_vars(), [
                'C_SEVERAL_VIEWS' => $item->get_views_number() > 1,
            ]));
        }
        $result->dispose();

		return $view;
	}
}
?>
