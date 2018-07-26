<?php
/*##################################################
 *                               config.php
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

 ####################################################
 #						French						#
 ####################################################

$lang['root_category_description'] = 'Bienvenue dans le module Petites Annonces du site !
<br /><br />
Une catégorie et une annonce ont été créés pour vous montrer comment fonctionne ce module. Voici quelques conseils pour bien débuter sur ce module.
<br /><br />
<ul class="formatter-ul">
<li class="formatter-li"> Pour configurer ou personnaliser votre module, rendez vous dans la <a href="' . SmalladsUrlBuilder::categories_configuration()->relative() . '">configuration des catégories</a></li>
<li class="formatter-li"> Pour configurer ou personnaliser les annonces et filtres d\'affichage, rendez vous dans la <a href="' . SmalladsUrlBuilder::items_configuration()->relative() . '">configuration des annonces</a></li>
<li class="formatter-li"> Pour configurer ou personnaliser les conditions générales d\'utilisation, rendez vous dans la <a href="' . SmalladsUrlBuilder::usage_terms_configuration()->relative() . '">configuration des CGU</a></li>
<li class="formatter-li"> Pour créer des catégories, <a href="' . SmalladsUrlBuilder::add_category()->relative() . '">cliquez ici</a> </li>
<li class="formatter-li"> Pour ajouter des annonces, <a href="' . SmalladsUrlBuilder::add_item()->relative() . '">cliquez ici</a></li>
</ul>
<br />Pour en savoir plus, n\'hésitez pas à consulter la documentation du module sur le site de <a href="http://www.phpboost.com">PHPBoost</a>.';
?>
