<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 6.1 - 2026 03 21
*/

class GalleryLobbyProvider extends DefaultModuleLobbyProvider
{
    public function get_module_id(): string
    {
        return 'gallery';
    }

    public function has_categories(): bool
    {
        return false;
    }

    public function get_config_fields(LobbyModule $module): array
    {
        // Gallery has no chars limit — override to return only the items limit field
        $module_id = $this->get_module_id();
        $lang      = LangLoader::get_module_langs('lobby');

        return [
            new FormFieldNumberEditor(
                $module_id . '_limit',
                $lang['lobby.items.number'],
                $module->get_elements_number_displayed(),
                ['min' => 1, 'max' => 50, 'required' => true, 'hidden' => !$module->is_displayed()]
            ),
        ];
    }

    public function get_fields_visibility(LobbyModule $module): array
    {
        return [$this->get_module_id() . '_limit' => !$module->is_displayed()];
    }

    public function save(HTMLForm $form, LobbyModule $module): void
    {
        $module->set_elements_number_displayed(
            (int) $form->get_value($this->get_module_id() . '_limit')
        );
    }

    public function get_view(): FileTemplate
    {
        $module_id     = $this->get_module_id();
        $module        = LobbyModulesList::load()[$module_id];
        $module_config = GalleryConfig::load();

        $view = $this->get_lobby_template('GalleryLobbyProvider.tpl');
        $view->add_lang(array_merge(LangLoader::get_all_langs(), LangLoader::get_all_langs('lobby'), LangLoader::get_all_langs($module_id)));

        $categories = CategoriesService::get_authorized_categories(Category::ROOT_CATEGORY, true, $module_id, 'id_category');

        $result = PersistenceContext::get_querier()->select('
                SELECT
                    g.id, g.id_category, g.name, g.path, g.timestamp, g.aprob, g.width, g.height, g.user_id, g.views,
                    m.display_name, m.user_groups, m.level,
                    notes.average_notes, notes.notes_number, note.note
                FROM ' . PREFIX . 'gallery g
                LEFT JOIN ' . PREFIX . 'gallery_cats cat ON cat.id = g.id_category
                LEFT JOIN ' . DB_TABLE_MEMBER . ' m ON m.user_id = g.user_id
                LEFT JOIN ' . DB_TABLE_AVERAGE_NOTES . " notes ON notes.id_in_module = g.id AND notes.module_name = 'gallery'
                LEFT JOIN " . DB_TABLE_NOTE . " note ON note.id_in_module = g.id AND note.module_name = 'gallery' AND note.user_id = :user_id
                WHERE id_category IN :categories
                ORDER BY g.timestamp DESC
                LIMIT :limit
            ", [
                'categories' => $categories,
                'user_id'    => AppContext::get_current_user()->get_id(),
                'limit'      => $module->get_elements_number_displayed(),
            ]
        );

        $view->put_all([
            'C_NO_ITEM'        => $result->get_rows_count() == 0,
            'C_MODULE_LINK'    => true,
            'C_VIEWS_ENABLED'  => $module_config->is_views_counter_enabled(),
            'MODULE_NAME'      => $module_id,
            'MODULE_POSITION'  => LobbyConfig::load()->get_module_position_by_id($module_id),
            'ITEMS_PER_ROW'    => $module_config->get_columns_number(),
            'L_MODULE_TITLE'   => ModulesManager::get_module($module_id)->get_configuration()->get_name(),
        ]);

        while ($row = $result->fetch())
        {
            $category = CategoriesService::get_categories_manager('gallery')->get_categories_cache()->get_category($row['id_category']);
            $view->assign_block_vars('items', [
                'U_PICTURE'    => Url::to_rel('/modules/gallery/pics/' . $row['path']),
                'TITLE'        => !empty($row['name']) ? $row['name'] : $row['path'],
                'VIEWS_NUMBER' => $row['views'],
                'U_CATEGORY'   => Url::to_rel('/gallery/gallery' . url('.php?cat=' . $row['id_category'], '-' . $row['id_category'] . '-' . $category->get_rewrited_name() . '.php'))
			]);
        }
        $result->dispose();

        return $view;
    }
}
?>
