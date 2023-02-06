<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2023 02 06
 * @since       PHPBoost 6.0 - 2021 08 22
*/

####################################################
#						French						#
####################################################

$lang['spots.module.title'] = 'Localisations';

$lang['item'] = 'localisation';
$lang['an.item'] = 'une localisation';

$lang['spots.member.items']  = 'Localisations publiées par';
$lang['spots.my.items']      = 'Mes localisations';
$lang['spots.pending.items'] = 'Localisations en attente';
$lang['spots.items.number']  = 'Nombre de localisations';
$lang['spots.filter.items']  = 'Filtrer les localisations';

$lang['spots.add']        = 'Ajouter une localisation';
$lang['spots.edit']       = 'Modifier une localisation';
$lang['spots.management'] = 'Gestion des localisations';

$lang['spots.address']           = 'Adresse';
$lang['spots.visit.website']     = 'Visiter le site web';
$lang['spots.no.website']        = 'Aucun site web répertorié';
$lang['spots.link.infos']        = 'Informations sur la localisation';
$lang['spots.contact']           = 'Contact';
$lang['spots.location']          = 'Coordonnées GPS';
$lang['spots.location.lat']      = 'Latitude';
$lang['spots.location.lng']      = 'Longitude';
$lang['spots.travel.type']       = 'Type de trajet';
$lang['spots.travel.type.car']   = 'en voiture';
$lang['spots.travel.type.walk']  = 'à pied';
$lang['spots.travel.type.bike']  = 'à vélo';
$lang['spots.travel.type.train'] = 'en train';
$lang['spots.osm.french']        = 'Français';
$lang['spots.osm.satellite']     = 'Satellite';
$lang['spots.osm.topo']          = 'Topographie';
$lang['spots.google.hybrid']     = 'Google hybrid';
$lang['spots.google.sat']        = 'Google satellite';
$lang['spots.google.terrain']    = 'Google terrain';
$lang['spots.google.roadmap']    = 'Google routes';

$lang['spots.change.orign.address'] = 'Calculer un nouvel itinéraire';
$lang['spots.route.infos']          = '
    L\'itinéraire est calculé à partir de l\'adresse par défaut du site. <br />
    Pour obtenir un itinéraire différent, déplacez le point d\'origine (A) ou déclarez une adresse dans le champ suivant.
';
$lang['spots.new.location']      = 'Nouvelle adresse de départ';
$lang['spots.new.location.clue'] = 'Sélectionner dans la liste de saisie semi-automatique pour qu\'elle soit prise en compte.';
$lang['spots.waze.description']  = '
    Vous pouvez envoyer cette destination dans l\'espace de recherche du site internet de Waze.<br />
    Sur mobile, vous serez d\'abord redirigé sur le site internet de Waze, depuis lequel vous pourrez ouvrir l\'application pour obtenir le trajet depuis votre position.
';
$lang['spots.send.to.waze']      = 'Ouvrir dans Waze';

// Form
$lang['spots.location.clue']          = 'Remplir le champ Adresse et valider avec le choix proposé et/ou déplacer le pointeur.<br /> Seul le pointeur est nécessaire.';
$lang['spots.display.route']          = 'Afficher l\'itinéraire';
$lang['spots.display.route.clue']     = 'depuis la localisation par défaut définie dans GoogleMaps.<br /> Il ne doit pas y avoir de mer/océan entre les deux localisations.';
    // Categories
$lang['spots.inner.icon']             = 'Icône';
$lang['spots.inner.icon.clue']        = '
    <a href="https://fontawesome.com/v5.15/icons?d=gallery&p=2" target="_blank" rel="noopener noreferrer">Liste des icônes Font Awesome</a>
    <span class="d-block">
        Si aucune icône n\'est déclarée, la catégorie prendra l\'icône par défaut définie dans la configuration.
    </span>
