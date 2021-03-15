<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 03 15
 * @since       PHPBoost 5.2 - 2020 03 06
*/

class HomeLandingCarousel
{
    public static function get_carousel_view()
	{
        $view = new FileTemplate('HomeLanding/pagecontent/carousel.tpl');
        $view->add_lang(LangLoader::get('common', 'HomeLanding'));
		$config = HomeLandingConfig::load();
        $modules = HomeLandingModulesList::load();
        $carousel = $config->get_carousel();

        $nb_dots = 0;
        foreach ($carousel as $id => $options)
        {
            $view->assign_block_vars('items', array(
                'C_LINK_ONLY'       => $options['link'] && empty($options['picture_url']) && empty($options['description']),
                'U_DEFAULT_PICTURE' => Url::to_rel('/templates/__default__/images/default_item_thumbnail.png'),
                'DESCRIPTION'       => $options['description'],
                'PICTURE_TITLE'     => $options['description'] ? $options['description'] : basename($options['picture_url']),
                'U_PICTURE'         => Url::to_rel($options['picture_url']),
                'LINK'              => Url::to_rel($options['link'])
            ));
            $nb_dots++;
        }

        $view->put_all(array(
            'CAROUSEL_POSITION' => $config->get_module_position_by_id(HomeLandingConfig::MODULE_CAROUSEL),
            'NB_DOTS'           => $nb_dots,
            'CAROUSEL_SPEED'    => $config->get_carousel_speed(),
            'CAROUSEL_TIME'     => $config->get_carousel_time(),
            'CAROUSEL_NUMBER'   => $config->get_carousel_number(),
            'CAROUSEL_AUTO'     => $config->get_carousel_auto(),
            'CAROUSEL_HOVER'    => $config->get_carousel_hover(),
        ));

        return $view;
	}
}
?>
