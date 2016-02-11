<?php
/*##################################################
 *                      CLIServerStatusCheckServersStatusCommand.class.php
 *                            -------------------
 *   begin                : August 20, 2013
 *   copyright            : (C) 2013 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
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
