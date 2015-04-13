<?php
/**
 *   quotes_english.php
 *
 *   @author            alain91
 *   @license          	GPL Version 2
 */

global $QUOTES_LANG;
$QUOTES_LANG = array();

//Admin
$QUOTES_LANG['q_config'] = 'Manage quotes';
$QUOTES_LANG['q_status'] = 'Display in mini menu ?';
$QUOTES_LANG['q_management'] = 'Manage quotes';
$QUOTES_LANG['admin.categories.manage'] = 'Manage categories';
$QUOTES_LANG['q_add_category'] = 'Add category';
$QUOTES_LANG['q_cat_management'] = 'Categories management';

//Titre
$QUOTES_LANG['q_title'] = 'Quotes';
$QUOTES_LANG['q_title_all'] = 'All quotes';
$QUOTES_LANG['q_more_contents'] = '[More...]';
$QUOTES_LANG['nbr_quotes_info'] = '%d quote(s) in this category';

$QUOTES_LANG['q_category'] 	= 'Category';
$QUOTES_LANG['q_contents'] 	= 'Quote';
$QUOTES_LANG['q_author'] 	= 'Author';
$QUOTES_LANG['q_in_mini'] 	= 'Display in mini module';
$QUOTES_LANG['q_approved'] 	= 'Quote approved';

$QUOTES_LANG['q_search_where'] 		= 'Search ?';
$QUOTES_LANG['q_no_items'] 			= 'No record found in this category';

$QUOTES_LANG['q_items_per_page'] 	= 'Number of quotes per page';
$QUOTES_LANG['q_mini_list_size'] 	= 'Number of random quotes in the mini module';
$QUOTES_LANG['q_cat_cols'] 			= 'Number of columns';

$QUOTES_LANG['q_create'] 	= 'Add quote';
$QUOTES_LANG['q_update'] 	= 'Update quote<br />or approve quote';
$QUOTES_LANG['q_delete'] 	= 'Delete quote';
$QUOTES_LANG['q_list']   	= 'List quotes';
$QUOTES_LANG['q_contrib']	= 'Post quote as contribution';
$QUOTES_LANG['q_write'] 	= 'Create, Update, Delete or Approve quote';

$QUOTES_LANG['contribution_legend'] = 'Quote introduction';
$QUOTES_LANG['contribution_notice'] = 'You are not allowed to create quote, but you may contribute by posting a post. Your contribution will be submit for approval';
$QUOTES_LANG['contribution_counterpart'] = 'Information on your contribution';
$QUOTES_LANG['contribution_counterpart_explain'] = 'Develop the aim of your contribution (optionnal).';
$QUOTES_LANG['contribution_entitled'] = 'A quote have been submitted : %d';
$QUOTES_LANG['contribution_confirmation'] = 'Contribution confirmation';
$QUOTES_LANG['contribution_confirmation_explain'] = '<p>you can follow the workflow in the <a href="' . url('../member/contribution_panel.php') . '">contribution panel</a></p><p>Thank you for contributing !</p>';
$QUOTES_LANG['contribution_success'] = 'Your contribution has been processed successfully.';

$QUOTES_LANG['auth_read'] = 'Read access';
$QUOTES_LANG['auth_write'] = 'Write access';
$QUOTES_LANG['auth_contribute'] = 'Contribution access';

$QUOTES_LANG['global_auth'] = 'Overall permissions';
$QUOTES_LANG['global_auth_explain'] = 'Here you can define overall permissions of the module. You can change these permissions locally in each category';

$QUOTES_LANG['special_auth'] = 'Special permissions';
$QUOTES_LANG['special_auth_explain'] = 'The category will have the general configuration of the module. You can apply particular permissions.';
$QUOTES_LANG['category_name'] = 'Name';
$QUOTES_LANG['category_location'] = 'Location of the category';
$QUOTES_LANG['icon_cat'] = 'Icon of the category';
$QUOTES_LANG['explain_icon_cat'] = 'You can associate an icone to a category';
$QUOTES_LANG['cat_description'] = 'Description';
$QUOTES_LANG['or_direct_path'] = 'Or direct path';

$QUOTES_LANG['removing_category'] = 'Delete category';
$QUOTES_LANG['explain_removing_category'] = 'Select your choice';
$QUOTES_LANG['delete_category_and_its_content'] = 'Delete category and its content';
$QUOTES_LANG['move_category_content'] = 'Move content in ';

$QUOTES_LANG['successful_operation'] = 'Successful operation';
$QUOTES_LANG['infinite_loop'] = 'Infinite loop';
?>