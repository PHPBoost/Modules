<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2023 01 17
 * @since       PHPBoost 6.0 - 2023 01 17
*/

class TagcloudModuleMiniMenu extends ModuleMiniMenu
{
	public function get_default_block()
	{
		return self::BLOCK_POSITION__RIGHT;
	}

	public function get_menu_id()
	{
		return 'module-mini-tagcloud';
	}

	public function get_menu_title()
	{
		return LangLoader::get_message('tagcloud.module.title', 'common', 'tagcloud');
	}

	public function get_formated_title()
	{
		return LangLoader::get_message('tagcloud.module.title', 'common', 'tagcloud');
	}

	public function is_displayed()
	{
		return TagcloudAuthorizationsService::check_authorizations()->read();
	}

	public function get_menu_content()
	{
		$lang = LangLoader::get_all_langs('tagcloud');
		$view = new FileTemplate('tagcloud/TagcloudModuleMiniMenu.tpl');
		$view->add_lang($lang);
		MenuService::assign_positions_conditions($view, $this->get_block());
		Menu::assign_common_template_variables($view);

		$result = PersistenceContext::get_querier()->select('SELECT tag.*, tag_rel.*, COUNT(*) AS tags_number
		FROM '. DB_TABLE_KEYWORDS .' tag
		LEFT JOIN '. DB_TABLE_KEYWORDS_RELATIONS .' tag_rel ON tag_rel.id_keyword = tag.id
		WHERE tag.id = tag_rel.id_keyword
		GROUP BY tag_rel.module_id, tag_rel.id_keyword
		ORDER BY tag.name');

		$view->put('C_ITEMS', $result->get_rows_count() > 0);

		while ($row = $result->fetch())
		{
			$module_id = $row['module_id'];
			if (in_array($module_id, array('articles', 'news', 'pages', 'poll')))
			{
				$tag_url = ItemsUrlBuilder::display_tag($row['rewrited_name'], $row['module_id'])->rel();
			}
			else
			{
				$module_url = TextHelper::ucfirst($row['module_id']).'UrlBuilder';
				$tag_url = $module_url::display_tag($row['rewrited_name'])->rel();
			}

			$module_config = ModulesManager::get_module($row['module_id'])->get_configuration();

			$view->assign_block_vars('items', array(
				'ID'           => $row['id'],
				'ID_IN_MODULE' => $row['id_in_module'],
				'NAME'         => $row['name'],
				'TAGS_NUMBER'  => $row['tags_number'],
				'MODULE_NAME'  => $module_config->get_name(),
				'MODULE_ICON'  => $module_config->get_fa_icon(),

				'U_TAG' => $tag_url
			));
		}
		$result->dispose();

		return $view->render();
	}
}
?>
