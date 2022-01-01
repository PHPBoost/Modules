<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 05 28
 * @since       PHPBoost 4.0 - 2013 08 04
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

#####################################################
#			French									#
#####################################################

$lang['server.module.title'] = 'Statut des serveurs';

// Labels
$lang['server.management']     = 'Gestion des serveurs';
$lang['server.add.item']       = 'Ajouter un serveur';
$lang['server.edit.item']      = 'Editer un serveur';
$lang['server.refresh.status'] = 'Regénérer le statut de tous les serveurs';
$lang['server.online']         = 'En ligne';
$lang['server.offline']        = 'Hors ligne';

// Configuration
$lang['server.refresh.delay'] = 'Délai de rafraîchissement automatique du statut des serveurs';
$lang['server.refresh.delay.clue'] = 'En minutes. 15 minutes par défaut.';
$lang['server.timeout'] = 'Durée du test avant de considérer un serveur <b>Hors ligne</b>';
$lang['server.timeout.clue'] = 'En millisecondes. 800 millisecondes par défaut. Augmentez un peu la valeur si votre serveur est toujours considéré comme <b>Hors ligne</b> alors qu\'il ne l\'est pas.';
$lang['server.display.address'] = 'Afficher l\'adresse des serveurs';
$lang['server.display.address.clue'] = 'Affiche l\'adresse et le port des serveurs dans la liste';

// Form
$lang['server.address.type']     = 'Type d\'adresse';
$lang['server.address.type.dns'] = 'DNS';
$lang['server.address.type.ip']  = 'IP';
$lang['server.address.dns']      = 'Nom DNS';
$lang['server.address.dns.clue'] = 'Exemple : <b>www.test.com</b>';
$lang['server.address.ip']       = 'Adresse IP';
$lang['server.address.ip.clue']  = 'Exemple : <b>1.2.3.4</b> ou <b>2001:67c:2e8:22::c100:68b</b>';
$lang['server.port']             = 'Port';
$lang['server.port.clue']        = 'Compris entre <b>1</b> et <b>65535</b>.';
$lang['server.type']             = 'Type';
$lang['server.icon']             = 'Icône';

// Messages helper
$lang['server.warning.curl.extension'] = 'L\'extension <b>php_curl</b> est désactivée sur ce serveur. Certains tests d\'accès aux serveurs risquent de ne pas fonctionner correctement.';
$lang['server.warning.empty.address'] = 'Veuillez renseigner l\'adresse du serveur';
?>
