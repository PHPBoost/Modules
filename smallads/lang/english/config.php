<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 11 12
 * @since       PHPBoost 5.1 - 2018 03 15
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

#####################################################
#                      English                      #
#####################################################

$lang['root_category_description'] = 'Welcome to the smallads section of the website!
<br /><br />
One category and one announce were created to show you how this module works. Here are some tips to get started on this module.
<br /><br />
<ul class="formatter-ul">
<li class="formatter-li"> To configure or customize your module, go into the <a href="' . SmalladsUrlBuilder::categories_configuration()->relative() . '">categories configuration</a></li>
<li class="formatter-li"> To configure or customize the ads and filters, go into the <a href="' . SmalladsUrlBuilder::items_configuration()->relative() . '">ads configuration</a></li>
<li class="formatter-li"> To configure or customize the terms and conditions page, go into the <a href="' . SmalladsUrlBuilder::usage_terms_configuration()->relative() . '">module T&C administration</a></li>
<li class="formatter-li"> To create categories, <a href="' . CategoriesUrlBuilder::add_category('smallads')->relative() . '">clic here</a></li>
<li class="formatter-li"> To create announces, <a href="' . SmalladsUrlBuilder::add_item()->relative() . '">clic here</a></li>
</ul>
<br />To learn more, please read the documentation for the module at <a href="https://www.phpboost.com">PHPBoost</a> website.';
?>
