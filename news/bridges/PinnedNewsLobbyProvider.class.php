<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 6.1 - 2026 03 21
*/

class PinnedNewsLobbyProvider extends DefaultModuleLobbyProvider
{
	public function get_module_id(): string
	{
		return 'pinned_news';
	}

	public function get_phpboost_module_id(): string
	{
		return 'news';
	}

	public function get_module_name(): string
	{
		return LangLoader::get_message('news.lobby.pinned.news', 'common', 'news');
	}

	/**
	 * Pinned news is a sub-view of news: it filters items with top_list_enabled = 1.
	 * It has no category selector of its own.
	 */
	public function has_categories(): bool
	{
		return false;
	}

	public function is_category_view(): bool
	{
		return false;
	}

	public function get_config_fields(LobbyModule $module): array
	{
		$module_id = $this->get_module_id();
		$lang      = LangLoader::get_all_langs('lobby');

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
		return [
			$this->get_module_id() . '_limit' => !$module->is_displayed(),
		];
	}

	public function save(HTMLForm $form, LobbyModule $module): void
	{
		$module->set_elements_number_displayed(
			$form->get_value($this->get_module_id() . '_limit')
		);
	}

	public function get_view(): FileTemplate
	{
		$phpboost_id   = $this->get_phpboost_module_id();
		$module_id     = $this->get_module_id();
		$module        = LobbyModulesList::load()[$module_id];
		$mod           = ModulesManager::get_module($phpboost_id);
		$module_config = $mod->get_configuration()->get_configuration_parameters();
		$now           = new Date();

		$view = $this->get_lobby_template('PinnedNewsLobbyProvider.tpl');
		$view->add_lang(array_merge(LangLoader::get_all_langs(), LangLoader::get_all_langs('lobby'), LangLoader::get_all_langs($phpboost_id)));

		$categories = CategoriesService::get_authorized_categories(
			Category::ROOT_CATEGORY,
			$mod->get_configuration()->has_rich_config_parameters() ? $module_config->get_summary_displayed_to_guests() : true,
			$phpboost_id
		);

		$sql_condition = '
            WHERE id_category IN :categories
			AND top_list_enabled = 1
			AND (
                published = ' . Item::PUBLISHED . ' OR (
                    published = ' . Item::DEFERRED_PUBLICATION . '
                    AND publishing_start_date < :timestamp_now
                    AND (publishing_end_date > :timestamp_now OR publishing_end_date = 0)
                )
            )
        ';

		$items = ItemsService::get_items_manager($phpboost_id)->get_items(
			$sql_condition,
			['categories' => $categories, 'timestamp_now' => $now->get_timestamp()],
			$module->get_elements_number_displayed(), 0, 'creation_date', Item::DESC
		);

		$view->put_all([
			'C_NO_ITEM'          => count($items) == 0,
			'C_VIEWS_NUMBER'     => $module_config->get_views_number_enabled(),
			'C_LIST_VIEW'        => $module_config->get_display_type() == DefaultRichModuleConfig::LIST_VIEW,
			'C_GRID_VIEW'        => $module_config->get_display_type() == DefaultRichModuleConfig::GRID_VIEW,
			'C_TABLE_VIEW'       => $module_config->get_display_type() == DefaultRichModuleConfig::TABLE_VIEW,
			'C_AUTHOR_DISPLAYED' => $module_config->get_author_displayed(),
			'MODULE_NAME'        => $this->get_module_name(),
			'MODULE_POSITION'    => LobbyConfig::load()->get_module_position_by_id($module_id),
			'ITEMS_PER_ROW'      => $module_config->get_items_per_row(),
		]);

		foreach ($items as $item)
		{
			$view->assign_block_vars('items', $item->get_template_vars());
		}

		return $view;
	}
}
?>
