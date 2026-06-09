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
#                       French                      #
#####################################################

$lang['categories'] = $lang['items'] = [];

$lang['categories'][] = [
	'category.name'        => 'Catégorie de test',
	'category.description' => 'Elements basiques de démonstration'
];

$lang['items'][] = [
	'item.title'   => 'Critique du site',
	'item.additional_fields.question' => 'Comment trouvez-vous notre site ?',
	'item.additional_fields.answers_type' => 1,
	'item.additional_fields.answers' => TextHelper::serialize([
		'Supersite'    => ['is_default' => false, 'title' => 'Super site'],
		'Pasmal'       => ['is_default' => false, 'title' => 'Pas mal'],
		'Plutôtmoyen'  => ['is_default' => false, 'title' => 'Plutôt moyen'],
		'Bof'          => ['is_default' => false, 'title' => 'Bof']
	]),
	'item.additional_fields.votes' => TextHelper::serialize(['Super site' => 15, 'Pas mal' => 3, 'Plutôt moyen' => 6, 'Bof' => 0]),
	'item.additional_fields.votes_number' => 24,
	'item.additional_fields.close_poll' => 0,
	'item.additional_fields.countdown_display' => 0,

	'item.content' => '',
	'item.summary' => ''
];
?>
