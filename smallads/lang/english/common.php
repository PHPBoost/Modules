<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 10 19
 * @since       PHPBoost 5.0 - 2016 02 02
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
 * @contributor Mipel <mipel@phpboost.com>
*/

#####################################################
#                      English                      #
#####################################################

$lang['smallads.module.title'] = 'Smallads';

// Tree links automatic vars
$lang['item']  = 'Ad';
$lang['items'] = 'Ads';

// Labels
$lang['smallads.my.items']       = 'My ads';
$lang['smallads.archived.items'] = 'Archived ads';
$lang['smallads.pending.items']  = 'Pending ads';
$lang['smallads.member.items']   = 'Ads published by';
$lang['smallads.filter.items']   = 'Filter ads';

$lang['smallads.items.management'] = 'Smallads management';
$lang['smallads.add.item']         = 'Add an ad';
$lang['smallads.edit.item']        = 'Item edition';
$lang['smallads.feed.name']        = 'Last ads';

$lang['smallads.category.list']   = 'Categories';
$lang['smallads.category.select'] = 'Choose a category';
$lang['smallads.category.all']    = 'All categories';
$lang['smallads.select.category'] = 'Select a category';

$lang['smallads.ad.type']  = 'Type';
$lang['smallads.category'] = 'Category';

$lang['smallads.publication.date'] = 'Published for';
$lang['smallads.contact']          = 'Contact the author';
$lang['smallads.contact.email']    = 'by email';
$lang['smallads.contact.pm']       = 'by private message';
$lang['smallads.contact.phone']    = 'by phone';

$lang['smallads.item.is.archived'] = 'This item has overflown its publication date, It\'s not displayed to other.';

// Categories configuration
$lang['smallads.categories.config'] = 'Categories configuration';
$lang['smallads.cats.icon.display'] = 'Categories icon display';
$lang['smallads.default.content']   = 'Smallads default content';
    // Default
$lang['smallads.default.type'] = '1st Type';
$lang['smallads.root.category.description'] = '
    Welcome to the smallads section of the website!
    <br /><br />
    One category and one announce were created to show you how this module works. Here are some tips to get started on this module.
    <br /><br />
    <ul class="formatter-ul">
        <li class="formatter-li"> To configure or customize your module, go into the <a class="offload" href="' . SmalladsUrlBuilder::categories_configuration()->relative() . '">categories configuration</a></li>
        <li class="formatter-li"> To configure or customize the ads and filters, go into the <a class="offload" href="' . SmalladsUrlBuilder::items_configuration()->relative() . '">ads configuration</a></li>
        <li class="formatter-li"> To configure or customize the terms and conditions page, go into the <a href="' . SmalladsUrlBuilder::usage_terms_configuration()->relative() . '">module T&C administration</a></li>
        <li class="formatter-li"> To create categories, <a class="offload" href="' . CategoriesUrlBuilder::add(Category::ROOT_CATEGORY, 'smallads')->relative() . '">click here</a></li>
        <li class="formatter-li"> To create announces, <a class="offload" href="' . SmalladsUrlBuilder::add_item()->relative() . '">click here</a></li>
    </ul>
    <br />To learn more, please read the documentation for the module at <a class="offload" href="https://www.phpboost.com">PHPBoost</a> website.
';

// Items configuration
$lang['smallads.items.config']                = 'Ads configuration';
$lang['smallads.currency']                    = 'Currency';
$lang['smallads.type.add']                    = 'Add types of ad';
$lang['smallads.type.placeholder']            = 'Sale, purchase, leasing ...';
$lang['smallads.brand.add']                   = 'Add brands';
$lang['smallads.brand.placeholder']           = 'Brand\'s name';
$lang['smallads.enable.location']             = 'Activate location';
$lang['smallads.limit.max.weeks']             = 'Limit the number of weeks of posting';
$lang['smallads.max.weeks']                   = 'Default number of weeks of posting';
$lang['smallads.delay.before.archiving']      = 'Display delay before archiving';
$lang['smallads.delay.before.archiving.clue'] = 'when the "completed" checkbox is enabled (in days).';
$lang['smallads.contact.to.visitors']         = 'Allow visitors to contact ad authors';
$lang['smallads.contact.to.visitors.clue']    = 'If not checked, only connected members can contact ad authors.';
$lang['smallads.enable.email.contact']        = 'Enable the author\'s email';
$lang['smallads.enable.pm.contact']           = 'Enable the author\'s pm';
$lang['smallads.enable.phone.contact']        = 'Enable the author\'s phone number';
$lang['smallads.enable.suggestions']          = 'Enable ad suggestions';
$lang['smallads.suggestions.number']          = 'Number of ads to display';
$lang['smallads.enable.related.links']        = 'Enable related links to ads';
$lang['smallads.related.links.clue']          = 'Previous link, next link.';

// Mini module configuration
$lang['smallads.mini.config']          = 'Mini menu configuration';
$lang['smallads.mini.items.number']    = 'Ads number to display mini menu';
$lang['smallads.mini.speed.clue']      = 'in milisecondes.';
$lang['smallads.mini.animation.speed'] = 'Speed scrolling';
$lang['smallads.mini.autoplay']        = 'Enable autoplay';
$lang['smallads.mini.autoplay.speed']  = 'Time between 2 scrolls';
$lang['smallads.mini.autoplay.hover']  = 'Enable pause on slideshow hover';

// Usage Terms Conditions
$lang['smallads.usage.terms.management'] = 'Usage terms management';
$lang['smallads.usage.terms']            = 'Usage terms';
$lang['smallads.display.usage.terms']    = 'Display the usage terms';
$lang['smallads.usage.terms.clue']       = 'Usage terms description.';

