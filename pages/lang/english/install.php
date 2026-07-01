<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 5.2 - 2020 06 15
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
*/

####################################################
#                       English                    #
####################################################

$lang['categories'] = $lang['items'] = [];

$lang['categories'][] = [
	'category.name'        => 'Test',
	'category.description' => 'Test category'
];

$lang['items'][] = [
	'item.title'   => 'Legal mentions',
	'item.content' => '
        <h2 class="formatter-title">Site manager</h2>
        <br />
        Release Manager: <a class="offload" href="/#">[ManagerName]</a>
        <br />
        Creation and development: ' . GeneralConfig::load()->get_site_name() . '
        <br />
        For any request, please contact:
        <br />
        email: [LinkToEmail/Form]
        <br/><br/>
        <h2 class="formatter-title">Property right</h2>
        <br />
        All text, photos and illustrations on this site are the property of ' . GeneralConfig::load()->get_site_name(). ' and its contractual partners.
        Any representation, reproduction in whole or in part made without the consent of the owner is illegal.
        If you wish to reproduce photos or documents contained in this site, solely for non-profit purposes, you must obtain prior written authorization from before any distribution ' . GeneralConfig::load()->get_site_name() . '.
        For this: send a written request by email to ' . GeneralConfig::load()->get_site_name() . ' specifying the exact content of what you wish to reproduce.
        After approval, you will be asked to cite the sources precisely. If you are a journalist and would like to obtain a high-definition photo for a press release, send us a request through the same channel: [LinkToEmail/Form]
        <br/><br/>
        <h2 class="formatter-title">Site Hosting</h2>
        <br />
        [HostMailingAddress]
        <br />
        <h2 class="formatter-title">Third Party Apps</h2>
        <br />
        PHP and JavaScript scripts are integrated into this site in compliance with the rights granted by their authors. The mentions provided for in these are visible in the page source codes. <br />
        <br />
        <h2 class="formatter-title">Personal data protection</h2>
        <br />
        The site does not collect any personal data
        <br/><br/>
        <h2 class="formatter-title">Cookies</h2>
        <br />
        When browsing certain parts of the site, cookies may be imported onto your computer.
        These do not allow you to be identified, but they record information relating to navigation that the program can read during your subsequent visits.
        You can prevent the storage of cookies by configuring your internet browser.
        <br />
        <h2 class="formatter-title">Links to this site</h2>
        <br />
        Links to this site are welcome under the following conditions:
        <br />
        <ul class="formatter-ul">
            <li class="formatter-li">Respect for reciprocity.</li>
            <li class="formatter-li">Link on a page of your site that is indexed by search engines.</li>
            <li class="formatter-li">Full page opening <a class="offload" href="/">' . GeneralConfig::load()->get_site_name() . '</a> in a browser, without any site-specific frames or endorsements. </li>
        </ul>
        <br/><br/>
        <h2 class="formatter-title">Credits</h2><br />
        The content included in this site remains the intellectual property of their respective authors:
        <br />
        <ul class="formatter-ul">
            <li class="formatter-li">Photo credits: ' . GeneralConfig::load()->get_site_name() . '</li>
            <li class="formatter-li">Illustration credits: ' . GeneralConfig::load()->get_site_name() . '</li>
            <li class="formatter-li">Text credits: ' . GeneralConfig::load()->get_site_name() . '</li>
            <li class="formatter-li">For any request to reproduce content: [LinkToEmail/Form]</li>
        </ul>',
	'item.summary' => ''
];
?>
