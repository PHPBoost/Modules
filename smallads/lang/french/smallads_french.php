<?php
/**
 * smallads_french.php
 *
 * @author         alain91
 * @copyright      (C) 2009-2010 Alain Gandon
 * @ email         alain091@gmail.com              
 * @license        GPL
 */

$LANG['sa_rank_post'] 	= 'Niveau pour pouvoir poster';

$LANG['sa_title'] 			= 'Petites annonces';
$LANG['sa_title_all'] 		= 'Toutes les petites annonces';
$LANG['sa_more_contents'] 	= '[Suite...]';
$LANG['sa_mini_info']		= '%d annonce(s) depuis le %s';

$LANG['sa_require_float']	= 'Entrer un nombre à virgule';
$LANG['sa_require_upload']	= 'Les seuls formats gérés de fichier image sont : gif, png ou jpeg';
$LANG['sa_no_smallads']		= 'Aucune petite annonce trouvée';
$LANG['sa_edit_success']	= 'Modifications enregistrées';
$LANG['sa_confirm_delete']	= 'Confirmer la suppression';
$LANG['sa_confirm_delete_picture']	= 'Confirmer la suppression de la photo';
$LANG['sa_created']			= 'Créé le : ';
$LANG['sa_updated']			= 'Modifié le : ';
$LANG['sa_list_not_approved'] = 'Lister PA non approuvées';

$LANG['sa_db_type'] 		= 'Type';
$LANG['sa_db_title'] 		= 'Titre';
$LANG['sa_db_contents'] 	= 'Description';
$LANG['sa_db_price'] 		= 'Prix';
$LANG['sa_db_shipping'] 	= 'Frais de port';
$LANG['sa_db_approved'] 	= 'Approuver la petite annonce';
$LANG['sa_db_picture']		= 'Photo associée';
$LANG['sa_db_max_weeks']	= 'Photo associée';

$LANG['sa_group_all'] 		= 'Tout';
$LANG['sa_group_1']			= 'Vend';
$LANG['sa_group_2']			= 'Achète';
$LANG['sa_group_3'] 		= 'Echange';
$LANG['sa_group_4'] 		= ''; // vide si fin de liste
$LANG['sa_group_5'] 		= '';
$LANG['sa_group_6'] 		= '';
$LANG['sa_group_7'] 		= '';
$LANG['sa_group_8'] 		= '';
$LANG['sa_group_9'] 		= '';

$LANG['sa_price_unit']		= '&euro;';
$LANG['sa_shipping_unit']	= '&euro;';

$LANG['sa_search_where'] 	= 'Rechercher dans ?';
$LANG['sa_add_legend']		= 'Ajouter une petite annonce';
$LANG['sa_update_legend']	= 'Modifier une petite annonce';
$LANG['sa_view_legend']		= 'Voir une petite annonce';

$LANG['sa_max_picture_weight'] = 'Max : ' . SmalladsConfig::MAX_PICTURE_WEIGHT . 'Ko';
$LANG['sa_max_weeks']		= 'Nombre de semaines d\'affichage';
$LANG['sa_max_weeks_default'] = '(%d semaines si laissé vide)';

$LANG['sa_auth_message']	= 'Sauf dans quelques cas signalés, Attribuer des droits sur les visiteurs sera ignoré';
$LANG['sa_create'] 			= 'Ajouter une petite annonce';
$LANG['sa_update'] 			= 'Modifier les petites annonces<br />et approuver les contributions';
$LANG['sa_delete'] 			= 'Supprimer les petites annonces';
$LANG['sa_list']   			= 'Lister les petites annonces (visiteurs permis)';
$LANG['sa_contrib'] 		= 'Ajouter, Modifier, Supprimer des contributions';

$LANG['sa_contribution_legend'] 				= 'Présenter une contribution';
$LANG['sa_contribution_notice'] 				= 'Vous n\'êtes pas autorisé à créer une petite annonce, cependant vous pouvez en proposer une. Votre contribution suivra le parcours classique et sera traitée dans le panneau de contribution de PHPBoost. Vous pouvez, dans le champ suivant, justifier votre contribution de façon à expliquer votre démarche à un approbateur.';
$LANG['sa_contribution_counterpart'] 			= 'Complément de contribution';
$LANG['sa_contribution_counterpart_explain'] 	= 'Expliquez les raisons de votre contribution. Ce champ est facultatif.';
$LANG['sa_contribution_confirmation'] 			= 'Confirmation de contribution';
$LANG['sa_contribution_confirmation_explain'] 	= '<p>Vous pourrez la suivre dans le <a href="' . url('../user/contribution_panel.php') . '">panneau de contribution de PHPBoost</a> et éventuellement discuter avec les validateurs si leur choix n\'est pas franc.</p><p>Merci d\'avoir participé à la vie du site !</p>';
$LANG['sa_contribution_success'] 				= 'Votre contribution a bien été enregistrée.';

$LANG['sa_sort_title']	= 'Titre';
$LANG['sa_sort_date']	= 'Date';
$LANG['sa_sort_price']	= 'Prix';

$LANG['sa_mode_asc']	= 'Croissant';
$LANG['sa_mode_desc']	= 'Décroissant';

$LANG['sa_error_picture_weight']= 'Le poids de l\'image est trop important, veuillez la réduire avant de l\'uploader (' . SmalladsConfig::MAX_PICTURE_WEIGHT . 'Ko max)';
$LANG['sa_error_upload']		= 'Erreur durant chargement photo';
$LANG['sa_unsupported_format']	= 'Format fichier image non supporté';
$LANG['sa_unabled_create_pics']	= 'Impossible de créer les images';
$LANG['sa_error_resize']		= 'Erreur fonction resize';
$LANG['sa_error_resample']		= 'Erreur fonction resample';
$LANG['sa_no_gd']				= 'Il manque l\'extension gd';
$LANG['sa_no_getimagesize']		= 'Fonction getimagsize non trouvée';

$LANG['sa_contrib_in_progress']	= 'Contribution en cours de traitement. Recommencer plus tard';
$LANG['sa_not_approved']		= 'Non approuvée à ce jour';

$LANG['sa_xml_desc'] 			= 'Suivez les dernières Petites Annonces sur ';

$LANG['sa_usage_legend']		= 'Conditions générales d\'utilisation';
$LANG['sa_agree_terms']			= 'J\'accepte les conditions générales d\'utilisation';

$LANG['sa_e_cgu_invalid']		= 'Vous avez sélectionné l\'affichage des conditions générales d\'utilisation mais leur contenu est vide';
$LANG['sa_e_cgu_file_invalid']	= 'Problème d\'accès au fichier des conditions générales';
$LANG['sa_e_cgu_not_agreed']	= 'Vous n\'avez pas accepté les conditions générales';
?>