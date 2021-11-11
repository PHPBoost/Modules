<?php
/**
 * @copyright   &copy; 2005-2021 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 11 11
 * @since       PHPBoost 5.2 - 2020 03 06
*/

class HomeLandingAnchorsMenu
{
    public static function get_anchors_menu_view()
	{
        $lang = LangLoader::get('common', 'HomeLanding');
        $view = new FileTemplate('HomeLanding/pagecontent/anchors-menu.tpl');
        $view->add_lang($lang);
		$config = HomeLandingConfig::load();
        $modules = HomeLandingModulesList::load();

        foreach($config->get_modules() as $key => $module)
        {
            if(!in_array($module['module_id'], array('anchors_menu', 'carousel')) && $module['displayed'] == 1)
            {
                if(isset($module['id_category'])) {
                    if($module['id_category'] != Category::ROOT_CATEGORY)
                        $module_title = $lang['homelanding.category.' . $module['module_id'] ] . '/' . CategoriesService::get_categories_manager($module['phpboost_module_id'])->get_categories_cache()->get_category($modules[$module['module_id']]->get_id_category())->get_name();
                    else
                        $module_title = $lang['homelanding.category.' . $module['module_id']];
                }
                else {
                    if(!in_array($module['module_id'], array('edito', 'lastcoms', 'rss', 'pinned_news')))
                        $module_title = ModulesManager::get_module($module['module_id'])->get_configuration()->get_name();
                    else
                        $module_title = $lang['homelanding.module.' . $module['module_id']];
                }

                $module_displayed = $module['displayed'];
                if ($module['module_id'] == 'pinned_news') {
                    $module_displayed = $module['displayed'] && HomeLandingPinnedNews::get_items_number() > 0;
                    $module_title = $config->get_pinned_news_title();
                }


                $view->assign_block_vars('tabs', array(
                    'C_DISPLAYED_TAB' => $module_displayed,
                    'TAB_POSITION'    => $config->get_module_position_by_id($module['module_id']),
                    'U_TAB'           => '#' . $module['module_id'],
                    'TAB_TITLE'       => $module_title
                ));
            }
        }

        $view->put_all(array(
            'MENU_POSITION' => $config->get_module_position_by_id(HomeLandingConfig::MODULE_ANCHORS_MENU),
        ));

        return $view;
	}
}
?>
