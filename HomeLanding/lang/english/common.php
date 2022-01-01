<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 14
 * @since       PHPBoost 5.0 - 2016 01 02
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
*/

####################################################
#                      English                     #
####################################################

$lang['homelanding.module.title']        = 'Home Page';
$lang['homelanding.config.module.title'] = 'Home Page module configuration';
$lang['homelanding.modules.position']    = 'Elements position';

// Module detection
$lang['homelanding.add.modules']             = 'Add some elements';
$lang['homelanding.add.modules.warning']     = '
    <p class="text-strong">:modules_list</p>
    <p>After following the instructions form  <a href="https://www.phpboost.com/wiki/ajouter-un-module-dans-homelanding">the documentation</a> on PHPBoost official website, click on <strong>Submit</strong>to add new modules on <strong>home page</strong>.</p>
';
$lang['homelanding.new.modules']             = 'New modules detected';
$lang['homelanding.no.new.module']           = '<p>New modules have been added to <strong>Home page</strong>.</p>';
$lang['homelanding.back.to.configuration']   = '<p>The module <a href="' . HomeLandingUrlBuilder::configuration()->rel() . '">configuration</a> is now available.</p>';
$lang['homelanding.new.modules.description'] = '
    <p>New <strong>Home page</strong> compatible modules have been installed and activated on website : </p>
    <p class="text-strong">:modules_list</p>
    <p>If they are not ready-to-use from the original module list of HomeLanding, They can be added by following <a href="https://www.phpboost.com/wiki/ajouter-un-module-dans-homelanding">the documentation</a> on PHPBoost official website.</p>
';

// Messages
$lang['homelanding.posted.in.topic']  = 'Posted in topic :';
$lang['homelanding.posted.in.module'] = 'Posted in module:';

// Modules labels
$lang['homelanding.see.module']          = 'see.module';
$lang['homelanding.module.carousel']     = 'Carousel';
$lang['homelanding.module.anchors_menu'] = 'Homepage Menu';
$lang['homelanding.module.edito']        = 'Edito';
$lang['homelanding.module.lastcoms']     = 'Comments';
    // Module position
$lang['homelanding.module.articles_category'] = 'Articles - category';
$lang['homelanding.module.download_category'] = 'Downloads - category';
$lang['homelanding.module.news_category']     = 'News - category';
$lang['homelanding.module.pinned_news']       = 'Pinned news';
$lang['homelanding.module.smallads_category'] = 'Small ads - category';
$lang['homelanding.module.web_category']      = 'Partners links - category';
    // Anchors tab
$lang['homelanding.category.articles_category'] = 'Articles';
$lang['homelanding.category.download_category'] = 'Downloads';
$lang['homelanding.category.news_category']     = 'News';
$lang['homelanding.category.smallads_category'] = 'Small ads';
$lang['homelanding.category.web_category']      = 'Partners links';

// Anchors menu
$lang['homelanding.anchors.title'] = 'Homepage menu';

// Carousel
$lang['homelanding.carousel.no.alt'] = 'Carousel item';

// Contact
$lang['homelanding.link.to.contact']                   = 'See contact page';
$lang['homelanding.send.email.success']                = 'Your email has been sent. ';
$lang['homelanding.send.email.error']                  = 'Your email could not be sent. ';
$lang['homelanding.send.email.acknowledgment']         = 'A confirmation message has been sent to you. ';
$lang['homelanding.send.email.tracking.number']        = 'Tracking number';
$lang['homelanding.send.email.acknowledgment.title']   = 'Confirmation';
$lang['homelanding.send.email.acknowledgment.correct'] = 'Your email has been correctly sent. ';
$lang['homelanding.send.another.email']                = 'Send another message. ';

// Configuration
$lang['homelanding.label.module.title']      = 'Module title';
$lang['homelanding.label.module.title.clue'] = 'Display the module title on the page, the breadcrumb and the page tab';

$lang['homelanding.hide.menu.left']           = 'Hide the left menu column';
$lang['homelanding.hide.menu.right']          = 'Hide the right menu column';
$lang['homelanding.hide.menu.top.central']    = 'Hide the top central menu';
$lang['homelanding.hide.menu.bottom.central'] = 'Hide the bottom central menu';
$lang['homelanding.hide.menu.top.footer']     = 'Hide the top footer menu';

$lang['homelanding.module.display']         = 'Module display';
$lang['homelanding.show.module']            = 'Display the module';
$lang['homelanding.show.full.module']    = 'Display full module';
$lang['homelanding.display.category']       = 'Display a category';
$lang['homelanding.items.number']           = 'Items number to display';
$lang['homelanding.characters.limit']       = 'Limit the number of characters';
$lang['homelanding.choose.category']        = 'Choose a category';
$lang['homelanding.display.sub.categories'] = 'Display subcategories content';
    // Pinned news
$lang['homelanding.pinned.news.title']      = 'Title on homepage';
$lang['homelanding.show.pinned.news']       = 'Display the pinned news';
    // Default
$lang['homelanding.title'] = 'Welcome';
$lang['homelanding.edito.description'] = 'Modify the <a class="offload" href="' . HomeLandingUrlBuilder::configuration()->relative() . '">module configuration</a> to setup the landing page';

// Configuration Anchors Menu
$lang['homelanding.config.anchors']       = 'Onepage menu display';
$lang['homelanding.display.anchors']      = 'Display the anchors menu';
$lang['homelanding.display.anchors.clue'] = 'Menu for fast navigation inside the homepage';

// Configuration Edito
$lang['homelanding.config.edito']  = 'Edito display';
$lang['homelanding.display.edito'] = 'Display the edito';
$lang['homelanding.edito.content'] = 'Content of the edito';

// Configuration Lastcoms
$lang['homelanding.config.lastcoms']  = 'Comments display';
$lang['homelanding.display.lastcoms'] = 'Display the last comments';
$lang['homelanding.lastcoms.limit']   = 'Number of comments to display';

// Configuration Carousel
$lang['homelanding.config.carousel']      = 'Slideshow display';
$lang['homelanding.display.carousel']     = 'Display the slideshow';
$lang['homelanding.carousel.content']     = 'Content of the slideshow';
$lang['homelanding.carousel.speed']       = 'Speed of picture switching (ms)';
$lang['homelanding.carousel.time']        = 'Display duration of an image (ms)';
$lang['homelanding.carousel.number']      = 'Displayed Pictures Number';
$lang['homelanding.carousel.number.clue'] = '0px < 1 image < 768px < 2 images < 1024px < choice';
$lang['homelanding.carousel.auto']        = 'Autoplay';
$lang['homelanding.carousel.hover']       = 'Pause on hover';
$lang['homelanding.carousel.enabled']     = 'Enabled';
$lang['homelanding.carousel.disabled']    = 'Disabled';
    // Content
$lang['homelanding.carousel.description'] = 'Description of the slide';
$lang['homelanding.carousel.link.url']    = 'Address of the link';
$lang['homelanding.carousel.picture.url'] = 'Address of the picture';
$lang['homelanding.carousel.upload']      = 'Open the file manager';
$lang['homelanding.carousel.add']         = 'Add a picture';
$lang['homelanding.carousel.del']         = 'Delete the slide';

// Modules configuration
$lang['homelanding.calendar.clue'] = 'Only displays upcoming events';
$lang['homelanding.flux.clue']     = 'Displays latest feed items from all feeds';
$lang['homelanding.web.clue']      = 'Displays only the partner links';
?>
