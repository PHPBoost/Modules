<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 5.2 - 2020 06 15
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
*/

####################################################
#                       French                     #
####################################################

$lang['categories'] = $lang['items'] = [];

$lang['categories'][] = [
	'category.name'        => 'Test',
	'category.description' => 'Catégorie de test'
];

$lang['items'][] = [

    'item.title' => 'Mentions légales',
    'item.content' => '
        <h2 class="formatter-title">Responsable du site</h2>
        <br />
        Responsable de Publication : <a class="offload" href="/#">[NomDuResponsable]</a>
        <br />
        Création et développement : ' . GeneralConfig::load()->get_site_name() . '
        <br />
        Pour toute demande, adressez-vous à :
        <br />
        email: [LienVersEmail/FormulaireDeContact]
        <br /><br />
        <h2 class="formatter-title">Droit de propriété</h2>
        <br />
        Tous les textes, photos et illustrations présents sur ce site sont la propriété de ' . GeneralConfig::load()->get_site_name() . ' et ses partenaires contractuels.
        Toute représentation, reproduction intégrale ou partielle faite sans le consentement du propriétaire est illicite.
        Si vous souhaitez reproduire des photos ou documents contenus dans ce site, uniquement à des fins non lucratives, vous devez avant toute diffusion obtenir l\'autorisation écrite préalable à ' . GeneralConfig::load()->get_site_name() . '.
        Pour cela : adressez une demande écrite par e-mail à ' . GeneralConfig::load()->get_site_name() . ' en précisant le contenu exact de ce que vous souhaitez reproduire.
        Après accord, il vous sera demandé de citer précisément les sources. Si vous êtes journaliste et que vous souhaitez obtenir une photo en haute définition pour une parution presse, adressez nous une demande par ce même canal: [LienVersEmail/FormulaireDeContact]
        <br /><br />
        <h2 class="formatter-title">Hébergement du site</h2>
        <br />
        [AdressePostaleDeLHebergeur]
        <br />
        <h2 class="formatter-title">Applications tierces</h2>
        <br />
        Des scripts php et javascripts sont intégrés à ce site dans le respect des droits concédés par leurs auteurs. Les mentions prévues par ceux-ci sont visibles dans les codes sources des pages.<br />
        <br />
        <h2 class="formatter-title">Protection des données personnelles</h2>
        <br />
        Le site ne récolte aucune donnée personnelle
        <br /><br />
        <h2 class="formatter-title">Cookies</h2>
        <br />
        Lors de la navigation dans certains endroits du site, des cookies peuvent être importés sur votre ordinateur.
        Ceux-ci ne permettent pas de vous identifier, mais ils enregistrent des informations relatives à la navigation que le programme pourra lire lors de vos visites ultérieures.
        Vous pouvez vous opposer à l\'enregistrement de cookies en configurant votre navigateur internet.
        <br />
        <h2 class="formatter-title">Liens vers ce site</h2>
        <br />
        Les liens vers ce site sont bienvenus aux conditions suivantes :
        <br />
        <ul class="formatter-ul">
            <li class="formatter-li">Respect de la réciprocité.</li>
            <li class="formatter-li">Lien sur une page de votre site indexée par les moteurs de recherche.</li>
            <li class="formatter-li">Ouverture intégrale de la page <a class="offload" href="/">' . GeneralConfig::load()->get_site_name() . '</a> dans un navigateur, sans cadre ni mentions propres à votre site.</li>
        </ul>
        <br /><br />
        <h2 class="formatter-title">Crédits</h2><br />
        Les contenus intégrés dans ce site reste la propriété intellectuelle de leurs auteurs respectifs :
        <br />
        <ul class="formatter-ul">
            <li class="formatter-li">Crédits photos : ' . GeneralConfig::load()->get_site_name() . '</li>
            <li class="formatter-li">Crédits illustrations : ' . GeneralConfig::load()->get_site_name() . '</li>
            <li class="formatter-li">Crédits textes : ' . GeneralConfig::load()->get_site_name() . '</li>
            <li class="formatter-li">Pour toute demande de reproduction d\'un contenu : [LienVersEmail/FormulaireDeContact]</li>
        </ul>'
];
?>
