<?php
/*##################################################
 *                      common.php
 *                            -------------------
 *   begin                : January 2, 2016
 *   copyright            : (C) 2016 Sebastien LARTIGUE - Julien BRISWALTER
 *   email                : babsolune@phpboost.com - j1.seth@phpboost.com
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

/**
 * @author Sebastien Lartigue <babsolune@phpboost.com>
 * @author Julien Briswalter <j1.seth@phpboost.com>
 */


 //  Sticky
 $lang['homelanding.sticky'] = 'Free Text';
 $lang['homelanding.sticky.title'] = 'Free Text';
 $lang['homelanding.sticky.title.name'] = 'Title of the page';
 $lang['homelanding.sticky.manage'] = 'Modify';
 $lang['homelanding.sticky.content.label'] = 'Content of the page';
 $lang['homelanding.sticky.content'] = 'This page allows you to display a free text. You can modify title and content in the <a href="' . HomeLandingUrlBuilder::sticky_manage()->relative() . '">administration</a>';
?>
