<?php
/*##################################################
 *                                 common.php
 *                            -------------------
 *   begin                : August 27, 2013
 *   copyright            : (C) 2013 Julien BRISWALTER
 *   email                : julienseth78@phpboost.com
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


 ####################################################
 #                     French                       #
 ####################################################

//Module title
$lang['module_title'] = 'Anniversaires';

//Admin
$lang['admin.config.members_age_displayed'] = 'Afficher l\'âge des membres dans le menu';
$lang['admin.config.send_pm_for_members_birthday'] = 'Envoyer un MP à l\'anniversaire des membres';
$lang['admin.config.pm_for_members_birthday_title'] = 'Titre du MP';
$lang['admin.config.pm_for_members_birthday_title.explain'] = 'Utilisez <b>:user_login</b> pour afficher le pseudo du membre dans le titre et <b>:user_age</b> pour afficher son âge si besoin.';
$lang['admin.config.pm_for_members_birthday_content'] = 'Contenu du MP';
$lang['admin.config.pm_for_members_birthday_content.explain'] = 'Utilisez <b>:user_login</b> pour afficher le pseudo du membre dans le texte et <b>:user_age</b> pour afficher son âge si besoin.';
$lang['admin.authorizations'] = 'Autorisations';
$lang['admin.authorizations.read']  = 'Autorisation d\'afficher le menu anniversaires';

//Other
$lang['happy_birthday'] = 'Joyeux anniversaire !';
$lang['year'] = 'an';
$lang['years'] = 'ans';

//Error
$lang['user_born_field_disabled'] = 'Le champ <b>Date de naissance</b> n\'est pas affiché dans le profil des membres. Veuillez activer l\'affichage du champ dans la <a href="' . AdminExtendedFieldsUrlBuilder::fields_list()->rel() . '">Gestion des champs du profils</a> pour permettre aux membres de renseigner leur date de naissance et afficher leur date d\'anniversaire dans le menu.';
?>
