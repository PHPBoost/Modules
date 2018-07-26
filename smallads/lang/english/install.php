<?php
/*##################################################
 *                            install.php
 *                            -------------------
 *   begin                : March 15, 2018
 *   copyright            : (C) 2018 Sebastien LARTIGUE
 *   email                : babsolune@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

/**
 * @author Sebastien LARTIGUE <babsolune@phpboost.com>
 */

#####################################################
#                      English			    #
####################################################

$lang['default.category.name'] = 'Test category';
$lang['default.category.description'] = 'Demonstration announces';
$lang['default.smallad.title'] = 'Smallads for PHPBoost ' . GeneralConfig::load()->get_phpboost_major_version();
$lang['default.smallad.description'] = '';
$lang['default.smallad.contents'] = 'This first ad will give you some simple tips to take control of this module.
<br /><br />
<ul class="formatter-ul">
	<li class="formatter-li"> To configure or customize your module, go into the <a href="' . SmalladsUrlBuilder::categories_configuration()->relative() . '">categories configuration</a></li>
	<li class="formatter-li"> To configure or customize the ads and filters, go into the <a href="' . SmalladsUrlBuilder::items_configuration()->relative() . '">ads configuration</a></li>
	<li class="formatter-li"> To configure or customize the terms and conditions page, go into the <a href="' . SmalladsUrlBuilder::usage_terms_configuration()->relative() . '">module T&C administration</a></li>
	<li class="formatter-li"> To create categories, <a href="' . SmalladsUrlBuilder::add_category()->relative() . '">clic here</a></li>
	<li class="formatter-li"> To create announces, <a href="' . SmalladsUrlBuilder::add_item()->relative() . '">clic here</a></li>
</ul>
<ul class="formatter-ul">
<li class="formatter-li">To format your articles, you can use bbcode language or the WYSIWYG editor (see this <a href="http://www.phpboost.com/wiki/bbcode">article</a>)<br />
</li>
</ul>
<br /><br />
For more information, please see the module documentation on the site <a href="http://www.phpboost.com">PHPBoost</a>.<br />
<br />
<br />
Good use of this module.
';
$lang['default.smallad.type'] = '1st Type';
$lang['config.usage.terms.conditions'] = 'Usage terms condition to define';

?>
