<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2016 02 11
 * @since       PHPBoost 4.0 - 2013 08 20
*/

class CLIServerStatusCheckServersStatusCommand implements CLICommand
{
	public function short_description()
	{
		return 'Checks the servers status.';
	}

	public function help(array $args)
	{
		CLIOutput::writeln('Checks the servers status. Try to get game servers informations if they are online.');
	}

	public function execute(array $args)
	{
		ServerStatusService::check_servers_status(true);
		CLIOutput::writeln('The servers status have successfully been updated');
	}
}
?>
