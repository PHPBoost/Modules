<?php
/**
 * @copyright   &copy; 2005-2025 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 10 25
 * @since       PHPBoost 6.0 - 2022 10 25
 */

require_once('../kernel/init.php');

define('TITLE', 'Broadcast Player');

$env = new SiteDisplayFrameGraphicalEnvironment();
Environment::set_graphical_environment($env);

$view = new FileTemplate('broadcast/BroadcastPlayer.tpl');
$config = BroadcastConfig::load();

$view->put_all(array(
    'C_HAS_LOGO'   => !empty($config->get_broadcast_logo()),
    'C_IS_WIDGET'  => $config->get_player_type() == BroadcastConfig::BROADCAST_WIDGET,
    'C_HAS_WIDGET' => $config->get_player_type() == BroadcastConfig::BROADCAST_COMBO,

    'WIDGET' => FormatingHelper::second_parse($config->get_broadcast_widget()),
    'TITLE'  => $config->get_broadcast_name(),

    'U_STREAM' => $config->get_broadcast_url()->rel(),
    'U_LOGO'   => $config->get_broadcast_logo()->rel()
));
$view->display();

require_once('../kernel/footer_no_display.php');
?>
