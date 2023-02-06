<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2023 02 06
 * @since       PHPBoost 6.0 - 2021 08 22
*/

####################################################
#						English						#
####################################################

$lang['spots.module.title'] = 'Locations';

$lang['item']  = 'location';

$lang['spots.member.items']  = 'Locations published by';
$lang['spots.my.items']      = 'My locations';
$lang['spots.pending.items'] = 'Pending locations';
$lang['spots.items.number']  = 'Locations number';
$lang['spots.filter.items']  = 'Filter locations';

$lang['spots.add']        = 'Add a location';
$lang['spots.edit']       = 'Location edition';
$lang['spots.management'] = 'Locations management';

$lang['spots.address']           = 'Address';
$lang['spots.visit.website']     = 'Visit the location website';
$lang['spots.no.website']        = 'No website listed';
$lang['spots.link.infos']        = 'Informations about the location';
$lang['spots.contact']           = 'Contact';
$lang['spots.location']          = 'GPS coordinates of the location ';
$lang['spots.location.lat']      = 'Latitude';
$lang['spots.location.lng']      = 'Longitude';
$lang['spots.travel.type']       = 'Travel type';
$lang['spots.travel.type.car']   = 'driving';
$lang['spots.travel.type.walk']  = 'walking';
$lang['spots.travel.type.bike']  = 'bicycling';
$lang['spots.travel.type.train'] = 'transit';
$lang['spots.osm.french']        = 'French';
$lang['spots.osm.satellite']     = 'Satellite';
$lang['spots.osm.topo']          = 'Topography';
$lang['spots.google.hybrid']     = 'Google hybrid';
$lang['spots.google.sat']        = 'Google satellite';
$lang['spots.google.terrain']    = 'Google terrain';
$lang['spots.google.roadmap']    = 'Google routes';

$lang['spots.change.orign.address'] = 'Calculate a new itinerary';
$lang['spots.route.infos']       = '
    The route is calculated from the default address of the website. <br />
    To set a new route, drag the starting marker or fill the followinf field with a new address.
';
$lang['spots.new.location']      = 'New starting location';
$lang['spots.new.location.clue'] = 'Select from the autocomplete list to make it valid';
$lang['spots.waze.description']  = '
    You can open this location in the search space of the Waze website.<br />
    On mobile device, you will be redirected first on Waze website, then open the application from the website to get the route from your position.
';
$lang['spots.send.to.waze']      = 'Send to Waze';

// Form
$lang['spots.location.clue']      = 'Fill in the address field and validate it from the autocomplete list, and/or drag the marker.<br /> Only the marker is necessary. ';
$lang['spots.display.route']      = 'Display the route';
$lang['spots.display.route.clue'] = 'From default location defines in GoogleMaps.<br /> Only usefull if no sea/ocean betwenn the two locations.';
    // Categories
$lang['spots.inner.icon']             = 'Icon';
$lang['spots.inner.icon.clue']        = '
    <a href="https://fontawesome.com/v5.15/icons?d=gallery&p=2" target="_blank" rel="noopener noreferrer">Font Awesome icons list</a>
    <span class="d-block">
        If no icone is chosen, the default icon from the configuration will be set for the category.
    </span>
';
$lang['spots.inner.icon.placeholder'] = 'fa fa-...';
$lang['spots.category.address'] = 'Start address';
$lang['spots.category.address.clue'] = '
    For route calculation. <br />
    If left empty, the address of the GoogleMaps module configuration replaces it.
';

// Configuration
$lang['spots.module.name']               = 'Module title';
$lang['spots.default.color']             = 'Default color';
$lang['spots.default.inner.icon']        = 'Default icon';
$lang['spots.default.color.clue']        = 'Defines the color of the root category and initialise the color when adding a new category.';
$lang['spots.default.inner.icon.clue']   = 'Defines the icon of the root category and the icon of a category if none is choosen.';
$lang['spots.root.category.description'] = '
    Welcome to the spots section of the website!
    <br /><br />
    A category and a location were created to show you how this module works. Here are some tips to get started on this module.
    <br /><br />
    <ul class="formatter-ul">
        <li class="formatter-li"> To configure or customize the module homepage your module, go into the <a class="offload" href="' . Url::to_rel(SpotsUrlBuilder::configuration('spots')) . '">module administration</a></li>
        <li class="formatter-li"> To create categories, <a class="offload" href="' . Url::to_rel(CategoriesUrlBuilder::add(Category::ROOT_CATEGORY, 'spots')) . '">clic here</a></li>
        <li class="formatter-li"> To create locations, <a class="offload" href="' . Url::to_rel(SpotsUrlBuilder::add(Category::ROOT_CATEGORY, 'spots')) . '">clic here</a></li>
    </ul>
    <br />To learn more, don \'t hesitate to consult the documentation for the module on <a class="offload" href="https://www.phpboost.com">PHPBoost</a>.
';

// S.E.O.
$lang['spots.seo.description.member'] = 'All locations published by :author.';
$lang['spots.seo.description.pending'] = 'All pending locations.';
$lang['spots.seo.description.root']    = 'All locations on :site .';

// Messages
$lang['spots.message.success.add']    = 'The location <b>:name</b> has been added';
$lang['spots.message.success.edit']   = 'The location <b>:name</b> has been modified';
$lang['spots.message.success.delete'] = 'The location <b>:name</b> has been deleted';

// Social Network
$lang['spots.social.network']        = 'Social networks';
$lang['spots.labels.facebook']       = 'Facebook <i class="fab fa-fw fa-facebook" aria-hidden="true"></i>';
$lang['spots.placeholder.facebook']  = 'https://www.facebook.com/...';
$lang['spots.labels.twitter']        = 'Twitter <i class="fab fa-fw fa-twitter" aria-hidden="true"></i>';
$lang['spots.placeholder.twitter']   = 'https://www.twitter.com/...';
$lang['spots.labels.instagram']      = 'Instagram <i class="fab fa-fw fa-instagram" aria-hidden="true"></i>';
$lang['spots.placeholder.instagram'] = 'https://www.instagram.com/...';
$lang['spots.labels.youtube']        = 'Youtube <i class="fab fa-fw fa-youtube" aria-hidden="true"></i>';
$lang['spots.placeholder.youtube']   = 'https://www.youtube.com/...';

// Warnings
$lang['spots.no.gmap']            = 'You must install and activate the GoogleMaps module and configure it (key + default location).';
$lang['spots.no.default.address'] = 'The default location has not been declared in the GoogleMaps module configuration.';
$lang['spots.no.gps']             = 'GPS coordinates of the location have not been entered.';
?>
