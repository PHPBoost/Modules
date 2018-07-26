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
#                      French			    #
####################################################

$lang['default.category.name'] = 'Catégorie de test';
$lang['default.category.description'] = 'Annonces de démonstration';
$lang['default.smallad.title'] = 'Petites annonces pour PHPBoost ' . GeneralConfig::load()->get_phpboost_major_version();
$lang['default.smallad.description'] = '';
$lang['default.smallad.contents'] = 'Cette première annonce va vous donner quelques conseils simples pour prendre en main ce module.<br />
<br />
<ul class="formatter-ul">
	<li class="formatter-li"> Pour configurer ou personnaliser votre module, rendez vous dans la <a href="' . SmalladsUrlBuilder::categories_configuration()->relative() . '">configuration des catégories</a></li>
	<li class="formatter-li"> Pour configurer ou personnaliser les annonces et filtres d\'affichage, rendez vous dans la <a href="' . SmalladsUrlBuilder::items_configuration()->relative() . '">configuration des annonces</a></li>
	<li class="formatter-li"> Pour configurer ou personnaliser les conditions générales d\'utilisation, rendez vous dans la <a href="' . SmalladsUrlBuilder::usage_terms_configuration()->relative() . '">configuration des CGU</a></li>
	<li class="formatter-li"> Pour créer des catégories, <a href="' . SmalladsUrlBuilder::add_category()->relative() . '">cliquez ici</a> </li>
	<li class="formatter-li"> Pour ajouter des annonces, <a href="' . SmalladsUrlBuilder::add_item()->relative() . '">cliquez ici</a></li>
</ul>
<ul class="formatter-ul">
<li class="formatter-li">Pour mettre en page vos articles, vous pouvez utiliser le langage bbcode ou l\'éditeur WYSIWYG (cf cet <a href="http://www.phpboost.com/wiki/bbcode">article</a>)<br />
</li>
</ul>
<br /><br />
Pour en savoir plus, n\'hésitez pas à consulter la documentation du module sur le site de <a href="https://www.phpboost.com/wiki/articles">PHPBoost</a>.<br />
<br />
<br />
Bonne utilisation de ce module.
';
$lang['default.smallad.type'] = 'Type de test';
$lang['config.usage.terms.conditions'] = '
<h2 class="formatter-title">ARTICLE 1 : Objet</h2>
<p>Les présentes « conditions générales d\'utilisation » ont pour objet l\'encadrement juridique des modalités de mise à disposition des services du site <span style="background-color:#CCFFFF;">[Nom du site]</span> et leur utilisation par « l\'utilisateur ».</p>
<p>Les conditions générales d\'utilisation doivent être acceptées par tout utilisateur souhaitant accéder au site. Elles constituent le contrat entre le site et l\'utilisateur. L\'accès au site par l\'utilisateur signifie son acceptation des présentes conditions générales d’utilisation.</p>
<p>Éventuellement :</p>
<ul class="formatter-ul">
    <li class="formatter-li">En cas de non-acceptation des conditions générales d\'utilisation stipulées dans le présent contrat, l\'utilisateur se doit de renoncer à l\'accès des services proposés par le site.</li>
    <li class="formatter-li"><span style="background-color:#CCFFFF;">[Nom du site]</span> se réserve le droit de modifier unilatéralement et à tout moment le contenu des présentes conditions générales d\'utilisation.</li>
</ul>

<h2 class="formatter-title">ARTICLE 2 : Mentions légales</h2>
<p>L\'édition du site <span style="background-color:#CCFFFF;">[Nom du site]</span> est assurée par la Société <span style="background-color:#CCFFFF;">[Nom de la société]</span> <span style="background-color:#CCFFFF;">[SAS / SA / SARL, etc.]</span> au capital de <span style="background-color:#CCFFFF;">[montant en euros]</span> € dont le siège social est situé au <span style="background-color:#CCFFFF;">[adresse du siège social]</span>.</p>
<p><span style="background-color:#CCFFFF;">[Le Directeur / La Directrice]</span> de la publication est <span style="background-color:#CCFFFF;">[Madame / Monsieur]</span> <span style="background-color:#CCFFFF;">[Nom & Prénom]</span>.</p>
<p>Éventuellement :</p>
<ul class="formatter-ul">
    <li class="formatter-li"><span style="background-color:#CCFFFF;">[Nom de la société]</span> est une société du groupe <span style="background-color:#CCFFFF;">[Nom de la société]</span> <span style="background-color:#CCFFFF;">[SAS / SA / SARL, etc.]</span> au capital de <span style="background-color:#CCFFFF;">[montant en euros]</span> € dont le siège social est situé au <span style="background-color:#CCFFFF;">[adresse du siège social]</span>.</li>
    <li class="formatter-li">L\'hébergeur du site <span style="background-color:#CCFFFF;">[Nom du site]</span> est la Société <span style="background-color:#CCFFFF;">[Nom de la société]</span> <span style="background-color:#CCFFFF;">[SAS / SA / SARL, etc.]</span> au capital de <span style="background-color:#CCFFFF;">[montant en euros]</span> € dont le siège social est situé au <span style="background-color:#CCFFFF;">[adresse du siège social]</span>.</li>
