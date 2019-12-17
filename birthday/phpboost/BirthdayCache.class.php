<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 10 09
 * @since       PHPBoost 4.0 - 2013 08 27
*/

class BirthdayCache implements CacheData
{
	private $users_birthday = array();

	public function synchronize()
	{
		$this->users_birthday = array();

		$result = PersistenceContext::get_querier()->select("SELECT member.user_id, display_name, level, member_extended_fields.user_born, groups, IF(member_extended_fields.user_born < 0, DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(DATE_ADD(FROM_UNIXTIME(0), INTERVAL member_extended_fields.user_born second))), '%Y')+0, CAST((PERIOD_DIFF(DATE_FORMAT(CURRENT_DATE(), '%Y%m'), DATE_FORMAT(FROM_UNIXTIME(member_extended_fields.user_born), '%Y%m')) / 12) as UNSIGNED)) AS age
		FROM " . DB_TABLE_MEMBER . " member
		LEFT JOIN " . DB_TABLE_MEMBER_EXTENDED_FIELDS . " member_extended_fields ON member_extended_fields.user_id = member.user_id
		WHERE IF(member_extended_fields.user_born < 0, MONTH(DATE_ADD(FROM_UNIXTIME(0), INTERVAL member_extended_fields.user_born second)), MONTH(FROM_UNIXTIME(member_extended_fields.user_born))) = MONTH(NOW()) AND IF(member_extended_fields.user_born < 0, DAY(DATE_ADD(FROM_UNIXTIME(0), INTERVAL member_extended_fields.user_born second)), DAY(FROM_UNIXTIME(member_extended_fields.user_born))) = DAY(NOW())");

		while ($row = $result->fetch())
		{
			$row['age'] = $row['age'] . ' ' . ($row['age'] > 1 ? LangLoader::get_message('years', 'date-common') : LangLoader::get_message('year', 'date-common'));

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
