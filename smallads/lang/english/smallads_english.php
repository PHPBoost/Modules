<?php
/**
 * smallads_french.php
 *
 * @author         alain91
 * @copyright      (C) 2009-2010 Alain Gandon
 * @ email         alain091@gmail.com              
 * @license        GPL
 */

$LANG['sa_rank_post'] 	= 'Level to post';

$LANG['sa_title'] 			= 'Small Ads';
$LANG['sa_title_all'] 		= 'All small ads';
$LANG['sa_more_contents'] 	= '[Other...]';
$LANG['sa_mini_info']		= '%d smallads from %s';

$LANG['sa_require_float']	= 'Enter a float';
$LANG['sa_require_upload']	= 'The only image format files supported are : gif, png ou jpeg';
$LANG['sa_no_smallads']		= 'No small ads found';
$LANG['sa_edit_success']	= 'Modifications saved';
$LANG['sa_confirm_delete']	= 'Confirm deletion';
$LANG['sa_confirm_delete_picture']	= 'Confirm picture deletion';
$LANG['sa_created']			= 'Created on : ';
$LANG['sa_updated']			= 'Updated on : ';
$LANG['sa_list_not_approved'] = 'List PA not approved';

$LANG['sa_db_type'] 		= 'Type';
$LANG['sa_db_title'] 		= 'Title';
$LANG['sa_db_contents'] 	= 'Description';
$LANG['sa_db_price'] 		= 'Price';
$LANG['sa_db_shipping'] 	= 'Shipping';
$LANG['sa_db_approved'] 	= 'Small ads approved';
$LANG['sa_db_picture']		= 'Picture';
$LANG['sa_db_max_weeks']	= 'Photo associée';

$LANG['sa_group_all'] 		= 'Tout';
$LANG['sa_group_1']			= 'Vend';
$LANG['sa_group_2']			= 'Achète';
$LANG['sa_group_3'] 		= 'Echange';
$LANG['sa_group_4'] 		= ''; // empty if end of list
$LANG['sa_group_5'] 		= '';
$LANG['sa_group_6'] 		= '';
$LANG['sa_group_7'] 		= '';
$LANG['sa_group_8'] 		= '';
$LANG['sa_group_9'] 		= '';

$LANG['sa_price_unit']		= '&euro;';
$LANG['sa_shipping_unit']	= '&euro;';

$LANG['sa_search_where'] 	= 'Search in ?';
$LANG['sa_add_legend']		= 'Add small ads';
$LANG['sa_update_legend']	= 'Modify small ads';
$LANG['sa_view_legend']		= 'Voir une petite annonce';

$LANG['sa_max_picture_weight'] = 'Max : ' . SmalladsConfig::MAX_PICTURE_WEIGHT . 'Ko';
$LANG['sa_max_weeks']		= 'Number of weeks to display';
$LANG['sa_max_weeks_default'] = '(%d weeks if empty)';

$LANG['sa_auth_message']	= 'Except identifed cases, Allow authorization for guests will be ignored';
$LANG['sa_own_crud']		= 'Add, Update or Delete owned small ads';
$LANG['sa_create'] 			= 'Add small ad';
$LANG['sa_update'] 			= 'Modify small ads<br />or approve small ads';
$LANG['sa_delete'] 			= 'Delete small ads';
$LANG['sa_list']   			= 'List small ads (guests allowed)';
$LANG['sa_contrib'] 		= 'Add, Update or Delete contributions';

$LANG['sa_contribution_legend'] 				= 'Introduce your contribution';
$LANG['sa_contribution_notice'] 				= 'You are not authorized to create small ads, but you can suggest a proposal. Your contribution may or may not be approved.';
$LANG['sa_contribution_counterpart'] 			= 'Complément de contribution';
$LANG['sa_contribution_counterpart_explain'] 	= 'Explain motivations of your contribution. this is an optional field.';
$LANG['sa_contribution_confirmation'] 			= 'Thank you for your contribution';
$LANG['sa_contribution_confirmation_explain'] 	= '<p>You may follow the process oh this contribution in <a href="' . url('../user/contribution_panel.php') . '">contribution panel of PHPBoost</a> and, if you would, argue with validators.</p><p>Thank you !</p>';
$LANG['sa_contribution_success'] 				= 'Your contribution have been saved.';

$LANG['sa_sort_title']	= 'Title';
$LANG['sa_sort_date']	= 'Date';
$LANG['sa_sort_price']	= 'Price';

$LANG['sa_mode_asc']	= 'Ascending';
$LANG['sa_mode_desc']	= 'Descendind';

$LANG['sa_error_picture_weight']= 'Picture weight is too high, please reduce it before uploading (' . SmalladsConfig::MAX_PICTURE_WEIGHT . 'Ko max)';
$LANG['sa_error_upload']		= 'Error during picture upload';
$LANG['sa_unsupported_format']	= 'File format not supported';
$LANG['sa_unabled_create_pics']	= 'Unable to create picture';
$LANG['sa_error_resize']		= 'Error in function resize';
$LANG['sa_error_resample']		= 'Error in function resample';
$LANG['sa_no_gd']				= 'Extension gd not found';
$LANG['sa_no_getimagesize']		= 'Function getimagsize not found';

$LANG['sa_contrib_in_progress']	= 'Contribution been processed. Retry later';
$LANG['sa_not_approved']		= 'Not approved yet';

$LANG['sa_xml_desc'] 			= 'Suscribe to last Smallads on ';

$LANG['sa_usage_legend']		= 'General usage terms';
$LANG['sa_agree_terms']			= 'I agree with the general usage terms';

$LANG['sa_e_cgu_invalid']		= 'You choose to display general usage terms but the content is empty';
$LANG['sa_e_cgu_file_invalid']	= 'Acces problem with general usage terms file';
$LANG['sa_e_cgu_not_agreed']	= 'yau have not agreed general usage terms';
?>