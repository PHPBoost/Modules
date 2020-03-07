<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 03 06
 * @since       PHPBoost 5.2 - 2020 03 06
*/

class AdvancedHomeLandingOnepage
{
    public static function get_onepage_view()
	{
        $tpl = new FileTemplate('HomeLanding/pagecontent/onepage.tpl');
		$config = HomeLandingConfig::load();
        $modules = HomeLandingModulesList::load();

        if($modules[HomeLandingConfig::MODULE_ARTICLES_CATEGORY]->is_displayed())
            $articles_cat = CategoriesService::get_categories_manager(HomeLandingConfig::MODULE_ARTICLES)->get_categories_cache()->get_category($modules[HomeLandingConfig::MODULE_ARTICLES_CATEGORY]->get_id_category())->get_name();
        else
            $articles_cat = '';

        if($modules[HomeLandingConfig::MODULE_DOWNLOAD_CATEGORY]->is_displayed())
            $download_cat = CategoriesService::get_categories_manager(HomeLandingConfig::MODULE_DOWNLOAD)->get_categories_cache()->get_category($modules[HomeLandingConfig::MODULE_DOWNLOAD_CATEGORY]->get_id_category())->get_name();
        else
            $download_cat = '';

        if($modules[HomeLandingConfig::MODULE_NEWS_CATEGORY]->is_displayed())
            $news_cat = CategoriesService::get_categories_manager(HomeLandingConfig::MODULE_NEWS)->get_categories_cache()->get_category($modules[HomeLandingConfig::MODULE_NEWS_CATEGORY]->get_id_category())->get_name();
        else
            $news_cat = '';

        if($modules[HomeLandingConfig::MODULE_WEB_CATEGORY]->is_displayed())
            $web_cat = CategoriesService::get_categories_manager(HomeLandingConfig::MODULE_WEB)->get_categories_cache()->get_category($modules[HomeLandingConfig::MODULE_WEB_CATEGORY]->get_id_category())->get_name();
        else
            $web_cat = '';

        $tpl->put_all(array(
            // location of the menu in the page
            'ONEPAGE_POSITION' => $config->get_module_position_by_id(HomeLandingConfig::MODULE_ONEPAGE_MENU),

            // Presence of modules on the page
            'C_DISPLAYED_EDITO' => $modules[HomeLandingConfig::MODULE_EDITO]->is_displayed(),
            'C_DISPLAYED_CAROUSEL' => $modules[HomeLandingConfig::MODULE_CAROUSEL]->is_displayed(),
            'C_DISPLAYED_LASTCOMS' => $modules[HomeLandingConfig::MODULE_LASTCOMS]->is_displayed(),
            'C_DISPLAYED_ARTICLES' => $modules[HomeLandingConfig::MODULE_ARTICLES]->is_displayed() && CategoriesAuthorizationsService::check_authorizations(Category::ROOT_CATEGORY, HomeLandingConfig::MODULE_ARTICLES)->read(),
            'C_DISPLAYED_ARTICLES_CAT' => $modules[HomeLandingConfig::MODULE_ARTICLES_CATEGORY]->is_displayed() && CategoriesAuthorizationsService::check_authorizations($modules[HomeLandingConfig::MODULE_ARTICLES_CATEGORY]->get_id_category(), HomeLandingConfig::MODULE_ARTICLES)->read(),
            'C_DISPLAYED_CONTACT' => $modules[HomeLandingConfig::MODULE_CONTACT]->is_displayed() && ContactAuthorizationsService::check_authorizations()->read(),
            'C_DISPLAYED_EVENTS' => $modules[HomeLandingConfig::MODULE_CALENDAR]->is_displayed() && CategoriesAuthorizationsService::check_authorizations()->read(),
            'C_DISPLAYED_DOWNLOAD' => $modules[HomeLandingConfig::MODULE_DOWNLOAD]->is_displayed() && DownloadAuthorizationsService::check_authorizations()->read(),
            'C_DISPLAYED_DOWNLOAD_CAT' => $modules[HomeLandingConfig::MODULE_DOWNLOAD_CATEGORY]->is_displayed() && DownloadAuthorizationsService::check_authorizations($modules[HomeLandingConfig::MODULE_DOWNLOAD_CATEGORY]->get_id_category())->read(),
            'C_DISPLAYED_FORUM' => $modules[HomeLandingConfig::MODULE_FORUM]->is_displayed() && ForumAuthorizationsService::check_authorizations()->read(),
            'C_DISPLAYED_GALLERY' => $modules[HomeLandingConfig::MODULE_GALLERY]->is_displayed() && CategoriesAuthorizationsService::check_authorizations()->read(),
            'C_DISPLAYED_GUESTBOOK' => $modules[HomeLandingConfig::MODULE_GUESTBOOK]->is_displayed() && GuestbookAuthorizationsService::check_authorizations()->read(),
            'C_DISPLAYED_MEDIA' => $modules[HomeLandingConfig::MODULE_MEDIA]->is_displayed() && CategoriesAuthorizationsService::check_authorizations()->read(),
            'C_DISPLAYED_NEWS' => $modules[HomeLandingConfig::MODULE_NEWS]->is_displayed() && CategoriesAuthorizationsService::check_authorizations(Category::ROOT_CATEGORY, HomeLandingConfig::MODULE_NEWS)->read(),
            'C_DISPLAYED_NEWS_CAT' => $modules[HomeLandingConfig::MODULE_NEWS_CATEGORY]->is_displayed() && CategoriesAuthorizationsService::check_authorizations($modules[HomeLandingConfig::MODULE_NEWS_CATEGORY]->get_id_category(), HomeLandingConfig::MODULE_NEWS)->read(),
            'C_DISPLAYED_WEB' => $modules[HomeLandingConfig::MODULE_WEB]->is_displayed() && CategoriesAuthorizationsService::check_authorizations()->read(),
            'C_DISPLAYED_WEB_CAT' => $modules[HomeLandingConfig::MODULE_WEB_CATEGORY]->is_displayed() && CategoriesAuthorizationsService::check_authorizations($modules[HomeLandingConfig::MODULE_WEB_CATEGORY]->get_id_category())->read(),
            'C_DISPLAYED_RSS' => $modules[HomeLandingConfig::MODULE_RSS]->is_displayed(),

            // Names of categories
            'ARTICLES_CAT' => $category = $articles_cat,
            'DOWNLOAD_CAT' => $category = $download_cat,
            'NEWS_CAT' => $category = $news_cat,
            'WEB_CAT' => $category = $web_cat,

            // Position of the tabs in the menu
            'OPM_EDITO_POS' => $config->get_module_position_by_id(HomeLandingConfig::MODULE_EDITO),
            'OPM_LASTCOMS_POS' => $config->get_module_position_by_id(HomeLandingConfig::MODULE_LASTCOMS),
            'OPM_ARTICLES_POS' => $config->get_module_position_by_id(HomeLandingConfig::MODULE_ARTICLES),
            'OPM_ARTICLES_CAT_POS' => $config->get_module_position_by_id(HomeLandingConfig::MODULE_ARTICLES_CATEGORY),
            'OPM_CONTACT_POS' => $config->get_module_position_by_id(HomeLandingConfig::MODULE_CONTACT),
            'OPM_EVENTS_POS' => $config->get_module_position_by_id(HomeLandingConfig::MODULE_CALENDAR),
            'OPM_DOWNLOAD_POS' => $config->get_module_position_by_id(HomeLandingConfig::MODULE_DOWNLOAD),
            'OPM_DOWNLOAD_CAT_POS' => $config->get_module_position_by_id(HomeLandingConfig::MODULE_DOWNLOAD_CATEGORY),
            'OPM_FORUM_POS' => $config->get_module_position_by_id(HomeLandingConfig::MODULE_FORUM),
            'OPM_GALLERY_POS' => $config->get_module_position_by_id(HomeLandingConfig::MODULE_GALLERY),
            'OPM_GUESTBOOK_POS' => $config->get_module_position_by_id(HomeLandingConfig::MODULE_GUESTBOOK),
            'OPM_MEDIA_POS' => $config->get_module_position_by_id(HomeLandingConfig::MODULE_MEDIA),
            'OPM_NEWS_POS' => $config->get_module_position_by_id(HomeLandingConfig::MODULE_NEWS),
            'OPM_NEWS_CAT_POS' => $config->get_module_position_by_id(HomeLandingConfig::MODULE_NEWS_CATEGORY),
            'OPM_WEB_POS' => $config->get_module_position_by_id(HomeLandingConfig::MODULE_WEB),
            'OPM_WEB_CAT_POS' => $config->get_module_position_by_id(HomeLandingConfig::MODULE_WEB_CATEGORY),
            'OPM_RSS_POS' => $config->get_module_position_by_id(HomeLandingConfig::MODULE_RSS),
        ));

        return $tpl;
	}
}
?>
