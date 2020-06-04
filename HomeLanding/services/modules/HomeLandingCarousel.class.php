<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 06 04
 * @since       PHPBoost 5.2 - 2020 03 06
*/

class HomeLandingCarousel
{
    public static function get_carousel_view()
	{
        $view = new FileTemplate('HomeLanding/pagecontent/carousel.tpl');
		$config = HomeLandingConfig::load();
        $modules = HomeLandingModulesList::load();
        $carousel = $config->get_carousel();

        $nb_dots = 0;
        foreach ($carousel as $id => $options)
        {
            $view->assign_block_vars('item', array(
                'DESCRIPTION' => $options['description'],
                'PICTURE_TITLE' => $options['description'] ? $options['description'] : basename($options['picture_url']),
                'PICTURE_URL' => Url::to_rel($options['picture_url']),
                'LINK' => Url::to_rel($options['link'])
            ));
            $nb_dots++;
        }

        $view->put_all(array(
            'CAROUSEL_POSITION' => $config->get_module_position_by_id(HomeLandingConfig::MODULE_CAROUSEL),
            'NB_DOTS' => $nb_dots,
            'CAROUSEL_SPEED' => $config->get_carousel_speed(),
            'CAROUSEL_TIME' => $config->get_carousel_time(),
            'CAROUSEL_NUMBER' => $config->get_carousel_number(),
            'CAROUSEL_AUTO' => $config->get_carousel_auto(),
            'CAROUSEL_HOVER' => $config->get_carousel_hover(),
        ));

        return $view;
	}
}
?>
