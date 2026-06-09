<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      xela <xela@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 6.0 - 2020 05 14
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

#####################################################
#                       English                     #
#####################################################

$lang['categories'] = $lang['items'] = [];

$lang['categories'][] = [
	'category.name'        => 'First category',
	'category.description' => 'Elements demonstration'
];

$lang['items'][] = [
	'item.title'   => 'Website review',
	'item.additional_fields.question' => 'Do you like our website?',
	'item.additional_fields.answers_type' => 1,
	'item.additional_fields.answers' => TextHelper::serialize([
		'Amazing'    => ['is_default' => false, 'title' => 'Amazing'],
		'Notsobad'   => ['is_default' => false, 'title' => 'Not so bad'],
		'Mid-level'  => ['is_default' => false, 'title' => 'Mid-level'],
		'Crapy'      => ['is_default' => false, 'title' => 'Crapy']
	]),
	'item.additional_fields.votes' => TextHelper::serialize(['Amazing' => 15, 'Not so bad' => 3, 'Mid-level' => 6, 'Crapy' => 0]),
	'item.additional_fields.votes_number' => 24,
	'item.additional_fields.close_poll' => 0,
	'item.additional_fields.countdown_display' => 0,

	'item.content' => '',
	'item.summary' => ''
];
?>
