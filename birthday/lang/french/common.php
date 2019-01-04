<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2018 12 20
 * @since   	PHPBoost 4.0 - 2013 08 27
 * @contributor mipel <mipel@phpboost.com>
*/

 ####################################################
 #                     French                       #
 ####################################################

//Module title
$lang['module_title'] = 'Anniversaires';

//Admin
$lang['admin.config.members_age_displayed'] = 'Afficher l\'âge des membres dans le menu';
$lang['admin.config.send_pm_for_members_birthday'] = 'Envoyer un MP à l\'anniversaire des membres';
$lang['admin.config.pm_for_members_birthday_title'] = 'Titre du MP';
$lang['admin.config.pm_for_members_birthday_title.explain'] = 'Utilisez <b>:user_display_name</b> pour afficher le pseudo du membre dans le titre et <b>:user_age</b> pour afficher son âge si besoin.';
$lang['admin.config.pm_for_members_birthday_content'] = 'Contenu du MP';
$lang['admin.config.pm_for_members_birthday_content.explain'] = 'Utilisez <b>:user_display_name</b> pour afficher le pseudo du membre dans le texte et <b>:user_age</b> pour afficher son âge si besoin.';
$lang['admin.authorizations'] = 'Autorisations';
$lang['admin.authorizations.read']  = 'Autorisation d\'afficher le menu anniversaires';

//Other
$lang['happy_birthday'] = 'Joyeux anniversaire !';
$lang['year'] = 'an';
$lang['years'] = 'ans';

//Error
$lang['user_born_field_disabled'] = 'Le champ <b>Date de naissance</b> n\'est pas affiché dans le profil des membres. Veuillez activer l\'affichage du champ dans la <a href="' . AdminExtendedFieldsUrlBuilder::fields_list()->rel() . '">Gestion des champs du profil</a> pour permettre aux membres de renseigner leur date de naissance et afficher leur date d\'anniversaire dans le menu.';
?>
