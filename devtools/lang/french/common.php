<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      LamPDL
 * @version     PHPBoost 6.0 - last update: 2026 05 19
 * @since       PHPBoost 6.0 - 2026 03 01
 */

####################################################
#                    French                        #
####################################################

$lang['devtools.module.title'] = 'PBT Manager';

// Tabs
$lang['devtools.tab.modules']  = 'Modules présents';
$lang['devtools.tab.themes']   = 'Thèmes';
$lang['devtools.tab.config']   = 'Réglages';

// Local status table
$lang['devtools.local.modules']        = 'Modules installés';
$lang['devtools.col.name']             = 'Nom';
$lang['devtools.col.version']          = 'Version installée';
$lang['devtools.col.status']           = 'État';
$lang['devtools.col.remote.version']   = 'Version disponible';
$lang['devtools.col.actions']          = 'Actions';

$lang['devtools.status.active']        = 'Actif';
$lang['devtools.status.inactive']      = 'Inactif';
$lang['devtools.status.not.installed'] = 'Non installé';
$lang['devtools.status.up.to.date']    = 'À jour';
$lang['devtools.status.update.avail']  = 'Mise à jour disponible';
$lang['devtools.status.unknown']       = 'Inconnu';

// Actions
$lang['devtools.action.refresh']          = 'Rafraîchir';
$lang['devtools.action.close']            = 'Fermer';
$lang['devtools.action.activate']         = 'Activer';
$lang['devtools.action.activate.title']   = 'Ce module sera à nouveau disponible';
$lang['devtools.action.deactivate']       = 'Désactiver';
$lang['devtools.action.deactivate.title'] = 'Ce module sera indisponible sans perte de données';
$lang['devtools.action.uninstall']        = 'Désinstaller';
$lang['devtools.action.uninstall.soft']   = 'Désinstaller';
$lang['devtools.action.uninstall.hard']   = 'Désinstaller et supprimer';
$lang['devtools.action.local.install']    = 'Installer';
$lang['devtools.action.install.sel']      = 'Installer la sélection';
$lang['devtools.action.select.all']       = 'Tout sélectionner';
$lang['devtools.action.deselect.all']     = 'Tout désélectionner';

$lang['devtools.uninstall.soft.title']   = 'Ce module pourra être réinstallé (les fichiers sont conservés)';
$lang['devtools.uninstall.hard.title']   = 'Ce module ne sera plus disponible sans le télécharger à nouveau';
$lang['devtools.uninstall.confirm']      = 'Confirmer la désinstallation de ce module ?';
$lang['devtools.uninstall.soft.confirm'] = 'Désinstaller ce module ? Les fichiers seront conservés, il pourra être réinstallé.';
$lang['devtools.uninstall.hard.confirm'] = 'Supprimer définitivement ce module ? Les fichiers seront supprimés, il faudra le télécharger à nouveau.';
$lang['devtools.uninstall.drop.confirm'] = 'Supprimer aussi les fichiers du dossier /modules ?';

// Repo panel
$lang['devtools.repo.add']          = 'Ajouter un dépôt';
$lang['devtools.repo.add.confirm']  = 'Ajouter';
$lang['devtools.repo.cancel']       = 'Annuler';
$lang['devtools.repo.select.error'] = 'Veuillez sélectionner un dépôt.';
$lang['devtools.repo.org']          = 'Organisation GitHub';
$lang['devtools.repo.pick']         = 'Dépôt';
$lang['devtools.repo.path']         = 'Sous-dossier';
$lang['devtools.repo.label']        = 'Label affiché';

// Remote repo panel
$lang['devtools.remote.title']     = 'Dépôts distants';
$lang['devtools.remote.repo']      = 'Dépôt';
$lang['devtools.remote.branch']    = 'Branche';
$lang['devtools.remote.available'] = 'Modules disponibles';
$lang['devtools.remote.loading']   = 'Chargement…';
$lang['devtools.remote.error']     = 'Erreur de chargement du dépôt distant.';
$lang['devtools.remote.none']      = 'Aucun module trouvé dans cette branche.';

