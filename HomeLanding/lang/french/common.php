<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 14
 * @since       PHPBoost 5.0 - 2016 01 02
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
*/

####################################################
#                      French                      #
####################################################

$lang['homelanding.module.title']        = 'Page d\'accueil';
$lang['homelanding.config.module.title'] = 'Configuration du module Page d\'accueil';
$lang['homelanding.modules.position']    = 'Position des éléments';

// Module detection
$lang['homelanding.add.modules']             = 'Ajouter des éléments';
$lang['homelanding.add.modules.warning']     = '
    <p class="text-strong">:modules_list</p>
    <p>Après avoir suivi les instructions de <a href="https://www.phpboost.com/wiki/ajouter-un-module-dans-homelanding">la documentation</a> sur le site officiel de PHPBoost, cliquer sur <strong>Valider</strong> pour ajouter les nouveaux modules à la <strong>Page d\'accueil</strong>.</p>
';
$lang['homelanding.new.modules']             = 'Nouveaux modules détectés';
$lang['homelanding.no.new.module']           = '<p>Les nouveaux modules ont bien été ajoutés à la <strong>Page d\'accueil</strong>.</p>';
$lang['homelanding.back.to.configuration']   = '<p>La <a href="' . HomeLandingUrlBuilder::configuration()->rel() . '">configuration</a> des nouveaux modules est maintenant disponible.</p>';
$lang['homelanding.new.modules.description'] = '
    <p>Des nouveaux modules compatibles à la <strong>Page d\'accueil</strong> ont été installés et activés sur le site: </p>
    <p class="text-strong">:modules_list</p>
    <p>S\'ils ne sont pas prévus nativement dans le module <strong>Page d\'accueil</strong>, ils peuvent y être ajoutés en suivant les instructions de <a href="https://www.phpboost.com/wiki/ajouter-un-module-dans-homelanding">la documentation</a> sur le site officiel de PHPBoost.</p>
';

// Messages
$lang['homelanding.posted.in.topic']  = 'Posté dans le sujet :';
$lang['homelanding.posted.in.module'] = 'Posté dans le module:';

// Modules labels
$lang['homelanding.see.module']          = 'Voir le module';
$lang['homelanding.module.carousel']     = 'Carrousel';
$lang['homelanding.module.anchors_menu'] = 'Menu d\'accueil';
$lang['homelanding.module.edito']        = 'Edito';
$lang['homelanding.module.lastcoms']     = 'Commentaires';
    // Module position
$lang['homelanding.module.articles_category'] = 'Articles - catégorie';
$lang['homelanding.module.download_category'] = 'Téléchargements - catégorie';
$lang['homelanding.module.news_category']     = 'Actualités - catégorie';
$lang['homelanding.module.pinned_news']       = 'Actualités épinglées';
$lang['homelanding.module.smallads_category'] = 'Petites annonces - catégorie';
$lang['homelanding.module.web_category']      = 'Liens web - catégorie';
    // Anchors tab
$lang['homelanding.category.articles_category'] = 'Articles';
$lang['homelanding.category.download_category'] = 'Téléchargements';
$lang['homelanding.category.news_category']     = 'Actualités';
$lang['homelanding.category.smallads_category'] = 'Petites annonces';
$lang['homelanding.category.web_category']      = 'Liens web';

// Anchors menu
$lang['homelanding.anchors.title'] = 'Menu d\'accueil';

// Carousel
$lang['homelanding.carousel.no.alt'] = 'Élément du carrousel';

// Contact
$lang['homelanding.link.to.contact']                   = 'Voir la page contact';
$lang['homelanding.send.email.success']                = 'Votre message a bien été envoyé. ';
$lang['homelanding.send.email.error']                  = 'Votre message n\'a pas pu être envoyé. ';
$lang['homelanding.send.email.acknowledgment']         = 'Un message de confirmation vous a été envoyé. ';
$lang['homelanding.send.email.tracking.number']        = 'Numéro de suivi';
$lang['homelanding.send.email.acknowledgment.title']   = 'Confirmation';
$lang['homelanding.send.email.acknowledgment.correct'] = 'Votre message a été envoyé correctement. ';
$lang['homelanding.send.another.email']                = 'Envoyer un autre message.';

