<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2018 12 20
 * @since       PHPBoost 4.0 - 2013 08 04
 * @contributor mipel <mipel@phpboost.com>
*/

#####################################################
#			French									#
#####################################################

//Module title
$lang['module_title'] = 'Etat des serveurs';

//Admin
$lang['admin.config.servers.management'] = 'Gestion des serveurs';
$lang['admin.config.servers.manage'] = 'Gérer les serveurs';
$lang['admin.config.servers.title.add_server'] = 'Ajout d\'un nouveau serveur';
$lang['admin.config.servers.title.edit_server'] = 'Edition d\'un serveur';
$lang['admin.config.servers.action.add_server'] = 'Ajouter un serveur';
$lang['admin.config.servers.action.edit_server'] = 'Modifier le serveur';
$lang['admin.config.servers.delete_server'] = 'Supprimer le serveur';
$lang['admin.config.servers.delete_server.confirm'] = 'Souhaitez-vous vraiment supprimer ce serveur ?';
$lang['admin.config.servers.update_fields_position'] = 'Valider la position des serveurs';
$lang['admin.config.servers.move_up'] = 'Monter le serveur';
$lang['admin.config.servers.move_down'] = 'Descendre le serveur';
$lang['admin.config.servers.no_server'] = 'Aucun serveur';
$lang['admin.config.servers.status_refresh'] = 'Regénérer le statut de tous les serveurs';

//Config
$lang['admin.config.curl_extension_disabled'] = 'L\'extension <b>php_curl</b> est désactivée sur ce serveur. Certains tests d\'accès aux serveurs risquent de ne pas fonctionner correctement.';
$lang['admin.config.refresh_delay'] = 'Délai de rafraîchissement automatique du statut des serveurs';
$lang['admin.config.refresh_delay.explain'] = 'En minutes. 15 minutes par défaut.';
$lang['admin.config.timeout'] = 'Durée du test avant de considérer un serveur <b>Hors ligne</b>';
$lang['admin.config.timeout.explain'] = 'En millisecondes. 800 millisecondes par défaut. Augmentez un peu la valeur si votre serveur est toujours considéré comme <b>Hors ligne</b> alors qu\'il ne l\'est pas.';
$lang['admin.config.address_displayed'] = 'Afficher l\'adresse des serveurs';
$lang['admin.config.address_displayed.explain'] = 'Affiche l\'adresse et le port des serveurs dans la liste';
$lang['admin.authorizations'] = 'Autorisations';
$lang['admin.authorizations.read']  = 'Autorisation d\'afficher la liste des serveurs';
$lang['admin.authorizations.display_server']  = 'Autorisation d\'afficher le serveur';

//Server
$lang['server.online'] = 'En ligne';
$lang['server.offline'] = 'Hors ligne';
$lang['server.name'] = 'Nom';
$lang['server.description'] = 'Description';
$lang['server.address_type'] = 'Type d\'adresse';
$lang['server.address_type.dns'] = 'DNS';
$lang['server.address_type.ip'] = 'IP';
$lang['server.address.dns'] = '* Nom DNS';
$lang['server.address.dns.explain'] = 'Exemple : <b>www.test.com</b>';
$lang['server.address.ip'] = '* Adresse IP';
$lang['server.address.ip.explain'] = 'Exemple : <b>1.2.3.4</b> ou <b>2001:67c:2e8:22::c100:68b</b>';
$lang['server.port'] = 'Port';
$lang['server.port.explain'] = 'Compris entre <b>1</b> et <b>65535</b>.';
$lang['server.type'] = 'Type';
$lang['server.icon'] = 'Icône';
$lang['server.icon.none_e'] = 'Aucune';
$lang['server.display'] = 'Afficher';
$lang['server.not_display'] = 'Ne pas afficher';
$lang['server.applications'] = 'Applications';
$lang['server.games'] = 'Jeux';

//Messages
$lang['message.empty_address'] = 'Veuillez renseigner l\'adresse du serveur';
$lang['message.unexist_address'] = 'L\'adresse indiquée est invalide';
?>
