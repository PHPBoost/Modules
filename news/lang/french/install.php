<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 4.0 - 2013 04 09
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Arnaud GENET <elenwii@phpboost.com>
 * @author      mipel <mipel@phpboost.com>
 * @author      janus57 <janus57@janus57.fr>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

####################################################
#                     French                       #
####################################################

$lang['categories'] = $lang['items'] = [];

$lang['categories'][] = [
	'category.name'        => 'Catégorie de test',
	'category.description' => 'Actualités de démonstration'
];

$lang['items'][] = [
	'item.title'   => 'Débuter avec le module Actualités',
	'item.content' => '
		<p>Cette brève actualité va vous donner quelques conseils simples pour prendre en main ce module.</p>
		<ul class="formatter-ul">
			<li class="formatter-li">Pour configurer votre module, <a class="offload" href="' . ModulesUrlBuilder::configuration('news')->relative() . '">cliquez ici</a></li>
			<li class="formatter-li">Pour ajouter des catégories : <a class="offload" href="' . CategoriesUrlBuilder::add(Category::ROOT_CATEGORY, 'news')->relative() . '">cliquez ici</a> (les catégories et sous catégories sont à l\'infini)</li>
			<li class="formatter-li">Pour ajouter une actualité : <a class="offload" href="' . ItemsUrlBuilder::add(Category::ROOT_CATEGORY, 'news')->relative() . '">cliquez ici</a></li>
		</ul>
		<ul class="formatter-ul">
			<li class="formatter-li">Pour mettre en page vos actualités, vous pouvez utiliser le langage bbcode ou l\'éditeur WYSIWYG (cf cet <a class="offload" href="https://www.phpboost.com/wiki/bbcode">article</a>)<br /></li>
		</ul>
		<p>Pour en savoir plus, n\'hésitez pas à consulter la documentation du module sur le site de <a class="offload" href="https://www.phpboost.com/wiki/news">PHPBoost</a>.</p>
		<br />
		<br />
		Bonne utilisation de ce module.',
	'item.summary' => ''
];
?>
