<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 6.1 - 2026 03 21
*/

class MediaLobbyProvider extends DefaultModuleLobbyProvider
{
	public function get_module_id(): string
	{
		return 'media';
	}

	// Media uses categories for authorisations but has no category sub-view in lobby
	public function has_categories(): bool
	{
		return false;
	}

	public function get_view(): FileTemplate
	{
		$module_id     = $this->get_module_id();
		$module        = LobbyModulesList::load()[$module_id];
		$module_config = MediaConfig::load();

		$view = $this->get_lobby_template('MediaLobbyProvider.tpl');
		$view->add_lang(array_merge(LangLoader::get_all_langs(), LangLoader::get_all_langs('lobby'), LangLoader::get_all_langs($module_id)));

		$authorized_categories = CategoriesService::get_authorized_categories(Category::ROOT_CATEGORY, true, $module_id, 'id_category');

		$result = PersistenceContext::get_querier()->select('
                SELECT
                    media.*,
                    mb.display_name, mb.user_groups, mb.level,
                    notes.average_notes, notes.notes_number,
                    note.note
                FROM ' . PREFIX . 'media AS media
                LEFT JOIN ' . PREFIX . "media_cats cat ON cat.id = media.id_category
                LEFT JOIN " . DB_TABLE_MEMBER . ' AS mb ON media.author_user_id = mb.user_id
                LEFT JOIN ' . DB_TABLE_AVERAGE_NOTES . " notes ON notes.id_in_module = media.id AND notes.module_name = 'media'
                LEFT JOIN " . DB_TABLE_NOTE . " note ON note.id_in_module = media.id AND note.module_name = 'media' AND note.user_id = :user_id
                WHERE id_category IN :authorized_categories AND published = 2
                ORDER BY media.creation_date DESC
                LIMIT :limit
            ", [
				'authorized_categories' => $authorized_categories,
				'user_id'               => AppContext::get_current_user()->get_id(),
				'limit'                 => $module->get_elements_number_displayed(),
			]
		);

		$view->put_all([
			'C_ITEMS'        => $result->get_rows_count() > 0,
			'C_LIST_VIEW'    => $module_config->get_display_type() == MediaConfig::LIST_VIEW,
			'C_GRID_VIEW'    => $module_config->get_display_type() == MediaConfig::GRID_VIEW,
			'MODULE_NAME'    => $module_id,
			'MODULE_POSITION' => LobbyConfig::load()->get_module_position_by_id($module_id),
			'ITEMS_PER_ROW'  => $module_config->get_items_per_row(),
			'L_MODULE_TITLE' => ModulesManager::get_module($module_id)->get_configuration()->get_name(),
		]);

		while ($row = $result->fetch())
		{
			$summary = TextHelper::cut_string(
				@strip_tags(FormatingHelper::second_parse($row['content']), '<br><br/>'),
				$module->get_characters_number_displayed()
			);

			$view->assign_block_vars('items', [
				'C_SUMMARY'       => !empty($row['content']),
				'C_AUDIO'         => $row['mime_type'] == 'audio/mpeg',
				'C_HAS_THUMBNAIL' => !empty($row['thumbnail']),
				'TITLE'           => $row['title'],
				'SUMMARY'         => $summary,
				'U_THUMBNAIL'     => !empty($row['thumbnail']) ? Url::to_rel($row['thumbnail']) : '',
				'U_ITEM'          => PATH_TO_ROOT . '/modules/media/' . url('media.php?id=' . $row['id'], $row['id'] . '.php'),
			]);
		}
		$result->dispose();

		return $view;
	}
}
?>
