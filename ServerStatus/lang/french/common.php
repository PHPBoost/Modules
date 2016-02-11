<?php
/*##################################################
 *								common.php
 *								-------------------
 *   begin                : August 4, 2013
 *   copyright            : (C) 2013 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
 *
 *
###################################################
 *
 *	This program is free software; you can redistribute it and/or modify
 *	it under the terms of the GNU General Public License as published by
 *	the Free Software Foundation; either version 2 of the License, or
 *	(at your option) any later version.
 *
 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU General Public License for more details.
 *
 *	You should have received a copy of the GNU General Public License
 *	along with this program; if not, write to the Free Software
 *	Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

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
$lang['admin.config.servers.delete_server.confirm'] = 'Souhaitez vous vraiment supprimer ce serveur ?';
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
