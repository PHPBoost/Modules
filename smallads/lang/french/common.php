<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 07 04
 * @since       PHPBoost 5.0 - 2016 02 02
 * @contributor Mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

#####################################################
#                      French                       #
#####################################################

$lang['smallads.module.title'] = 'Petites Annonces';

// Tree links automatic vars
$lang['item']  = 'Annonce';
$lang['items'] = 'Annonces';

// Labels
$lang['smallads.my.items']       = 'Mes annonces';
$lang['smallads.archived.items'] = 'Annonces archivées';
$lang['smallads.pending.items']  = 'Annonces en attente';
$lang['smallads.member.items']   = 'Annonces publiées par';
$lang['smallads.filter.items']   = 'Filtrer les annonces';

$lang['smallads.items.management'] = 'Gestion des annonces';
$lang['smallads.add.item']         = 'Ajouter une annonce';
$lang['smallads.edit.item']        = 'Modification d\'une annonce';
$lang['smallads.feed.name']        = 'Dernières annonces';

$lang['smallads.category.list']   = 'Catégories';
$lang['smallads.category.select'] = 'Choisir une catégorie';
$lang['smallads.category.all']    = 'Toutes les catégories';
$lang['smallads.select.category'] = 'Sélectionnez une catégorie';

$lang['smallads.ad.type']  = 'Type';
$lang['smallads.category'] = 'Catégorie';

$lang['smallads.publication.date'] = 'Publié depuis';
$lang['smallads.contact']          = 'Contacter l\'auteur';
$lang['smallads.contact.email']    = 'par email';
$lang['smallads.contact.pm']       = 'par messagerie privée';
$lang['smallads.contact.phone']    = 'par téléphone';

$lang['smallads.item.is.archived'] = 'Cet élément a dépassé la date de publication, il n\'est pas affiché pour les autres utilisateurs du site.';

// Categories configuration
$lang['smallads.categories.config'] = 'Configuration des catégories';
$lang['smallads.cats.icon.display'] = 'Afficher l\'icône des catégories';
$lang['smallads.default.content']   = 'Contenu par défaut d\'une petite annonce';
    // Default
$lang['smallads.default.type'] = 'Type de test';
$lang['smallads.root.category.description'] = '
    Bienvenue dans le module Petites Annonces du site !
    <br /><br />
    Une catégorie et une annonce ont été créées pour vous montrer comment fonctionne ce module. Voici quelques conseils pour bien débuter sur ce module.
    <br /><br />
    <ul class="formatter-ul">
    <li class="formatter-li"> Pour configurer ou personnaliser votre module, rendez-vous dans la <a href="' . SmalladsUrlBuilder::categories_configuration()->relative() . '">configuration des catégories</a></li>
    <li class="formatter-li"> Pour configurer ou personnaliser les annonces et filtres d\'affichage, rendez-vous dans la <a href="' . SmalladsUrlBuilder::items_configuration()->relative() . '">configuration des annonces</a></li>
    <li class="formatter-li"> Pour configurer ou personnaliser les conditions générales d\'utilisation, rendez-vous dans la <a href="' . SmalladsUrlBuilder::usage_terms_configuration()->relative() . '">configuration des CGU</a></li>
    <li class="formatter-li"> Pour créer des catégories, <a href="' . CategoriesUrlBuilder::add('smallads')->relative() . '">cliquez ici</a> </li>
    <li class="formatter-li"> Pour ajouter des annonces, <a href="' . SmalladsUrlBuilder::add_item()->relative() . '">cliquez ici</a></li>
    </ul>
    <br />Pour en savoir plus, n\'hésitez pas à consulter la documentation du module sur le site de <a href="https://www.phpboost.com">PHPBoost</a>.
';

