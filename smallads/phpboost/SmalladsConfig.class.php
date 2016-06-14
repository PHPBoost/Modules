<?php
/*##################################################
 *                               SmalladsConfig.class.php
 *                            -------------------
 *   begin                : February 2, 2016
 *   copyright            : (C) 2016 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
 *
 *
 ###################################################
 *
 * This program is a free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

 /**
 * @author Julien BRISWALTER <j1.seth@phpboost.com>
 */

class SmalladsConfig extends AbstractConfigData
{
	const ITEMS_NUMBER_PER_PAGE = 'items_number_per_page';
	const LIST_SIZE = 'list_size';
	const MAX_CONTENTS_LENGTH = 'max_contents_length';
	const MAX_WEEKS_NUMBER_DISPLAYED = 'max_weeks_number_displayed';
	const MAX_WEEKS_NUMBER = 'max_weeks_number';
	const DISPLAY_MAIL_ENABLED = 'display_mail_enabled';
	const DISPLAY_PM_ENABLED = 'display_pm_enabled';
	const RETURN_TO_LIST_ENABLED = 'return_to_list_enabled';
	const USAGE_TERMS_ENABLED = 'usage_terms_enabled';
	const USAGE_TERMS = 'usage_terms';
	const AUTHORIZATIONS = 'authorizations';
	
	const MAX_PICTURE_WEIGHT = 1024;
	
	public function get_items_number_per_page()
	{
		return $this->get_property(self::ITEMS_NUMBER_PER_PAGE);
	}
	
	public function set_items_number_per_page($value)
	{
		$this->set_property(self::ITEMS_NUMBER_PER_PAGE, $value);
	}
	
	public function get_list_size()
	{
		return $this->get_property(self::LIST_SIZE);
	}
	
	public function set_list_size($value)
	{
		$this->set_property(self::LIST_SIZE, $value);
	}
	
	public function get_max_contents_length()
	{
		return $this->get_property(self::MAX_CONTENTS_LENGTH);
	}
	
	public function set_max_contents_length($value)
	{
		$this->set_property(self::MAX_CONTENTS_LENGTH, $value);
	}
	
	public function display_max_weeks_number()
	{
		$this->set_property(self::MAX_WEEKS_NUMBER_DISPLAYED, true);
	}
	
	public function hide_max_weeks_number()
	{
		$this->set_property(self::MAX_WEEKS_NUMBER_DISPLAYED, false);
	}
	
	public function is_max_weeks_number_displayed()
	{
		return $this->get_property(self::MAX_WEEKS_NUMBER_DISPLAYED);
	}
	
	public function get_max_weeks_number()
	{
		return $this->get_property(self::MAX_WEEKS_NUMBER);
	}
	
	public function set_max_weeks_number($value)
	{
		$this->set_property(self::MAX_WEEKS_NUMBER, $value);
	}
	
	public function display_mail()
	{
		$this->set_property(self::DISPLAY_MAIL_ENABLED, true);
	}
	
	public function hide_mail()
	{
		$this->set_property(self::DISPLAY_MAIL_ENABLED, false);
	}
	
	public function is_mail_displayed()
	{
		return $this->get_property(self::DISPLAY_MAIL_ENABLED);
	}
	
	public function display_pm()
	{
		$this->set_property(self::DISPLAY_PM_ENABLED, true);
	}
	
	public function hide_pm()
	{
		$this->set_property(self::DISPLAY_PM_ENABLED, false);
	}
	
	public function is_pm_displayed()
	{
		return $this->get_property(self::DISPLAY_PM_ENABLED);
	}
	
	public function display_return_to_list()
	{
		$this->set_property(self::RETURN_TO_LIST_ENABLED, true);
	}
	
	public function hide_return_to_list()
	{
		$this->set_property(self::RETURN_TO_LIST_ENABLED, false);
	}
	
	public function is_return_to_list_displayed()
	{
		return $this->get_property(self::RETURN_TO_LIST_ENABLED);
	}
	
	public function display_usage_terms()
	{
		$this->set_property(self::USAGE_TERMS_ENABLED, true);
	}
	
	public function hide_usage_terms()
	{
		$this->set_property(self::USAGE_TERMS_ENABLED, false);
	}
	
	public function are_usage_terms_displayed()
	{
		return $this->get_property(self::USAGE_TERMS_ENABLED);
	}
	
	public function get_usage_terms()
	{
		return $this->get_property(self::USAGE_TERMS);
	}
	
	public function set_usage_terms($value)
	{
		$this->set_property(self::USAGE_TERMS, $value);
	}
	
	public function get_authorizations()
	{
		return $this->get_property(self::AUTHORIZATIONS);
	}
	
	public function set_authorizations(Array $authorizations)
	{
		$this->set_property(self::AUTHORIZATIONS, $authorizations);
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function get_default_values()
	{
		return array(
			self::ITEMS_NUMBER_PER_PAGE => 10,
			self::LIST_SIZE => 1,
			self::MAX_CONTENTS_LENGTH => 1000,
			self::MAX_WEEKS_NUMBER_DISPLAYED => true,
			self::MAX_WEEKS_NUMBER => 12,
			self::DISPLAY_MAIL_ENABLED => true,
			self::DISPLAY_PM_ENABLED => true,
			self::RETURN_TO_LIST_ENABLED => false,
			self::USAGE_TERMS_ENABLED => false,
			self::USAGE_TERMS => 'Renseigner ici vos Conditions Générales / Fill in there your general usage terms',
			self::AUTHORIZATIONS => array('r-1' => 1, 'r0' => 7, 'r1' => 15)
		);
	}
	
	/**
	 * Returns the configuration.
	 * @return SmalladsConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'smallads', 'config');
	}
	
	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('smallads', self::load(), 'config');
	}
}
?>
