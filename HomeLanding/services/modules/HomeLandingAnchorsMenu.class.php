<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 07 13
 * @since       PHPBoost 5.2 - 2020 03 06
*/

class HomeLandingAnchorsMenu
{
    public static function get_anchors_menu_view()
	{
        $view = new FileTemplate('HomeLanding/pagecontent/anchors-menu.tpl');
		$config = HomeLandingConfig::load();
        $modules = HomeLandingModulesList::load();

        foreach($config->get_modules() as $key => $module)
        {
            if(!in_array($module['module_id'], array('anchors_menu', 'carousel')))
            {
                if(isset($module['id_category']))
                    $module_title = CategoriesService::get_categories_manager($module['phpboost_module_id'])->get_categories_cache()->get_category($modules[$module['module_id']]->get_id_category())->get_name();
                else
                    $module_title = LangLoader::get_message('anchors.' . $module['module_id'], 'common', 'HomeLanding');

                $view->assign_block_vars('tabs', array(
                    'C_DISPLAYED_TAB' => $module['displayed'],
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