</ul>

<h2 class="formatter-title">ARTICLE 3 : Définitions</h2>
<p>La présente clause a pour objet de définir les différents termes essentiels du contrat :</p>
<ul class="formatter-ul">
    <li class="formatter-li">utilisateur : ce terme désigne toute personne qui utilise le site ou l\'un des services proposés par le site.</li>
    <li class="formatter-li">Contenu utilisateur : ce sont les données transmises par l\'utilisateur au sein du site.</li>
    <li class="formatter-li">Membre : l\'utilisateur devient membre lorsqu\'il est identifié sur le site.</li>
    <li class="formatter-li">Identifiant et mot de passe : c\'est l\'ensemble des informations nécessaires à l\'identification d\'un utilisateur sur le site. L\'identifiant et le mot de passe permettent à l\'utilisateur d\'accéder à des services réservés aux membres du site. Le mot de passe est confidentiel.</li>
</ul>

<h2 class="formatter-title">ARTICLE 4 : accès aux services</h2>
<p>Le site permet à l\'utilisateur un accès gratuit aux services suivants :</p>
<ul class="formatter-ul">
    <li class="formatter-li"><span style="background-color:#CCFFFF;">[articles d’information]</span> ;</li>
    <li class="formatter-li"><span style="background-color:#CCFFFF;">[annonces classées]</span> ;</li>
    <li class="formatter-li"><span style="background-color:#CCFFFF;">[mise en relation de personnes]</span> ;</li>
    <li class="formatter-li"><span style="background-color:#CCFFFF;">[publication de commentaires / d’œuvres personnelles]</span> ;</li>
    <li class="formatter-li"><span style="background-color:#CCFFFF;">[…]</span>.</li>
</ul>
<p>Le site est accessible gratuitement en tout lieu à tout utilisateur ayant un accès à Internet. Tous les frais supportés par l\'utilisateur pour accéder au service (matériel informatique, logiciels, connexion Internet, etc.) sont à sa charge.</p>
<p>Selon le cas :</p>
<p>L\'utilisateur non membre n\'a pas accès aux services réservés aux membres. Pour cela, il doit s\'identifier à l\'aide de son identifiant et de son mot de passe.</p>
<p>Le site met en œuvre tous les moyens mis à sa disposition pour assurer un accès de qualité à ses services. L\'obligation étant de moyens, le site ne s\'engage pas à atteindre ce résultat.</p>
<p>Tout événement dû à un cas de force majeure ayant pour conséquence un dysfonctionnement du réseau ou du serveur n\'engage pas la responsabilité de <span style="background-color:#CCFFFF;">[Nom du site]</span>.</p>
<p>L\'accès aux services du site peut à tout moment faire l\'objet d\'une interruption, d\'une suspension, d\'une modification sans préavis pour une maintenance ou pour tout autre cas. L\'utilisateur s\'oblige à ne réclamer aucune indemnisation suite à l\'interruption, à la suspension ou à la modification du présent contrat.</p>
<p>L\'utilisateur a la possibilité de contacter le site par messagerie électronique à l’adresse <span style="background-color:#CCFFFF;">[contact@nomdusite.com]</span>.</p>

<h2 class="formatter-title">ARTICLE 5 : Propriété intellectuelle</h2>
<p>Les marques, logos, signes et tout autre contenu du site font l\'objet d\'une protection par le Code de la propriété intellectuelle et plus particulièrement par le droit d\'auteur.</p>
<p>L\'utilisateur sollicite l\'autorisation préalable du site pour toute reproduction, publication, copie des différents contenus.</p>
<p>L\'utilisateur s\'engage à une utilisation des contenus du site dans un cadre strictement privé. Une utilisation des contenus à des fins commerciales est strictement interdite.</p>
<p>Tout contenu mis en ligne par l\'utilisateur est de sa seule responsabilité. L\'utilisateur s\'engage à ne pas mettre en ligne de contenus pouvant porter atteinte aux intérêts de tierces personnes. Tout recours en justice engagé par un tiers lésé contre le site sera pris en charge par l\'utilisateur.</p>
<p>Le contenu de l\'utilisateur peut être à tout moment et pour n\'importe quelle raison supprimé ou modifié par le site. L\'utilisateur ne reçoit aucune justification et notification préalablement à la suppression ou à la modification du contenu utilisateur.</p>

