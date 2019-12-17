<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 11 12
 * @since       PHPBoost 5.1 - 2018 03 15
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
*/

#####################################################
#                      French                       #
#####################################################

$lang['root_category_description'] = 'Bienvenue dans le module Petites Annonces du site !
<br /><br />
Une catégorie et une annonce ont été créées pour vous montrer comment fonctionne ce module. Voici quelques conseils pour bien débuter sur ce module.
<br /><br />
<ul class="formatter-ul">
<li class="formatter-li"> Pour configurer ou personnaliser votre module, rendez-vous dans la <a href="' . SmalladsUrlBuilder::categories_configuration()->relative() . '">configuration des catégories</a></li>
<li class="formatter-li"> Pour configurer ou personnaliser les annonces et filtres d\'affichage, rendez-vous dans la <a href="' . SmalladsUrlBuilder::items_configuration()->relative() . '">configuration des annonces</a></li>
<li class="formatter-li"> Pour configurer ou personnaliser les conditions générales d\'utilisation, rendez-vous dans la <a href="' . SmalladsUrlBuilder::usage_terms_configuration()->relative() . '">configuration des CGU</a></li>
<li class="formatter-li"> Pour créer des catégories, <a href="' . CategoriesUrlBuilder::add_category('smallads')->relative() . '">cliquez ici</a> </li>
<li class="formatter-li"> Pour ajouter des annonces, <a href="' . SmalladsUrlBuilder::add_item()->relative() . '">cliquez ici</a></li>
</ul>
<br />Pour en savoir plus, n\'hésitez pas à consulter la documentation du module sur le site de <a href="https://www.phpboost.com">PHPBoost</a>.';
?>
