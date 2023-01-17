<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 10 17
 * @since       PHPBoost 6.0 - 2022 10 17
 */

####################################################
#                       English                    #
####################################################

// Module titles
$lang['video.module.title'] = 'Videos';

// TreeLinks
$lang['item']  = 'video';
$lang['items'] = 'videos';

// Titles
$lang['video.add.item']         = 'Add new video';
$lang['video.edit.item']        = 'Edit video';
$lang['video.my.items']         = 'My videos';
$lang['video.member.items']     = 'Videos published by';
$lang['video.pending.items']    = 'Pending videos';
$lang['video.filter.items']     = 'Filter videos';
$lang['video.items.management'] = 'Videos management';

$lang['video.width']  = 'Width of the video';
$lang['video.height'] = 'Height of the video';

// Mini module sorting
$lang['video.last.items'] = 'Last videos';
$lang['video.last.items'] = 'Popular videos';
$lang['video.ranking']    = 'Ranking';
$lang['video.watch']      = 'Watch video';

// Configuration
$lang['video.config.display.subcategories'] = 'Display subcategories';
$lang['video.config.mini.module']     = 'Mini module';
$lang['video.config.sort.type.clue']  = 'Decreasing direction';
$lang['video.config.items.number']    = 'Maximum number of items displayed';
$lang['video.trusted.hosts']          = 'Trusted hosts';
$lang['video.platform']               = 'Platform';
$lang['video.domain']                 = 'Domain';
$lang['video.host.player']            = 'Plateform player';
$lang['video.authorized.extensions']  = 'Authorized video extensions list';
$lang['video.authorized.extensions.clue'] = 'Precede the extension with the words "video/"';
$lang['video.authorized.url']         = 'List of authorized hosting platforms';

// SEO
$lang['video.seo.description.root']    = 'All :site\'s videos.';
$lang['video.seo.description.tag']     = 'All videos on :subject.';
$lang['video.seo.description.pending'] = 'All pending videos.';
$lang['video.seo.description.member']  = 'All :author\'s videos.';

// Messages helper
$lang['video.message.success.add']    = 'The video <b>:title</b> has been added';
$lang['video.message.success.edit']   = 'The video <b>:title</b> has been modified';
$lang['video.message.success.delete'] = 'The video <b>:title</b> has been deleted';

// Error message
$lang['e_mime_disable_video'] = 'The type of video you want to submit is disabled!';
$lang['e_link_invalid_video'] = 'Please enter a valid link for your video!';
$lang['e_unexist_video']      = 'The requested video does not exist!';
$lang['e_bad_url_peertube']   = 'The entered url is not valid. It does not correspond to the url of one of the PeerTube instances entered in the module configuration.';
$lang['e_bad_url_odysee'] = '
    The entered Odysee url is not valid. <br />
    In the <span class="pinned question">Share</span> tab under the video, choose one of the two following urls:
    <ul>
        <li><span class="pinned question">Embed this content</span> / url provided in <span class="pinned question">Embedded</span></li>
        <li><span class="pinned question">Links</span> / url provided in <span class="pinned question">Download link</span></li>
    </ul>
';
?>
