<?php
/**
 *   quotes_french.php
 *
 *   @author            alain91
 *   @license          	GPL Version 2
 */

global $QUOTES_LANG;
$QUOTES_LANG = array();

//Admin
$QUOTES_LANG['q_config'] = 'Configuration des citations';
$QUOTES_LANG['q_status'] = 'Visible dans menu ?';
$QUOTES_LANG['q_management'] = 'Administrer les citations';
$QUOTES_LANG['admin.categories.manage'] = 'G�rer les cat�gories';
$QUOTES_LANG['q_add_category'] = 'Ajouter une cat�gorie';
$QUOTES_LANG['q_cat_management'] = 'Gestion des cat�gories';

//Titre
$QUOTES_LANG['q_title'] = 'Citation';
$QUOTES_LANG['q_title_all'] = 'Toutes les citations';
$QUOTES_LANG['q_more_contents'] = '[Suite...]';
$QUOTES_LANG['nbr_quotes_info'] = '%d citation(s) dans la cat�gorie';

$QUOTES_LANG['q_category'] 	= 'Cat�gorie';
$QUOTES_LANG['q_contents'] 	= 'Citation';
$QUOTES_LANG['q_author'] 	= 'Auteur';
$QUOTES_LANG['q_in_mini'] 	= 'Apparait dans le mini module';
$QUOTES_LANG['q_approved'] 	= 'Approuver la citation';

$QUOTES_LANG['q_search_where'] 		= 'Rechercher dans ?';
$QUOTES_LANG['q_no_items'] 			= 'Aucun �l�ment dans cette cat�gorie';

$QUOTES_LANG['q_items_per_page'] 	= 'Nombre de citations par page';
$QUOTES_LANG['q_mini_list_size'] 	= 'Nombre de citations al�atoires dans le mini module';
$QUOTES_LANG['q_cat_cols'] 			= 'Nombre de colonnes';

$QUOTES_LANG['q_create'] 	= 'Ajouter une citation';
$QUOTES_LANG['q_update'] 	= 'Modifier une citation<br />ou approuver les contributions';
$QUOTES_LANG['q_delete'] 	= 'Supprimer une citation';
$QUOTES_LANG['q_list']   	= 'Lister les citations';
$QUOTES_LANG['q_contrib']	= 'Poster une citation en contribution';
$QUOTES_LANG['q_write'] 	= 'Cr�er, Modifier, Supprimer et Approuver une citation';

$QUOTES_LANG['contribution_legend'] = 'Pr�senter une contribution';
$QUOTES_LANG['contribution_notice'] = 'Vous n\'�tes pas autoris� � cr�er une citation, cependant vous pouvez en proposer une. Votre contribution suivra le parcours classique et sera trait�e dans le panneau de contribution de PHPBoost. Vous pouvez, dans le champ suivant, justifier votre contribution de fa�on � expliquer votre d�marche � un approbateur.';
$QUOTES_LANG['contribution_counterpart'] = 'Compl�ment de contribution';
$QUOTES_LANG['contribution_counterpart_explain'] = 'Expliquez les raisons de votre contribution. Ce champ est facultatif.';
$QUOTES_LANG['contribution_entitled'] = 'Une citation a �t� propos�e : %d';
$QUOTES_LANG['contribution_confirmation'] = 'Confirmation de contribution';
$QUOTES_LANG['contribution_confirmation_explain'] = '<p>Vous pourrez la suivre dans le <a href="' . url('../member/contribution_panel.php') . '">panneau de contribution de PHPBoost</a> et �ventuellement discuter avec les validateurs si leur choix n\'est pas franc.</p><p>Merci d\'avoir particip� � la vie du site !</p>';
$QUOTES_LANG['contribution_success'] = 'Votre contribution a bien �t� enregistr�e.';

$QUOTES_LANG['auth_read'] = 'Droit de lecture';
$QUOTES_LANG['auth_write'] = 'Droit d\'�criture';
$QUOTES_LANG['auth_contribute'] = 'Droit de contribution';

$QUOTES_LANG['global_auth'] = 'Permissions globales';
$QUOTES_LANG['global_auth_explain'] = 'Vous d�finissez ici les permissions globales du module. Vous pourrez changer ces permissions localement sur chaque cat�gorie.';

$QUOTES_LANG['special_auth'] = 'Permissions sp�ciales';
$QUOTES_LANG['special_auth_explain'] = 'Par d�faut la cat�gorie aura la configuration g�n�rale du module. Vous pouvez lui appliquer des permissions particuli�res.';
$QUOTES_LANG['category_name'] = 'Nom';
$QUOTES_LANG['category_location'] = 'Emplacement de la cat�gorie';
$QUOTES_LANG['icon_cat'] = 'Ic�ne de la cat�gorie';
$QUOTES_LANG['explain_icon_cat'] = 'Vous pouvez associ�e une image � une cat�gorie';
$QUOTES_LANG['cat_description'] = 'Description';
$QUOTES_LANG['or_direct_path'] = 'Ou chemin direct';

$QUOTES_LANG['removing_category'] = 'Supprimer une cat�gorie';
$QUOTES_LANG['explain_removing_category'] = 'Choisir parmi les possibilit�s suivantes';
$QUOTES_LANG['delete_category_and_its_content'] = 'Supprimer la cat�gorie et son contenu';
$QUOTES_LANG['move_category_content'] = 'D�placer le contenu dans ';

$QUOTES_LANG['successful_operation'] = 'Op�ration r�alis�e avec succ�s';
$QUOTES_LANG['infinite_loop'] = 'Boucle infinie';
?>