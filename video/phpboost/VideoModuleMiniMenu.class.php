<?php
/**
 * @copyright   &copy; 2005-2025 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 10 17
 * @since       PHPBoost 6.0 - 2022 10 17
 */

class VideoModuleMiniMenu extends ModuleMiniMenu
{
	public function get_default_block()
	{
		return self::BLOCK_POSITION__RIGHT;
	}

	public function get_menu_id()
	{
		return 'module-mini-video';
	}

	public function get_menu_title()
	{
		return VideoConfig::load()->is_sort_type_date() ? LangLoader::get_message('video.last.items', 'common', 'video') : LangLoader::get_message('video.popular', 'common', 'video');
	}

	public function get_formated_title()
	{
		return LangLoader::get_message('video.module.title', 'common', 'video');
	}

	public function is_displayed()
	{
		return true;
	}

	public function get_menu_content()
	{
		// Create file template
		$view = new FileTemplate('video/VideoModuleMiniMenu.tpl');

		// Assign the lang file to the tpl
		$view->add_lang(LangLoader::get_all_langs('video'));

		// Assign common menu variables to the tpl
		MenuService::assign_positions_conditions($view, $this->get_block());

		// Load module config
		$config = VideoConfig::load();

		// Load module cache
		$video_cache = VideoCache::load();

		// Load categories cache
		$categories_cache = CategoriesService::get_categories_manager('video')->get_categories_cache();

		$items = $video_cache->get_items();

		$view->put_all(array(
			'C_ITEMS' => !empty($items)
		));

		$displayed_position = 1;
		foreach ($items as $file)
		{
			$item = new VideoItem();
			$item->set_properties($file);

			$view->assign_block_vars('items', array_merge($item->get_template_vars(), array(
				'DISPLAYED_POSITION' => $displayed_position
			)));

			$displayed_position++;
		}

		return $view->render();
	}
}
?>
