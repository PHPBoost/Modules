<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 4.0 - 2013 08 20
*/

class CLIServerStatusCheckServersStatusCommand implements CLICommand
{
	public function short_description(): string
	{
		return 'Checks the servers status.';
	}

	public function help(array $args): void
	{
		CLIOutput::writeln('Checks the servers status. Try to get game servers informations if they are online.');
	}

	public function execute(array $args): void
	{
		ServerStatusService::check_servers_status(true);
		CLIOutput::writeln('The servers status have successfully been updated');
	}
}
?>
