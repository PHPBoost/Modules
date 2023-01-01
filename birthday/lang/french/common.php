<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 02 03
 * @since       PHPBoost 4.0 - 2013 08 27
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

####################################################
#                     French                       #
####################################################

// Titles
$lang['birthday.module.title']   = 'Anniversaires';
$lang['birthday.happy.birthday'] = 'Joyeux anniversaire !';
$lang['birthday.next.days'] = 'Dans les :coming_next prochains jours';

// Configuration
$lang['birthday.coming.next.number']                   = 'Nombre de jours pour afficher les prochains anniversaires';
$lang['birthday.members.age.displayed']                = 'Afficher l\'âge des membres dans le menu';
$lang['birthday.send.pm.for.members.birthday']         = 'Envoyer un MP à l\'anniversaire des membres';
$lang['birthday.pm.for.members.birthday.title']        = 'Titre du MP';
$lang['birthday.pm.for.members.birthday.title.clue']   = 'Utilisez <b>:user_display_name</b> pour afficher le pseudo du membre dans le titre et <b>:user_age</b> pour afficher son âge si besoin.';
$lang['birthday.pm.for.members.birthday.content']      = 'Contenu du MP';
$lang['birthday.pm.for.members.birthday.content.clue'] = 'Utilisez <b>:user_display_name</b> pour afficher le pseudo du membre dans le texte et <b>:user_age</b> pour afficher son âge si besoin.';

// Errors
$lang['birthday.user.born.field.disabled'] = 'Le champ <b>Date de naissance</b> n\'est pas affiché dans le profil des membres. Veuillez activer l\'affichage du champ dans la <a class="offload" href="' . AdminExtendedFieldsUrlBuilder::fields_list()->rel() . '">Gestion des champs du profil</a> pour permettre aux membres de renseigner leur date de naissance et afficher leur date d\'anniversaire dans le menu.';

// Default Config
$lang['birthday.config.pm.for.members.birthday.default.title']   = 'Joyeux anniversaire';
$lang['birthday.config.pm.for.members.birthday.default.content'] = 'La communauté vous souhaite un joyeux anniversaire !';
?>