// Items configuration
$lang['config.items.title']                      = 'Configuration des annonces';
$lang['config.currency']                         = 'Devise';
$lang['smallads.type.add']                       = 'Ajouter des types d\'annonce';
$lang['smallads.type.placeholder']               = 'Vente, achat, location ...';
$lang['smallads.brand.add']                      = 'Ajouter des marques';
$lang['smallads.brand.placeholder']              = 'Nom de la marque';
$lang['config.location']                         = 'Activer la localisation';
$lang['config.max.weeks.number.displayed']       = 'Limiter le nombre de semaines d\'affichage';
$lang['config.max.weeks.number']                 = 'Nombre de semaines d\'affichage';
$lang['config.display.delay.before.delete']      = 'Délai d\'affichage avant suppression';
$lang['config.display.delay.before.delete.desc'] = 'lorsque la case à cocher "annonce terminée" est activée (en jours).';
$lang['config.display.contact.to.visitors']      = 'Autoriser les visiteurs à contacter les auteurs d\'annonces';
$lang['config.display.contact.to.visitors.desc'] = 'Si non coché, seuls les membres connectés peuvent contacter les auteurs d\'annonces.';
$lang['config.display.email.enabled']            = 'Autoriser le lien vers l\'email de l\'auteur';
$lang['config.display.pm.enabled']               = 'Autoriser le lien vers la messagerie privée de l\'auteur';
$lang['config.display.phone.enabled']            = 'Autoriser l\'affichage du numéro de téléphone de l\'auteur';
$lang['config.suggestions.display']              = 'Afficher les suggestions d\'annonces';
$lang['config.suggestions.nb']                   = 'Nombre d\'annonces suggérées à afficher';
$lang['config.related.links.display']            = 'Afficher les liens associés aux annonces';
$lang['config.related.links.display.desc']       = 'Lien précédent, lien suivant.';

// Mini module configuration
$lang['config.mini.title']           = 'Configuration du mini menu';
$lang['config.mini.items.nb']        = 'Nombre d\'annonces à afficher dans le mini menu';
$lang['config.mini.speed.desc']      = 'en millisecondes.';
$lang['config.mini.animation.speed'] = 'Vitesse de défilement';
$lang['config.mini.autoplay']        = 'Autoriser le défilement automatique';
$lang['config.mini.autoplay.speed']  = 'Temps entre chaque défilement';
$lang['config.mini.autoplay.hover']  = 'Autoriser la pause au survol du carrousel';

// Usage Terms Conditions
$lang['config.usage.terms']           = 'Gestion des CGU';
$lang['smallads.usage.terms']         = 'Conditions générales d\'utilisation';
$lang['config.usage.terms.displayed'] = 'Afficher les CGU';
$lang['config.usage.terms.desc']      = 'Description des CGU';

// Form
$lang['smallads.form.add']                                     = 'Ajouter une annonce';
$lang['smallads.form.edit']                                    = 'Modifier une annonce';
$lang['smallads.form.summary']                                 = 'Description (maximum :number caractères)';
$lang['smallads.form.enabled.summary']                         = 'Activer le condensé de l\'annonce';
$lang['smallads.form.enabled.summary.description']             = 'ou laissez PHPBoost couper le contenu à :number caractères.';
$lang['smallads.form.price']                                   = 'Prix';
$lang['smallads.form.price.desc']                              = 'Laisser à 0 pour ne pas afficher le prix.<br />Utiliser une virgule pour les décimales.';
$lang['smallads.form.thumbnail']                               = 'Vignette de l\'annonce';
$lang['smallads.form.thumbnail.desc']                          = 'Accompagne l\'annonce sur l\'ensemble du site.';
$lang['smallads.form.carousel']                                = 'Ajouter un carrousel d\'images';
$lang['smallads.form.image.description']                       = 'Description';
$lang['smallads.form.image.url']                               = 'Adresse de l\'image';
$lang['smallads.form.contact']                                 = 'Coordonnées de contact';
$lang['smallads.form.max.weeks']                               = 'Nombre de semaines d\'affichage';
$lang['smallads.form.max.weeks.description']                   = 'Passé ce délai, l\'annonce sera dépubliée et archivée.';
$lang['smallads.form.displayed.author.pm']                     = 'Afficher le lien vers la messagerie privée';
$lang['smallads.form.displayed.author.email']                  = 'Afficher le lien vers l\'email';
$lang['smallads.form.enabled.author.email.customization']      = 'Personnaliser l\'email';
$lang['smallads.form.enabled.author.email.customization.desc'] = 'si vous voulez être contacté sur un autre email que celui de votre compte.';
$lang['smallads.form.custom.author.email']                     = 'Email de contact';
$lang['smallads.form.displayed.author.phone']                  = 'Afficher le numéro de téléphone';
$lang['smallads.form.author.phone']                            = 'Numéro de téléphone';
$lang['smallads.form.enabled.author.name.customization']       = 'Personnaliser le nom de l\'auteur';
$lang['smallads.form.custom.author.name']                      = 'Nom de l\'auteur personnalisé';
$lang['smallads.form.completed.ad']                            = 'Annonce terminée';
$lang['smallads.form.completed']                               = 'Déclarer cette annonce terminée';
$lang['smallads.form.completed.warning']                       = 'L\'annonce sera supprimée après :delay jours<br /><span style="color:var(--error-tone)">Cette action est irréversible</span>';

