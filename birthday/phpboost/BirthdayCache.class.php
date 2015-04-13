<?php
/*##################################################
 *                           CalendarCurrentMonthEventsCache.class.php
 *                            -------------------
 *   begin                : August 27, 2013
 *   copyright            : (C) 2013 Julien BRISWALTER
 *   email                : julienseth78@phpboost.com
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
 * @author Julien BRISWALTER <julienseth78@phpboost.com>
 */
class BirthdayCache implements CacheData
{
	private $users_birthday = array();
	
	public function synchronize()
	{
		$this->users_birthday = array();
		
		$result = PersistenceContext::get_querier()->select("SELECT member.user_id, login, level, member_extended_fields.user_born, user_groups, IF(member_extended_fields.user_born < 0, DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(DATE_ADD(FROM_UNIXTIME(0), INTERVAL member_extended_fields.user_born second))), '%Y')+0, CAST((PERIOD_DIFF(DATE_FORMAT(CURRENT_DATE(), '%Y%m'), DATE_FORMAT(FROM_UNIXTIME(member_extended_fields.user_born), '%Y%m')) / 12) as UNSIGNED)) AS age
		FROM " . DB_TABLE_MEMBER . " member
		LEFT JOIN " . DB_TABLE_MEMBER_EXTENDED_FIELDS . " member_extended_fields ON member_extended_fields.user_id = member.user_id
		WHERE IF(member_extended_fields.user_born < 0, DAYOFYEAR(DATE_ADD(FROM_UNIXTIME(0), INTERVAL member_extended_fields.user_born second)), DAYOFYEAR(FROM_UNIXTIME(member_extended_fields.user_born))) = DAYOFYEAR(NOW())");
		
		while ($row = $result->fetch())
		{
			$row['age'] = $row['age'] . ' ' . ($row['age'] > 1 ? LangLoader::get_message('years', 'common', 'birthday') : LangLoader::get_message('year', 'common', 'birthday'));
			
			$this->users_birthday[] = $row;
		}
	}
	
	public function get_users_birthday()
	{
		return $this->users_birthday;
	}
	
	/**
	 * Loads and returns the users birthday cached data.
	 * @return BirthdayCache The cached data
	 */
	public static function load()
	{
		return CacheManager::load(__CLASS__, 'birthday', 'usersbirthday');
	}
	
	/**
	 * Invalidates the birthdays of the day cached data.
	 */
	public static function invalidate()
	{
		CacheManager::invalidate('birthday', 'usersbirthday');
	}
}
?>
