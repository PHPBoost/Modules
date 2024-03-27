<?php
/**
 * @copyright   &copy; 2005-2024 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2023 05 09
 * @since       PHPBoost 6.0 - 2022 10 17
 */

####################################################
#                       French                     #
####################################################

$lang['video.module.title'] = 'Vidéos';

// TreeLinks
$lang['item'] = 'Vidéo';
$lang['an.item'] = 'Une vidéo';
$lang['items'] = 'Vidéos';

// Titles
$lang['video.add.item']         = 'Ajouter une vidéo';
$lang['video.edit.item']        = 'Modifier une vidéo';
$lang['video.my.items']         = 'Mes vidéos';
$lang['video.member.items']     = 'Vidéos publiées par';
$lang['video.pending.items']    = 'Vidéos en attente';
$lang['video.filter.items']     = 'Filtrer les vidéos';
$lang['video.items.management'] = 'Gestion des vidéos';

$lang['video.width']  = 'Largeur de la vidéo';
$lang['video.height'] = 'Hauteur de la vidéo';

// Mini module
$lang['video.more.videos'] = 'Plus de vidéos';
$lang['video.last.items'] = 'Dernières vidéos';
$lang['video.last.items'] = 'Vidéos populaires';
$lang['video.ranking']    = 'Position';
$lang['video.watch']      = 'Voir la vidéo';

// Configuration
$lang['video.config.display.subcategories'] = 'Afficher les sous-catégories';
$lang['video.config.mini.module']    = 'Mini module';
$lang['video.config.sort.type.clue'] = 'Sens décroissant';
$lang['video.config.items.number']   = 'Nombre maximum d\'éléments affichés';
$lang['video.trusted.hosts']         = 'Sites de confiance';
$lang['video.platform']              = 'Platforme';
$lang['video.domain']                = 'Domaine';
$lang['video.host.player']           = 'Lecteur de la plateforme';
$lang['video.authorized.extensions'] = 'Liste des extensions vidéo autorisées';
$lang['video.authorized.extensions.clue'] = 'Précéder l\'extension de la mention "video/"';
$lang['video.authorized.url']        = 'Liste de plateformes d\'hébergement autorisées';

// SEO
$lang['video.seo.description.root']    = 'Toutes les vidéos du site :site.';
$lang['video.seo.description.tag']     = 'Toutes les vidéos sur le sujet :subject.';
$lang['video.seo.description.pending'] = 'Toutes les vidéos en attente.';
$lang['video.seo.description.member']  = 'Toutes les vidéos de :author.';

// Messages helper
$lang['video.message.success.add']    = 'Le vidéo <b>:title</b> a été ajoutée';
$lang['video.message.success.edit']   = 'Le vidéo <b>:title</b> a été modifiée';
$lang['video.message.success.delete'] = 'Le vidéo <b>:title</b> a été supprimée';

// Error message
$lang['e_mime_disable_video'] = 'Le type de vidéo que vous souhaitez proposer est désactivé !';
$lang['e_link_invalid_video'] = 'Veuillez renseigner un lien valide pour votre vidéo !';
$lang['e_unexist_video']      = 'La vidéo demandée n\'existe pas !';
$lang['e_bad_url_peertube']   = 'L\'url renseignée n\'est pas valide. Elle ne correspond pas à l\'url d\'une des instances PeerTube renseignées dans la configuration du module.';
$lang['e_bad_url_odysee'] = '
    L\'url Odysee renseignée n\'est pas valide. <br />
    Dans l\'onglet <span class="pinned question">Partager</span> sous la vidéo, choisir une des deux url suivantes:
    <ul>
        <li><span class="pinned question">Intégrer ce contenu</span> / url fournie dans <span class="pinned question">Intégré</span></li>
        <li><span class="pinned question">Liens</span> / url fournie dans <span class="pinned question">Lien de téléchargement</span></li>
    </ul>
';
?>
