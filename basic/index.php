<?php
/**
 * @copyright   &copy; 2005-2021 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@PHPBoost.com>
 * @version     PHPBoost 6.0 - last update: 2021 03 08
 * @since       PHPBoost 6.0 - 2020 05 14
*/

define('PATH_TO_ROOT', '..');

require_once PATH_TO_ROOT . '/kernel/init.php';

ModuleDispatchManager::dispatch();
?>