<h2 class="formatter-title">ARTICLE 6 : Données personnelles</h2>
<p>Les informations demandées à l’inscription au site sont nécessaires et obligatoires pour la création du compte de l\'utilisateur. En particulier, l\'adresse électronique pourra être utilisée par le site pour l\'administration, la gestion et l\'animation du service.</p>
<p>Le site assure à l\'utilisateur une collecte et un traitement d\'informations personnelles dans le respect de la vie privée conformément à la loi n°78-17 du 6 janvier 1978 relative à l\'informatique, aux fichiers et aux libertés. Le site est déclaré à la CNIL sous le numéro <span style="background-color:#CCFFFF;">[numéro]</span>.</p>
<p>En vertu des articles 39 et 40 de la loi en date du 6 janvier 1978, l\'utilisateur dispose d\'un droit d\'accès, de rectification, de suppression et d\'opposition de ses données personnelles. L\'utilisateur exerce ce droit via :</p>
<ul class="formatter-ul">
    <li class="formatter-li">son espace personnel ;</li>
    <li class="formatter-li">un formulaire de contact ;</li>
    <li class="formatter-li">par mail à <span style="background-color:#CCFFFF;">[adresse mail]</span> ;</li>
    <li class="formatter-li">par voie postale au <span style="background-color:#CCFFFF;">[adresse]</span>.</li>
</ul>

<h2 class="formatter-title">ARTICLE 7 : Responsabilité et force majeure</h2>
<p>Les sources des informations diffusées sur le site sont réputées fiables. Toutefois, le site se réserve la faculté d\'une non-garantie de la fiabilité des sources. Les informations données sur le site le sont à titre purement informatif. Ainsi, l\'utilisateur assume seul l\'entière responsabilité de l\'utilisation des informations et contenus du présent site.</p>
<p>L\'utilisateur s\'assure de garder son mot de passe secret. Toute divulgation du mot de passe, quelle que soit sa forme, est interdite.</p>
<p>L\'utilisateur assume les risques liés à l\'utilisation de son identifiant et mot de passe. Le site décline toute responsabilité.</p>
<p>Tout usage du service par l\'utilisateur ayant directement ou indirectement pour conséquence des dommages doit faire l\'objet d\'une indemnisation au profit du site.</p>
<p>Une garantie optimale de la sécurité et de la confidentialité des données transmises n\'est pas assurée par le site. Toutefois, le site s\'engage à mettre en œuvre tous les moyens nécessaires afin de garantir au mieux la sécurité et la confidentialité des données.</p>
<p>La responsabilité du site ne peut être engagée en cas de force majeure ou du fait imprévisible et insurmontable d\'un tiers.</p>

<h2 class="formatter-title">ARTICLE 8 : Liens hypertextes</h2>
<p>De nombreux liens hypertextes sortants sont présents sur le site, cependant les pages web où mènent ces liens n\'engagent en rien la responsabilité de <span style="background-color:#CCFFFF;">[Nom du site]</span> qui n\'a pas le contrôle de ces liens.</p>
<p>L\'utilisateur s\'interdit donc à engager la responsabilité du site concernant le contenu et les ressources relatives à ces liens hypertextes sortants.</p>

<h2 class="formatter-title">ARTICLE 9 : Évolution du contrat</h2>
<p>Le site se réserve à tout moment le droit de modifier les clauses stipulées dans le présent contrat.</p>

<h2 class="formatter-title">ARTICLE 10 : Durée</h2>
<p>La durée du présent contrat est indéterminée. Le contrat produit ses effets à l\'égard de l\'utilisateur à compter de l\'utilisation du service.</p>

<h2 class="formatter-title">ARTICLE 11 : Droit applicable et juridiction compétente</h2>
<p>La législation française s\'applique au présent contrat. En cas d\'absence de résolution amiable d\'un litige né entre les parties, seuls les tribunaux <span style="background-color:#CCFFFF;">[du ressort de la Cour d\'appel de / de la ville de]</span> <span style="background-color:#CCFFFF;">[Ville]</span> sont compétents.</p>

<h2 class="formatter-title">ARTICLE 12 : Publication par l’utilisateur</h2>
<p>Le site permet aux membres de publier <span style="background-color:#CCFFFF;">[des commentaires / des œuvres personnelles]</span>.</p>
<p>Dans ses publications, le membre s’engage à respecter les règles de la Netiquette et les règles de droit en vigueur.</p>
<p>Le site exerce une modération <span style="background-color:#CCFFFF;">[a priori / a posteriori]</span> sur les publications et se réserve le droit de refuser leur mise en ligne, sans avoir à s’en justifier auprès du membre.</p>
<p>Le membre reste titulaire de l’intégralité de ses droits de propriété intellectuelle. Mais en publiant une publication sur le site, il cède à la société éditrice le droit non exclusif et gratuit de représenter, reproduire, adapter, modifier, diffuser et distribuer sa publication, directement ou par un tiers autorisé, dans le monde entier, sur tout support (numérique ou physique), pour la durée de la propriété intellectuelle.
Le Membre cède notamment le droit d\'utiliser sa publication sur internet et sur les réseaux de téléphonie mobile.</p>
<p>La société éditrice s\'engage à faire figurer le nom du membre à proximité de chaque utilisation de sa publication.</p>

';
?>