// Configuration
$lang['homelanding.label.module.title']      = 'Titre du module';
$lang['homelanding.label.module.title.clue'] = 'Affiche le titre du module sur la page, le breadcrumb et l\'onglet';

$lang['homelanding.hide.menu.left']           = 'Cacher le menu gauche';
$lang['homelanding.hide.menu.right']          = 'Cacher le menu droite';
$lang['homelanding.hide.menu.top.central']    = 'Cacher le menu central haut';
$lang['homelanding.hide.menu.bottom.central'] = 'Cacher le menu central bas';
$lang['homelanding.hide.menu.top.footer']     = 'Cacher le menu sur-pied de page';

$lang['homelanding.module.display']         = 'Affichage du module ';
$lang['homelanding.show.module']            = 'Afficher le module';
$lang['homelanding.show.full.module']       = 'Afficher le module complet';
$lang['homelanding.display.category']       = 'Afficher une catégorie';
$lang['homelanding.items.number']           = 'Nombre d\'éléments à afficher';
$lang['homelanding.characters.limit']       = 'Limiter le nombre de caractères';
$lang['homelanding.choose.category']        = 'Choisir une catégorie';
$lang['homelanding.display.sub.categories'] = 'Afficher le contenu des sous-catégories';
    // Pinned news
$lang['homelanding.pinned.news.title']      = 'Titre sur la page d\'accueil';
$lang['homelanding.show.pinned.news']       = 'Afficher les actualités épinglées';
    // Default
$lang['homelanding.title'] = 'Bienvenue';
$lang['homelanding.edito.description'] = 'Accédez à la <a class="offload" href="' . HomeLandingUrlBuilder::configuration()->relative() . '">configuration du module</a> pour paramétrer la page d\'accueil';

// Configuration Anchors Menu
$lang['homelanding.config.anchors']       = 'Affichage du menu d\'accueil';
$lang['homelanding.display.anchors']      = 'Afficher le menu d\'accueil';
$lang['homelanding.display.anchors.clue'] = 'Ce menu permet de naviguer rapidement au sein de la page d\'accueil';

// Configuration Edito
$lang['homelanding.config.edito']  = 'Affichage de l\'édito';
$lang['homelanding.display.edito'] = 'Afficher l\'édito';
$lang['homelanding.edito.content'] = 'Contenu de l\'édito';

// Configuration Lastcoms
$lang['homelanding.config.lastcoms']  = 'Affichage des commentaires';
$lang['homelanding.display.lastcoms'] = 'Afficher les commentaires récents';
$lang['homelanding.lastcoms.limit']   = 'Nombre de commentaires à afficher';

// Configuration Carousel
$lang['homelanding.config.carousel']      = 'Affichage du carrousel';
$lang['homelanding.display.carousel']     = 'Afficher le carrousel';
$lang['homelanding.carousel.content']     = 'Contenu du carrousel';
$lang['homelanding.carousel.speed']       = 'Vitesse de changement d\'image (ms)';
$lang['homelanding.carousel.time']        = 'Durée d\'affichage d\'une image (ms)';
$lang['homelanding.carousel.number']      = 'Nombre d\'images affichées';
$lang['homelanding.carousel.number.clue'] = '0px < 1 image < 768px < 2 images < 1024px < choix';
$lang['homelanding.carousel.auto']        = 'Défilement automatique';
$lang['homelanding.carousel.hover']       = 'Pause au survol';
$lang['homelanding.carousel.enabled']     = 'Activé';
$lang['homelanding.carousel.disabled']    = 'Désactivé';
    // Content
$lang['homelanding.carousel.description'] = 'Description du slide';
$lang['homelanding.carousel.link.url']    = 'Adresse du lien';
$lang['homelanding.carousel.picture.url'] = 'Adresse de l\'image';
$lang['homelanding.carousel.upload']      = 'Ouvrir le gestionnaire de fichiers';
$lang['homelanding.carousel.add']         = 'Ajouter une image';
$lang['homelanding.carousel.del']         = 'Supprimer le slide';

// Modules configuration
$lang['homelanding.calendar.clue']     = 'Affiche uniquement les événements à venir';
$lang['homelanding.flux.clue']     = 'Affiche les éléments de flux les plus récents parmi tous les flux';
$lang['homelanding.web.clue']          = 'Affiche seulement les liens partenaires';
?>
