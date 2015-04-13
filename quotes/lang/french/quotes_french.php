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
$QUOTES_LANG['admin.categories.manage'] = 'Gérer les catégories';
$QUOTES_LANG['q_add_category'] = 'Ajouter une catégorie';
$QUOTES_LANG['q_cat_management'] = 'Gestion des catégories';

//Titre
$QUOTES_LANG['q_title'] = 'Citation';
$QUOTES_LANG['q_title_all'] = 'Toutes les citations';
$QUOTES_LANG['q_more_contents'] = '[Suite...]';
$QUOTES_LANG['nbr_quotes_info'] = '%d citation(s) dans la catégorie';

$QUOTES_LANG['q_category'] 	= 'Catégorie';
$QUOTES_LANG['q_contents'] 	= 'Citation';
$QUOTES_LANG['q_author'] 	= 'Auteur';
$QUOTES_LANG['q_in_mini'] 	= 'Apparait dans le mini module';
$QUOTES_LANG['q_approved'] 	= 'Approuver la citation';

$QUOTES_LANG['q_search_where'] 		= 'Rechercher dans ?';
$QUOTES_LANG['q_no_items'] 			= 'Aucun élément dans cette catégorie';

$QUOTES_LANG['q_items_per_page'] 	= 'Nombre de citations par page';
$QUOTES_LANG['q_mini_list_size'] 	= 'Nombre de citations aléatoires dans le mini module';
$QUOTES_LANG['q_cat_cols'] 			= 'Nombre de colonnes';

$QUOTES_LANG['q_create'] 	= 'Ajouter une citation';
$QUOTES_LANG['q_update'] 	= 'Modifier une citation<br />ou approuver les contributions';
$QUOTES_LANG['q_delete'] 	= 'Supprimer une citation';
$QUOTES_LANG['q_list']   	= 'Lister les citations';
$QUOTES_LANG['q_contrib']	= 'Poster une citation en contribution';
$QUOTES_LANG['q_write'] 	= 'Créer, Modifier, Supprimer et Approuver une citation';

$QUOTES_LANG['contribution_legend'] = 'Présenter une contribution';
$QUOTES_LANG['contribution_notice'] = 'Vous n\'êtes pas autorisé à créer une citation, cependant vous pouvez en proposer une. Votre contribution suivra le parcours classique et sera traitée dans le panneau de contribution de PHPBoost. Vous pouvez, dans le champ suivant, justifier votre contribution de façon à expliquer votre démarche à un approbateur.';
$QUOTES_LANG['contribution_counterpart'] = 'Complément de contribution';
$QUOTES_LANG['contribution_counterpart_explain'] = 'Expliquez les raisons de votre contribution. Ce champ est facultatif.';
$QUOTES_LANG['contribution_entitled'] = 'Une citation a été proposée : %d';
$QUOTES_LANG['contribution_confirmation'] = 'Confirmation de contribution';
$QUOTES_LANG['contribution_confirmation_explain'] = '<p>Vous pourrez la suivre dans le <a href="' . url('../member/contribution_panel.php') . '">panneau de contribution de PHPBoost</a> et éventuellement discuter avec les validateurs si leur choix n\'est pas franc.</p><p>Merci d\'avoir participé à la vie du site !</p>';
$QUOTES_LANG['contribution_success'] = 'Votre contribution a bien été enregistrée.';

$QUOTES_LANG['auth_read'] = 'Droit de lecture';
$QUOTES_LANG['auth_write'] = 'Droit d\'écriture';
$QUOTES_LANG['auth_contribute'] = 'Droit de contribution';

$QUOTES_LANG['global_auth'] = 'Permissions globales';
$QUOTES_LANG['global_auth_explain'] = 'Vous définissez ici les permissions globales du module. Vous pourrez changer ces permissions localement sur chaque catégorie.';

$QUOTES_LANG['special_auth'] = 'Permissions spéciales';
$QUOTES_LANG['special_auth_explain'] = 'Par défaut la catégorie aura la configuration générale du module. Vous pouvez lui appliquer des permissions particulières.';
$QUOTES_LANG['category_name'] = 'Nom';
$QUOTES_LANG['category_location'] = 'Emplacement de la catégorie';
$QUOTES_LANG['icon_cat'] = 'Icône de la catégorie';
$QUOTES_LANG['explain_icon_cat'] = 'Vous pouvez associée une image à une catégorie';
$QUOTES_LANG['cat_description'] = 'Description';
$QUOTES_LANG['or_direct_path'] = 'Ou chemin direct';

$QUOTES_LANG['removing_category'] = 'Supprimer une catégorie';
$QUOTES_LANG['explain_removing_category'] = 'Choisir parmi les possibilités suivantes';
$QUOTES_LANG['delete_category_and_its_content'] = 'Supprimer la catégorie et son contenu';
$QUOTES_LANG['move_category_content'] = 'Déplacer le contenu dans ';

$QUOTES_LANG['successful_operation'] = 'Opération réalisée avec succès';
$QUOTES_LANG['infinite_loop'] = 'Boucle infinie';
?>