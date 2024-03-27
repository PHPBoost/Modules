<?php
/**
 * @copyright   &copy; 2005-2024 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2023 02 02
 * @since       PHPBoost 6.0 - 2021 10 30
*/

####################################################
#					 English					   #
####################################################

$lang['flux.module.title']     = 'RSS feeds';
$lang['flux.last.feeds.title'] = 'The :feeds_number most recent feed items';
$lang['flux.no.last.feeds']    = 'No Rss feeds has been initilized.';
$lang['flux.words.not.read']   = 'Words remaining to be read';

$lang['item']  = 'feed';

$lang['flux.member.items']  = 'Feeds published by';
$lang['flux.my.items']      = 'My feeds';
$lang['flux.pending.items'] = 'Pending feeds';
$lang['flux.items.number']  = 'Feeds number';
$lang['flux.filter.items']  = 'Filter feeds';

$lang['flux.add']        = 'Add a feed';
$lang['flux.edit']       = 'Feed edition';
$lang['flux.management'] = 'Feeds management';

$lang['flux.website.infos']         = 'Infos about the website';
$lang['flux.website.xml']           = 'xml file url';
$lang['flux.empty.xml.file']        = 'The xml file is created but no feeds have been found. Please check the Rss feed url entered';
$lang['flux.rss.init']              = 'The site feed has not been initialized.';
$lang['flux.rss.init.admin']        = 'The display of new feed items from the site feeds is updated by clicking on the button.';
$lang['flux.rss.init.contribution'] = 'The display of new feed items is available when the contribution is validated.';
$lang['flux.wrong.rss.init']        = 'The file is xml but not a rss file.Check the rss address of the website';
$lang['flux.check.updates']         = 'Check new site feed topics.';
$lang['flux.update']                = 'Update';

// Configuration
$lang['flux.module.name']               = 'Module title';
$lang['flux.rss.number']                = 'Feed items number per site';
$lang['flux.display.last.feeds']        = 'Display most recent feed items on module homepage';
$lang['flux.last.feeds.number']         = 'Number of most recent feed items to display on module homepage';
$lang['flux.characters.number.to.cut']  = 'Characters number to cut the feed item';
$lang['flux.update.all']                = 'Update cache';
$lang['flux.success.update']            = 'All flux have been successfully updated';
$lang['flux.update.clue']               = 'This action update all <strong>declared AND initialized</strong> flux and delete all unused files from the cache.';
$lang['flux.root.category.description'] = '
    <p>Welcome to the Rss Feed section of the website!</p>
    <p>A category and a feed were created to show you how this module works. Here are some tips to get started on this module.</p>
    <ul class="formatter-ul">
        <li class="formatter-li"> To configure or customize the module homepage your module, go into the <a class="offload" href="' . Url::to_rel(FluxUrlBuilder::configuration('flux')->relative()) . '">module administration</a></li>
        <li class="formatter-li"> To create categories, <a class="offload" href="' . Url::to_rel(CategoriesUrlBuilder::add()->relative(Category::ROOT_CATEGORY, 'flux')) . '">clic here</a></li>
        <li class="formatter-li"> To create feeds, <a class="offload" href="' . Url::to_rel(FluxUrlBuilder::add()->relative(Category::ROOT_CATEGORY, 'flux')) . '">clic here</a></li>
    </ul>
    <p>To learn more, feel free to consult the documentation for the module on <a class="offload" href="https://www.phpboost.com">PHPBoost</a>.</p>
';

// S.E.O.
$lang['flux.seo.description.member'] = 'All feeds published by :author.';
$lang['flux.seo.description.pending'] = 'All pending feeds.';

// Messages
$lang['flux.message.success.add']    = 'The feed <b>:name</b> has been added';
$lang['flux.message.success.edit']   = 'The feed <b>:name</b> has been modified';
$lang['flux.message.success.delete'] = 'The feed <b>:name</b> has been deleted';
?>
