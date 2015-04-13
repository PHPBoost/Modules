<?php
/*##################################################
 *                              dictionary.php
 *                            -------------------
 *   begin                : March  3, 2009 
 *   copyright            : (C) 2009 Nicolas Maurel
 *   email                :  crunchfamily@free.fr
 *
 *  
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

####################################################
# French                                           #
####################################################

$LANG['dictionary'] = "Dictionnaire";
$LANG['dictionary_contribution_legend'] = "Pr�senter une contribution";
$LANG['dictionary_contribution_notice'] = "Vous n\'�tes pas autoris� � ajouter un mot au lexique, cependant vous pouvez en proposer une. Votre contribution suivra le parcours classique et sera trait�e dans le panneau de contribution de PHPBoost. Vous pouvez, dans le champ suivant, justifier votre contribution de fa�on � expliquer votre d�marche � un approbateur.";
$LANG['dictionary_contribution_counterpart'] = 'Compl�ment de contribution';
$LANG['dictionary_contribution_counterpart_explain'] = 'Expliquez les raisons de votre contribution. Ce champ est facultatif.';
$LANG['dictionary_contribution_entitled'] = 'Un mot a �t� propos�e : %d';
$LANG['contribution_confirmation'] = 'Confirmation de contribution';
$LANG['contribution_confirmation_explain'] = '<p>Vous pourrez la suivre dans le <a href="' . url('../member/contribution_panel.php') . '">panneau de contribution de PHPBoost</a> et �ventuellement discuter avec les validateurs si leur choix n\'est pas franc.</p><p>Merci d\'avoir particip� � la vie du site !</p>';
$LANG['contribution_success'] = 'Votre contribution a bien �t� enregistr�e.';
$LANG['require_text_desc'] = 'Vous devez rentrer une d�finition';
$LANG['require_text_word'] = 'Vous devez remplir le champs mots';
$LANG['delete_dictionary_conf'] = '�tes vous sur de vouloir supprimer cette d�finition ?';
$LANG['alert_del'] = '�tes-vous sur de vouloir supprimer cette cat�gorie ?';
$LANG['gestion_cat'] = 'Gestion des cat�gories';
$LANG['configuration'] = 'Configuration';
$LANG['auth'] = 'Authorisations';
$LANG['all'] = 'TOUS';
$LANG['all_cat'] = 'TOUTES';
$LANG['create_dictionary'] = 'Ajout';
$LANG['dictionary_contents'] = 'D�finition';
$LANG['dictionary_word'] = 'Mots';
$LANG['submit'] = 'Valider';
$LANG['previs'] = 'Pr�visualiser';
$LANG['validation'] = 'Validation';
$LANG['modify'] = 'Modifier';
$LANG['previsualisation'] = 'Pr�visualisation';
$LANG['dictionary_config'] = 'Configuration';
$LANG['create_dictionary'] = 'Ajouter un mot';
$LANG['update_dictionary'] = 'Modifier un mot<br />et approuver les contributions';
$LANG['delete_dictionary'] = 'Supprimer un mot';
$LANG['list_dictionary']   = 'Lister les mots';
$LANG['admin.words.manage'] = 'G�rer les mots';
$LANG['contrib_dictionary'] = 'Poster un mot en contribution';
$LANG['dictionary_forbidden_tags'] = 'Balises interdites';
$LANG['pagination_nb'] = 'Nombre de mots par page';
$LANG['dictionary_search_where'] = 'Rechercher dans ?';
$LANG['dictionary_author'] = 'mots';
$LANG['dictionary_contents'] = 'D�finition';
$LANG['name_cat'] = 'Nom de la cat�gorie';
$LANG['category'] = 'Cat�gorie';
$LANG['dictionary_cats'] = 'Gestion des cat�gories';
$LANG['admin.categories.manage'] = 'G�rer les cat�gories';
$LANG['dictionary_cats_add'] = 'Ajouter une cat�gorie';
$LANG['del_cat'] = "Suppression d'une cat�gorie";
$LANG['del_text'] = "Vous �tes sur le point de supprimer la cat�gorie. Deux solutions s'offrent � vous. Vous pouvez soit d�placer l'ensemble de son contenu dans une autre cat�gorie soit supprimer l'ensemble de son contenu. ";
$LANG['del_cat_def'] = 'Supprimer la cat�gorie et tout son contenu';
$LANG['move'] = 'D�placer son contenu dans ';
$LANG['del'] = 'Supprimer';
$LANG['warning_del'] = 'Attention, cette action est irr�versible !';
$LANG['image'] = 'Image';
$LANG['image_a'] = 'Image actuelle';
$LANG['image_up'] = 'Uploader image';
$LANG['weight_max'] = 'Poids maximum';
$LANG['height_max'] = 'Hauteur maximale';
$LANG['width_max'] = 'Largeur maximale';
$LANG['image_up_one'] = 'Uploader une image';
$LANG['image_server'] = 'Image directement h�berg�e sur le serveur';
$LANG['image_link'] = 'Lien image';
$LANG['image_adr'] = "Adresse directe de l'image";
$LANG['e_upload_max_dimension'] = "Taille de l'image sup�rieur � la limite. Poids maximum : 20 Ko. Largeur maximum : 16px. Hauteur maximum : 16px; ";
$LANG['del_cat'] = "Vous ne pouvez pas supprimer cette cat�gorie, car c'est la derni�re pr�sente.";
$LANG['del_word'] = "Vous ne pouvez pas supprimer cette d�finition, car c'est la derni�re pr�sente.";
$LANG['value_incorrect' ]= 'Valeur incorrecte';
$LANG['word_exist'] = 'Le mots que vous voulez ajouter existe d�j�.';
$LANG['word_exist_contrib']  = "Le mots que vous voulez ajouter existe d�j� mais il est en attente d'acceptation dans les contributions.";
$LANG['random_def'] = "D�finition al�atoire";
$LANG['no_script'] = "Votre navigateur ne prend pas en charge le Javascript, vous ne pourrez donc pas profiter de toutes les fonctionnalit�s de ce module.";
$LANG['xml_title']  = "Derni�re d�finition";
$LANG['nb_def'] = "Nombre de d�finition(s)";
$LANG['def_set']= "d�finition(s) r�partis en";
$LANG['cat_s'] = "Cat�gorie(s)";
$LANG['list_def'] = "Liste des d�finitions";
$LANG['list'] = "Liste";
$LANG['date'] = "Date";
$LANG['approbation'] = "Approbation";
$LANG['yes'] = "OUI";
$LANG['no'] = "NON";
$LANG['error_upload_img']="Erreur inconnu, v�rifier que vous ayez les droits sur votre r�pertoire d'upload (./dictionary/templates/images/)";
?>