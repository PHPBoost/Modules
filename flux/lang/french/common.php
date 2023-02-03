<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2023 02 02
 * @since       PHPBoost 6.0 - 2021 10 30
*/

####################################################
#						French					   #
####################################################

$lang['flux.module.title']     = 'Flux RSS';
$lang['flux.last.feeds.title'] = 'Les :feeds_number éléments de flux les plus récents';
$lang['flux.no.last.feeds']    = 'Aucun flux n\'a été initialisé.';
$lang['flux.words.not.read']   = 'Mots restant à lire';

$lang['item']  = 'flux';
$lang['items'] = 'flux';

$lang['flux.member.items']  = 'Flux publiés par';
$lang['flux.my.items']      = 'Mes flux';
$lang['flux.pending.items'] = 'Flux en attente';
$lang['flux.items.number']  = 'Nombre de flux';
$lang['flux.filter.items']  = 'Filtrer les flux';

$lang['flux.add']        = 'Ajouter un flux';
$lang['flux.edit']       = 'Modifier un flux';
$lang['flux.management'] = 'Gestion des flux';

$lang['flux.website.infos']         = 'Infos sur le site';
$lang['flux.website.xml']           = 'Url du fichier xml';
$lang['flux.empty.xml.file']        = 'Le fichier xml a été mis en cache mais aucun flux n\'a été trouvé. Vérifiez l\'adresse du flux Rss renseigné.';
$lang['flux.rss.init']              = 'Des flux Rss ont été trouvés. Il doivent être mis en cache pour être accessibles, en cliquant sur le bouton de mise à jour ci-dessous.';
$lang['flux.rss.init.admin']        = 'L\'affichage des nouveaux éléments issus des flux du site est mis à jour en cliquant sur le bouton.';
$lang['flux.rss.init.contribution'] = 'L\'affichage des nouveaux éléments sera accessible quand la contribution sera validée.';
$lang['flux.wrong.rss.init']        = 'Le fichier trouvé est un fichier xml mais pas un fichier rss. Vérifiez l\'adresse des rss du site';
$lang['flux.check.updates']         = 'Vérifier les nouveaux sujets sur le site';
$lang['flux.update']                = 'Mettre à jour';

// Configuration
$lang['flux.module.name']               = 'Titre du module';
$lang['flux.rss.number']                = 'Nombre d\'éléments de flux par site';
$lang['flux.display.last.feeds']        = 'Afficher les éléments récents de flux sur l\'accueil';
$lang['flux.last.feeds.number']         = 'Nombre d\'éléments de flux à afficher sur l\'accueil';
$lang['flux.characters.number.to.cut']  = 'Nombre de caractères pour couper l\'élément d\'un flux';
$lang['flux.update.all']                = 'Mettre à jour le cache';
$lang['flux.success.update']            = 'Tous les flux ont bien été mis à jour';
$lang['flux.update.clue']               = 'Cette action permet de mettre à jour tous les flux <strong>déclarés et initialisés</strong> et de supprimer tous les fichiers en cache non utilisés.';
$lang['flux.root.category.description'] = '
    <p>Bienvenue dans l\'espace du site consacré aux Flux Rss !</p>
    <p>Une catégorie et un flux ont été créés pour vous montrer comment fonctionne ce module. Voici quelques conseils pour bien débuter sur ce module.</p>
    <ul class="formatter-ul">
        <li class="formatter-li"> Pour configurer ou personnaliser l\'accueil de votre module, rendez vous dans l\'<a class="offload" href="' . Url::to_rel(FluxUrlBuilder::configuration('flux')) . '">administration du module</a></li>
        <li class="formatter-li"> Pour créer des catégories, <a class="offload" href="' . Url::to_rel(CategoriesUrlBuilder::add(Category::ROOT_CATEGORY, 'flux')) . '">cliquez ici</a> </li>
        <li class="formatter-li"> Pour ajouter des flux, <a class="offload" href="' . Url::to_rel(FluxUrlBuilder::add(Category::ROOT_CATEGORY, 'flux')) . '">cliquez ici</a></li>
    </ul>
    <p>Pour en savoir plus, n\'hésitez pas à consulter la documentation du module sur le site de <a class="offload" href="https://www.phpboost.com">PHPBoost</a>.</p>
';

// S.E.O.
$lang['flux.seo.description.member'] = 'Toutes les flux publiés par :author.';
$lang['flux.seo.description.pending'] = 'Toutes les flux en attente.';

// Messages
$lang['flux.message.success.add']    = 'Le flux <b>:name</b> a été ajouté';
$lang['flux.message.success.edit']   = 'Le flux <b>:name</b> a été modifié';
$lang['flux.message.success.delete'] = 'Le flux <b>:name</b> a été supprimé';
?>