';
$lang['spots.inner.icon.placeholder'] = 'fa fa-...';
$lang['spots.category.address'] = 'Adresse de départ';
$lang['spots.category.address.clue'] = '
    Pour le calcul des itinéraires. <br />
    Si laissée vide, l\'adresse de la configuration du module GoogleMaps la remplace.
';

// Configuration
$lang['spots.module.name']               = 'Titre du module';
$lang['spots.default.color']             = 'Couleur par défaut';
$lang['spots.default.inner.icon']        = 'Icône par défaut';
$lang['spots.default.color.clue']        = 'Défini la couleur de la catégorie racine et initialise la couleur pour l\'ajout d\'une catégorie.';
$lang['spots.default.inner.icon.clue']   = 'Défini l\'icône de la catégorie racine et l\'icône d\'une catégorie si aucune icône n\'est choisie.';
$lang['spots.root.category.description'] = '
    Bienvenue dans l\'espace du site consacré aux localisations !
    <br /><br />
    Une catégorie et une localisation ont été créées pour vous montrer comment fonctionne ce module. Voici quelques conseils pour bien débuter sur ce module.
    <br /><br />
    <ul class="formatter-ul">
        <li class="formatter-li"> Pour configurer ou personnaliser l\'accueil de votre module, rendez vous dans l\'<a class="offload" href="' . Url::to_rel(SpotsUrlBuilder::configuration('spots')) . '">administration du module</a></li>
        <li class="formatter-li"> Pour créer des catégories, <a class="offload" href="' . Url::to_rel(CategoriesUrlBuilder::add(Category::ROOT_CATEGORY, 'spots')) . '">cliquez ici</a> </li>
        <li class="formatter-li"> Pour ajouter des localisations, <a class="offload" href="' . Url::to_rel(SpotsUrlBuilder::add(Category::ROOT_CATEGORY, 'spots')) . '">cliquez ici</a></li>
    </ul>
    <br />Pour en savoir plus, n\'hésitez pas à consulter la documentation du module sur le site de <a class="offload" href="https://www.phpboost.com">PHPBoost</a>.
';

// S.E.O.
$lang['spots.seo.description.member']  = 'Toutes les localisations publiées par :author.';
$lang['spots.seo.description.pending'] = 'Toutes les localisations en attente.';
$lang['spots.seo.description.root']    = 'Toutes les localisations du site :site .';

// Messages
$lang['spots.message.success.add']    = 'La localisation <b>:name</b> a été ajoutée';
$lang['spots.message.success.edit']   = 'La localisation <b>:name</b> a été modifiée';
$lang['spots.message.success.delete'] = 'La localisation <b>:name</b> a été supprimée';

// Social Network
$lang['spots.social.network']        = 'Réseaux sociaux';
$lang['spots.labels.facebook']       = 'Adresse du compte Facebook <i class="fab fa-fw fa-facebook" aria-hidden="true"></i>';
$lang['spots.placeholder.facebook']  = 'https://www.facebook.com/...';
$lang['spots.labels.twitter']        = 'Adresse du compte Twitter <i class="fab fa-fw fa-twitter" aria-hidden="true"></i>';
$lang['spots.placeholder.twitter']   = 'https://www.twitter.com/...';
$lang['spots.labels.instagram']      = 'Adresse du compte Instagram <i class="fab fa-fw fa-instagram" aria-hidden="true"></i>';
$lang['spots.placeholder.instagram'] = 'https://www.instagram.com/...';
$lang['spots.labels.youtube']        = 'Adresse du compte Youtube <i class="fab fa-fw fa-youtube" aria-hidden="true"></i>';
$lang['spots.placeholder.youtube']   = 'https://www.youtube.com/...';

// Warnings
$lang['spots.no.gmap']            = 'Vous devez installer et activer le module GoogleMaps et le configurer (clé + lieu par défaut).';
$lang['spots.no.default.address'] = 'L\'adresse par défaut n\'a pas été déclarée dans la configuration du module GoogleMaps.';
$lang['spots.no.gps']             = 'Les coordonnées GPS du lieu de la localisation n\'ont pas été renseignées.';
?>
