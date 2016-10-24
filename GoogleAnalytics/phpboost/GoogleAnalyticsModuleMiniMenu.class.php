<?php
/*##################################################
 *                          GoogleAnalyticsModuleMiniMenu.class.php
 *                            -------------------
 *   begin                : December 20, 2012
 *   copyright            : (C) 2012 Kévin MASSY
 *   email                : kevin.massy@phpboost.com
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

class GoogleAnalyticsModuleMiniMenu extends ModuleMiniMenu
{
	public function get_default_block()
	{
		return self::BLOCK_POSITION__BOTTOM_CENTRAL;
	}

	public function default_is_enabled() { return true; }

	public function display($tpl = false)
	{
		$tpl = new FileTemplate('GoogleAnalytics/GoogleAnalyticsModuleMiniMenu.tpl');
		MenuService::assign_positions_conditions($tpl, $this->get_block());
		
		$config = GoogleAnalyticsConfig::load();
		
		$identifier = $config->get_identifier();
		
		if (empty($identifier) && AppContext::get_current_user()->check_level(User::ADMIN_LEVEL))
		{
			$message = StringVars::replace_vars(LangLoader::get_message('identifier_required','common', 'GoogleAnalytics'), array(
				'link' => Url::to_absolute('/GoogleAnalytics/' . url('index.php?url=/admin', 'admin/'))
			));
			return MessageHelper::display($message, MessageHelper::WARNING)->render();
		}

		$tpl->put_all(array(
			'C_IDENTIFIER' => !empty($identifier),
			'IDENTIFIER' => $identifier
		));
		
		return $tpl->render();
	}
}
?>