<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 3.0 - 2011 03 11
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 */

define('PATH_TO_ROOT', '../..');

require_once PATH_TO_ROOT . '/kernel/init.php';

$url_controller_mappers = [
    //Admin
    new UrlControllerMapper('AdminNewsletterConfigController', '`^/admin(?:/config)?/?$`'),

    //Streams
    new UrlControllerMapper('NewsletterStreamsManagementController', '`^/streams/?$`'),
    new UrlControllerMapper('NewsletterStreamsFormController', '`^/stream/add/?$`'),
    new UrlControllerMapper('NewsletterStreamsFormController', '`^/stream/([0-9]+)/edit/?$`', ['id']),
    new UrlControllerMapper('NewsletterDeleteStreamController', '`^/stream/([0-9]+)/delete/?$`', ['id']),

    //Newsletter
    new UrlControllerMapper('HomeAddNewsletterController', '`^/add/?$`'),
    new UrlControllerMapper('AddNewsletterController', '`^/add/([a-z]+)?/?$`', ['type']),

    //Suscribers
    new UrlControllerMapper('NewsletterSubscribersListController', '`^/subscribers/([0-9]+)-([a-z0-9-_]+)/?([a-z]+)?/?([a-z]+)?/?([0-9]+)?/?$`', ['id_stream', 'rewrited_name_stream', 'field', 'sort', 'page']),
    new UrlControllerMapper('NewsletterSubscribeController', '`^/subscribe/?$`'),
    new UrlControllerMapper('NewsletterUnsubscribeController', '`^/unsubscribe/?$`'),
    new UrlControllerMapper('NewsletterEditSubscriberController', '`^/subscriber/([0-9]+)/edit/?$`', ['id']),
    new UrlControllerMapper('NewsletterDeleteSubscriberController', '`^/subscriber/([0-9]+)/delete(?:/([0-9]+))?/?$`', ['id', 'id_stream']),

    //Archives
    new UrlControllerMapper('NewsletterArchivesController', '`^/archives(?:/([0-9]+)-([a-z0-9-_]+))?/?([a-z]+)?/?([a-z]+)?/?([0-9]+)?/?$`', ['id_stream', 'rewrited_name_stream', 'field', 'sort', 'page']),
    new UrlControllerMapper('NewsletterArchiveController', '`^/archive/([0-9]+)?/?$`', ['id']),
    new UrlControllerMapper('NewsletterDeleteArchiveController', '`^/delete/([0-9]+)/([0-9]+)/?$`', ['id', 'id_stream']),

    new UrlControllerMapper('NewsletterHomeController', '`^(?:/([0-9]+))?/?$`', ['page']),
];
DispatchManager::dispatch($url_controller_mappers);