// Form
$lang['smallads.form.warning']                         = 'Validation is available on each tab. Be sure to fill all wanted fields on every page before validate the item.';
$lang['smallads.form.add']                             = 'Add an ad';
$lang['smallads.form.edit']                            = 'Modify an ad';
$lang['smallads.form.summary']                         = 'Description (maximum :number characters)';
$lang['smallads.form.enable.summary']                  = 'Enable ad summary';
$lang['smallads.form.enable.summary.clue']             = 'or let PHPBoost cut the content at :number characters';
$lang['smallads.form.price']                           = 'Price';
$lang['smallads.form.price.clue']                      = 'Leave to 0 to not display the price.<br />Use a comma for decimals.';
$lang['smallads.form.thumbnail']                       = 'Ad\'s thumbnail';
$lang['smallads.form.thumbnail.clue']                  = 'Follows the ad on the entire website.';
$lang['smallads.form.carousel']                        = 'Add a picture carousel';
$lang['smallads.form.image.description']               = 'Description';
$lang['smallads.form.image.url']                       = 'Picture address';
$lang['smallads.form.contact']                         = 'Contact details';
$lang['smallads.form.max.weeks']                       = 'Number of weeks of display';
$lang['smallads.form.max.weeks.clue']                  = 'After this period, the ad will be unpublished and archived ';
$lang['smallads.form.display.author.pm']               = 'Display the link to pm';
$lang['smallads.form.display.author.email']            = 'Display the link to email';
$lang['smallads.form.author.email.customization']      = 'Customize email';
$lang['smallads.form.author.email.customization.clue'] = 'if you want to be contacted on another email than your account one.';
$lang['smallads.form.custom.author.email']             = 'Contact email';
$lang['smallads.form.display.author.phone']            = 'Display the phone number';
$lang['smallads.form.author.phone']                    = 'Phone number';
$lang['smallads.form.author.name.customization']       = 'Customize author name';
$lang['smallads.form.custom.author.name']              = 'Custom author name';
$lang['smallads.form.completed.ad']                    = 'Completed ad';
$lang['smallads.form.completed']                       = 'Declare this ad completed';
$lang['smallads.form.completed.warning']               = 'The ad is archived :delay day(s) after.<br /><span style="color:var(--error-tone)">This action is irreversible.</span>';
$lang['smallads.form.unarchive']                       = 'Unarchive the ad';
$lang['smallads.form.unarchive.clue']                  = '<span class="error">Modify the publication date to restart the count before the next archiving.</span>';

$lang['smallads.form.smallad.type']                = 'Type of ad';
$lang['smallads.form.smallads.types']              = 'Types of ads';
$lang['smallads.form.member.edition']              = 'Modification by author';
$lang['smallads.form.member.contribution.explain'] = 'Your contribution will be sent to pending ads, follow the approval processing in your contribution panel. Modification is possible before and after approbation. You can justify your contribution in the next field.';
$lang['smallads.form.member.edition.explain']      = 'You are about to modify your ad. It will be sent to pending ads to be processed and a new alert will be sent to administrators';
$lang['smallads.form.member.edition.summary']      = 'Further summary of modification';
$lang['smallads.form.member.edition.summary.clue'] = 'Explain what you have modify for a better approval processing.';

// S.E.O.
$lang['smallads.seo.description.root']        = 'All :site\'s ads.';
$lang['smallads.seo.description.archived']    = 'All :site\'s archived ads.';
$lang['smallads.seo.description.tag']         = 'All :subject\'s ads.';
$lang['smallads.seo.description.pending']     = 'All pending ads.';
$lang['smallads.seo.description.member']      = 'All :author\'s ads.';
$lang['smallads.seo.description.usage.terms'] = ':site\'s smallads usage terms.';

// Messages helper
$lang['smallads.message.success.add']    = 'The ad <b>:title</b> has been added';
$lang['smallads.message.success.edit']   = 'The ad <b>:title</b> has been modified';
$lang['smallads.message.success.delete'] = 'The ad <b>:title</b> has been deleted';
$lang['smallads.no.type']                = '<div class="warning">You must declare some ad types in the <a class="offload" href="'. PATH_TO_ROOT . SmalladsUrlBuilder::items_configuration()->relative() . '">ads configuration</a></div>';
$lang['smallads.all.types.filters']      = 'All';

// Contact
$lang['smallads.tel.modal']             = 'You must be connected to see the phone number';
$lang['smallads.email.modal']           = 'You must be connected to contact the author of this ad';
$lang['smallads.message.success.email'] = 'Your message have been sent';
$lang['smallads.message.error.email']   = 'An error occurred while sending your message';
$lang['smallads.contact.author']        = 'Contact the ad author';
$lang['smallads.item.interest']         = 'You are interested by the ad :';
$lang['smallads.sender.name']           = 'Your name :';
$lang['smallads.sender.email']          = 'Your email :';
$lang['smallads.sender.message']        = 'Your message :';

// Mini module
$lang['smallads.mini.last.items']    = 'Last ads';
$lang['smallads.mini.no.item']       = 'No ad available';
$lang['smallads.mini.there.is']      = 'There is';
$lang['smallads.mini.there.are']     = 'There are';
$lang['smallads.mini.one.item']      = 'ad on the website';
$lang['smallads.mini.several.items'] = 'ads on the website';

// Accessibility
$lang['smallads.open.modal']  = 'Opening in a new window';
$lang['smallads.close.modal'] = 'Close window';
?>