// Install feedback
$lang['devtools.install.success']      = 'Module(s) installé(s) avec succès.';
$lang['devtools.install.error']        = 'Erreur lors de l\'installation : ';
$lang['devtools.install.no.selection'] = 'Aucun module sélectionné.';

// Restore tab
$lang['devtools.restore.title']    = 'Restauration';
$lang['devtools.restore.none']     = 'Aucune sauvegarde disponible.';
$lang['devtools.restore.date']     = 'Date de sauvegarde';
$lang['devtools.restore.size']     = 'Taille';
$lang['devtools.restore.download'] = 'Télécharger .sql';

// Config
$lang['devtools.config.repos']        = 'Dépôts GitHub';
$lang['devtools.config.repo.add']     = 'Ajouter un dépôt';
$lang['devtools.config.repo.delete']  = 'Supprimer';
$lang['devtools.config.repo.org']     = 'Organisation GitHub';
$lang['devtools.config.repo.pick']    = 'Dépôt';
$lang['devtools.config.repo.owner']   = 'Propriétaire (ex: LamPDL)';
$lang['devtools.config.repo.name']    = 'Nom du dépôt (ex: LamPDL)';
$lang['devtools.config.repo.path']    = 'Sous-dossier des modules (laisser vide si racine)';
$lang['devtools.config.repo.label']   = 'Label affiché';
$lang['devtools.config.github.token'] = 'Token GitHub (optionnel, pour éviter les limites de taux)';

// SEO
$lang['devtools.seo.description'] = 'Gestion des modules PHPBoost sur ' . GeneralConfig::load()->get_site_name() . '.';

// Import BDD tab
$lang['devtools.importbdd.title']      = 'Import BDD';
$lang['devtools.importbdd.none']       = 'Aucun module avec fichiers SQL trouvé dans /backup/importBDD/.';
$lang['devtools.importbdd.error']      = 'Erreur de chargement.';
$lang['devtools.importbdd.col.module'] = 'Module';
$lang['devtools.importbdd.col.files']  = 'Tables SQL disponibles';
$lang['devtools.importbdd.col.date']   = 'Date';
$lang['devtools.importbdd.col.tables'] = 'Tables du module';
$lang['devtools.importbdd.action']     = 'Importer';
$lang['devtools.importbdd.confirm']    = 'Importer les tables du module « %s » ? Les tables existantes seront supprimées puis recréées (DROP + CREATE + INSERT).';
$lang['devtools.importbdd.success']    = 'Import effectué avec succès.';
$lang['devtools.importbdd.importing']  = 'Import en cours…';

// File Review tab
$lang['devtools.review.title']                      = 'Revue de fichiers';
$lang['devtools.review.refresh']                    = 'Analyser';
$lang['devtools.review.refreshing']                 = 'Analyse en cours…';
$lang['devtools.review.refresh.success']            = 'Analyse terminée.';
$lang['devtools.review.clear']                      = 'Vider la table';
$lang['devtools.review.clearing']                   = 'Vidage en cours…';
$lang['devtools.review.clear.success']              = 'Table vidée.';
$lang['devtools.review.info']                       = 'L\'analyse scanne les contenus de tous les modules compatibles et croise les fichiers présents sur le serveur avec ceux référencés en base de données.';
$lang['devtools.review.incompatible']               = 'Module non compatible';
$lang['devtools.review.col.file']                   = 'Fichier';
$lang['devtools.review.col.module']                 = 'Module source';
$lang['devtools.review.col.item']                   = 'Document';
$lang['devtools.review.col.edit']                   = 'Éditer';
$lang['devtools.review.col.context']                = 'Contexte';
$lang['devtools.review.col.user']                   = 'Uploadé par';
$lang['devtools.review.col.date']                   = 'Date d\'upload';
$lang['devtools.review.col.size']                   = 'Poids';
$lang['devtools.review.section.files.on.server']    = 'Fichiers sur le serveur (/upload)';
$lang['devtools.review.group.upload']               = 'Upload';
$lang['devtools.review.group.errors']               = 'Erreurs';
$lang['devtools.review.group.gallery']              = 'Galerie';
$lang['devtools.review.section.files.in.upload']    = 'Fichiers dans la table upload';
$lang['devtools.review.section.files.in.content']   = 'Fichiers utilisés dans un contenu';
$lang['devtools.review.section.all.unused']         = 'Tous les fichiers non utilisés';
$lang['devtools.review.section.used.not.on.server'] = 'Utilisés mais absents du serveur (erreur 404)';
$lang['devtools.review.section.unused.with.users']  = 'Non utilisés (avec lien upload)';
$lang['devtools.review.section.orphan']             = 'Fichiers orphelins (sans lien upload)';
$lang['devtools.review.section.gallery.folder']     = 'Fichiers dans /gallery/pics';
$lang['devtools.review.section.gallery.table']      = 'Fichiers dans la table gallery';
$lang['devtools.review.section.no.gallery.folder']  = 'Dans la table gallery mais absents du dossier';
$lang['devtools.review.section.no.gallery.table']   = 'Dans le dossier mais absents de la table gallery';
$lang['devtools.review.group.gallery.errors']       = 'Anomalies Galerie';
$lang['devtools.review.total.errors']               = 'Total de toutes les anomalies';

