<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 4.0 - 2012 04 09
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      janus57 <janus57@janus57.fr>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

####################################################
#                     English                      #
####################################################

$lang['categories'] = $lang['items'] = [];

$lang['categories'][] = [
	'category.name'        => 'Test category',
	'category.description' => 'Demonstration news'
];

$lang['items'][] = [
	'item.title'   => 'How to begin with the News module',
	'item.content' => '
		<p>This brief news will give you some simple tips to take control of this module.</p>
		<ul class="formatter-ul">
			<li class="formatter-li">To configure your module, <a class="offload" href="' . ModulesUrlBuilder::configuration('news')->relative() . '">click here</a></li>
			<li class="formatter-li">To add categories: <a class="offload" href="' . CategoriesUrlBuilder::add(Category::ROOT_CATEGORY, 'news')->relative() . '">click here</a> (categories and subcategories are infinitely)</li>
			<li class="formatter-li">To add an item: <a class="offload" href="' . ItemsUrlBuilder::add(Category::ROOT_CATEGORY, 'news')->relative() . '">click here</a></li>
		</ul>
		<ul class="formatter-ul">
			<li class="formatter-li">To format your news, you can use bbcode language or the WYSIWYG editor (see this <a class="offload" href="https://www.phpboost.com/wiki/bbcode">article</a>)<br /></li>
		</ul>
		<p>For more information, please see the module documentation on the site <a class="offload" href="https://www.phpboost.com">PHPBoost</a>.</p>
		<br />
		<br />
		Enjoy using this module.',
	'item.summary' => ''
];
?>
