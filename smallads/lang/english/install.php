<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 03 15
 * @since       PHPBoost 5.1 - 2018 03 15
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

#####################################################
#                      English                      #
#####################################################

$lang['default.category.name'] = 'Test category';
$lang['default.category.description'] = 'Demonstration announces';
$lang['default.smallad.title'] = 'Smallads for PHPBoost CMS';
$lang['default.smallad.summary'] = '';
$lang['default.smallad.content'] = 'This first ad will give you some tips for taking control of this module.
<br /><br />
<ul class="formatter-ul">
	<li class="formatter-li"> To configure or customize your module, go into the <a class="offload" href="' . SmalladsUrlBuilder::categories_configuration()->relative() . '">categories configuration</a></li>
	<li class="formatter-li"> To configure or customize the ads and filters, go into the <a class="offload" href="' . SmalladsUrlBuilder::items_configuration()->relative() . '">ads configuration</a></li>
	<li class="formatter-li"> To configure or customize the terms and conditions page, go into the <a class="offload" href="' . SmalladsUrlBuilder::usage_terms_configuration()->relative() . '">module T&C administration</a></li>
	<li class="formatter-li"> To create categories, <a class="offload" href="' . CategoriesUrlBuilder::add('smallads')->relative() . '">click here</a></li>
	<li class="formatter-li"> To create announces, <a class="offload" href="' . SmalladsUrlBuilder::add_item()->relative() . '">click here</a></li>
</ul>
<ul class="formatter-ul">
<li class="formatter-li">To format your articles, you can use bbcode language or the WYSIWYG editor (see this <a class="offload" href="https://www.phpboost.com/wiki/bbcode">article</a>)<br />
</li>
</ul>
<br /><br />
For more information, please see the module documentation on the site <a class="offload" href="https://www.phpboost.com">PHPBoost</a>.<br />
<br />
<br />
Good use of this module.
';
$lang['default.smallad.type'] = '1st Type';
$lang['config.usage.terms.conditions'] = 'Usage terms condition to define';

?>