// Tooltips
$lang['devtools.review.tip.onserver']       = 'Fichiers physiquement présents dans le dossier /upload sur le serveur.';
$lang['devtools.review.tip.inupload']       = 'Fichiers référencés dans la table upload de la base de données.';
$lang['devtools.review.tip.incontent']      = 'Fichiers dont le chemin apparaît dans le contenu d\'un module (articles, news, wiki…). Sans doublons.';
$lang['devtools.review.tip.allunused']      = 'Fichiers présents dans la table upload mais dont le chemin n\'apparaît dans aucun contenu.';
$lang['devtools.review.tip.usednoserver']   = 'Fichiers référencés dans un contenu mais introuvables sur le serveur — ils génèrent des erreurs 404.';
$lang['devtools.review.tip.unuseduser']     = 'Fichiers non utilisés dans aucun contenu mais liés à un utilisateur via la table upload.';
$lang['devtools.review.tip.orphan']         = 'Fichiers présents dans /upload sur le serveur mais sans entrée dans la table upload.';
$lang['devtools.review.tip.galleryfolder']  = 'Fichiers physiquement présents dans le dossier /gallery/pics sur le serveur.';
$lang['devtools.review.tip.gallerytable']   = 'Fichiers référencés dans la table du module Galerie.';
$lang['devtools.review.tip.nogalleryfolder']= 'Fichiers présents dans la table Galerie mais introuvables dans le dossier /gallery/pics.';
$lang['devtools.review.tip.nogallerytable'] = 'Fichiers présents dans le dossier /gallery/pics mais sans entrée dans la table Galerie.';

// Lang Review tab
$lang['devtools.langrev.title']                = 'Revue de Lang';
$lang['devtools.langrev.select.module']        = 'Sélectionner un module';
$lang['devtools.langrev.analyzing']            = 'Analyse en cours…';
$lang['devtools.langrev.total.keys']           = 'clés au total';
$lang['devtools.langrev.section.unused']       = 'Variables inutilisées';
$lang['devtools.langrev.section.dup.internal'] = 'Doublons internes (même valeur dans le même module)';
$lang['devtools.langrev.section.dup.external'] = 'Doublons inter-modules (même valeur dans d\'autres modules)';
$lang['devtools.langrev.col.key']              = 'Nom de variable';
$lang['devtools.langrev.col.lang']             = 'Version';
$lang['devtools.langrev.col.value.fr']         = 'Valeur FR';
$lang['devtools.langrev.col.value.en']         = 'Valeur EN';
$lang['devtools.langrev.col.value']            = 'Valeur';
$lang['devtools.langrev.col.keys']             = 'Variables concernées';
$lang['devtools.langrev.col.matches']          = 'Correspondances';
$lang['devtools.langrev.none']                 = 'Aucun résultat';
$lang['devtools.langrev.error']                = 'Erreur lors de l\'analyse';
?>