$lang['smallads.form.smallad.type']                = 'Type d\'annonce';
$lang['smallads.form.smallads.types']              = 'Types d\'annonces';
$lang['smallads.form.member.edition']              = 'Modification par l\'auteur';
$lang['smallads.form.member.contribution.explain'] = 'Votre contribution suivra le parcours classique et sera traitée dans le panneau de contribution. La modification est possible à tout moment, tant qu\'elle est en attente d\'approbation, mais aussi lorsqu\'elle sera publiée. Vous pouvez, dans le champ suivant, justifier votre contribution de façon à expliquer votre démarche à un approbateur.';
$lang['smallads.form.member.edition.explain']      = 'Vous êtes sur le point de modifier votre annonce. Elle va être déplacée dans les annonces en attente afin d\'être traitée et une nouvelle alerte sera envoyée à un administrateur.';
$lang['smallads.form.member.edition.summary']      = 'Complément de modification';
$lang['smallads.form.member.edition.summary.desc'] = 'Expliquez ce que vous avez modifié pour un meilleur traitement d\'approbation.';

// S.E.O.
$lang['smallads.seo.description.root']        = 'Toutes les annonces du site :site.';
$lang['smallads.seo.description.archived']    = 'Toutes les annonces archivées du site :site.';
$lang['smallads.seo.description.tag']         = 'Toutes les annonces sur le sujet :subject.';
$lang['smallads.seo.description.pending']     = 'Toutes les annonces en attente.';
$lang['smallads.seo.description.member']      = 'Toutes les annonces de :author.';
$lang['smallads.seo.description.usage.terms'] = 'Conditions générales d\'utilisation des annonces du site :site.';

// Messages helper
$lang['smallads.message.success.add']    = 'L\'annonce <b> :title</b> a été ajoutée';
$lang['smallads.message.success.edit']   = 'L\'annonce <b> :title</b> a été modifiée';
$lang['smallads.message.success.delete'] = 'L\'annonce <b> :title</b> a été supprimée';
$lang['smallads.no.type']                = '<div class="warning">Vous devez déclarer les types d\'annonces (Vente, Achat, ...) dans la <a class="offload" href="'. PATH_TO_ROOT . SmalladsUrlBuilder::items_configuration()->relative() . '">configuration des annonces</a></div>';
$lang['smallads.all.types.filters']      = 'Toutes';

// Contact
$lang['smallads.tel.modal']             = 'Vous devez être connecté pour voir le numéro de téléphone';
$lang['smallads.email.modal']           = 'Vous devez être connecté pour contacter l\'auteur de cette annonce';
$lang['smallads.message.success.email'] = 'Votre message a bien été envoyé';
$lang['smallads.message.error.email']   = 'Une erreur est survenue lors de l\'envoi de votre message';
$lang['email.smallad.contact']          = 'Contacter l\'auteur de l\'annonce';
$lang['email.smallad.title']            = 'Vous êtes intéressé par l\'annonce :';
$lang['email.sender.name']              = 'Votre nom :';
$lang['email.sender.email']             = 'Votre adresse email :';
$lang['email.sender.message']           = 'Votre message :';

// Mini module
$lang['smallads.mini.last.smallads']    = 'Dernières annonces';
$lang['smallads.mini.no.smallad']       = 'Aucune annonce disponible';
$lang['smallads.mini.there.is']         = 'Il y a';
$lang['smallads.mini.there.are']        = 'Il y a';
$lang['smallads.mini.one.smallad']      = 'annonce sur le site';
$lang['smallads.mini.several.smallads'] = 'annonces sur le site';

// Accessibility
$lang['open.modal']  = 'Ouverture dans une nouvelle fenêtre';
$lang['close.modal'] = 'Fermeture de la fenêtre';
?>
