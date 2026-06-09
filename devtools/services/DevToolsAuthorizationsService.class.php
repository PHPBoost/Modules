<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      LamPDL
 * @version     PHPBoost 6.0 - last update: 2026 05 19
 * @since       PHPBoost 6.0 - 2026 03 01
 */

class DevToolsAuthorizationsService
{
    /**
     * Returns an authorization checker for the current user.
     * Admins and moderators (r1 and above) are granted access.
     */
    public static function check_authorizations()
    {
        return new DevToolsAuthorizationsChecker();
    }
}

class DevToolsAuthorizationsChecker
{
    private $current_user;

    public function __construct()
    {
        $this->current_user = AppContext::get_current_user();
    }

    /**
     * Read access: admins + moderators (rank >= 1)
     */
    public function read()
    {
        return $this->current_user->get_level() >= User::MODERATOR_LEVEL;
    }

    /**
     * Moderation (install / activate / deactivate / uninstall): same as read for now.
     */
    public function moderation()
    {
        return $this->read();
    }

    /**
     * Admin-only actions (config): administrator level only.
     */
    public function admin()
    {
        return $this->current_user->get_level() >= User::ADMINISTRATOR_LEVEL;
    }
}
?>
