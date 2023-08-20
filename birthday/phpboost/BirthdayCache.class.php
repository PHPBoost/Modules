<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2023 08 20
 * @since       PHPBoost 4.0 - 2013 08 27
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class BirthdayCache implements CacheData
{
	private $users_birthday = array();
	private $upcoming_birthdays = array();

	public function synchronize()
	{
		$result = PersistenceContext::get_querier()->select("SELECT
			member.user_id, display_name, level, member_extended_fields.user_born, user_groups,
			IF(
				member_extended_fields.user_born < 0,
				DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(DATE_ADD(FROM_UNIXTIME(0), INTERVAL member_extended_fields.user_born second))), '%Y')+0,
				CAST((PERIOD_DIFF(DATE_FORMAT(CURRENT_DATE(), '%Y%m'),DATE_FORMAT(FROM_UNIXTIME(member_extended_fields.user_born), '%Y%m')) / 12) as UNSIGNED)
			) AS age
			FROM " . DB_TABLE_MEMBER . " member
			LEFT JOIN " . DB_TABLE_MEMBER_EXTENDED_FIELDS . " member_extended_fields ON member_extended_fields.user_id = member.user_id
			WHERE IF(
					member_extended_fields.user_born < 0,
					MONTH(DATE_ADD(FROM_UNIXTIME(0),INTERVAL member_extended_fields.user_born second)),
					MONTH(FROM_UNIXTIME(member_extended_fields.user_born))
				) = MONTH(NOW())
			AND IF(
					member_extended_fields.user_born < 0,
					DAY(DATE_ADD(FROM_UNIXTIME(0),INTERVAL member_extended_fields.user_born second)),
					DAY(FROM_UNIXTIME(member_extended_fields.user_born))
				) = DAY(NOW())
		");

		while ($row = $result->fetch())
		{
			$row['age'] = $row['age'] . ' ' . ($row['age'] > 1 ? LangLoader::get_message('date.years', 'date-lang') : LangLoader::get_message('date.year', 'date-lang'));

			$this->users_birthday[] = $row;
		}

		$result_next = PersistenceContext::get_querier()->select(
            "   SELECT member.user_id, display_name, member_extended_fields.user_born, user_groups, level,
                IF(
                    member_extended_fields.user_born < 0,
                    DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(DATE_ADD(FROM_UNIXTIME(0), INTERVAL member_extended_fields.user_born second))), '%Y')+0,
                    CAST((PERIOD_DIFF(DATE_FORMAT(CURRENT_DATE(), '%Y%m'),DATE_FORMAT(FROM_UNIXTIME(member_extended_fields.user_born), '%Y%m')) / 12) as UNSIGNED)
                ) AS age
                FROM " . DB_TABLE_MEMBER . " member
                LEFT JOIN " . DB_TABLE_MEMBER_EXTENDED_FIELDS . " member_extended_fields ON member_extended_fields.user_id = member.user_id
                ORDER BY IF(
					member_extended_fields.user_born < 0,
					DATE_FORMAT(DATE_ADD(FROM_UNIXTIME(0), INTERVAL member_extended_fields.user_born second), '%m%d'),
					DATE_FORMAT(FROM_UNIXTIME(member_extended_fields.user_born), '%m%d')
				)
			"
		);

        $now = new Date();
        $delay = new Date($now->get_timestamp() + (BirthdayConfig::load()->get_coming_next() * 86400));

		while ($row_next = $result_next->fetch())
		{
            $birthdate = new Date($row_next['user_born']);
            $b_day = $birthdate->get_day();
            $b_month = $birthdate->get_month();

            if ($now->get_year() !== $delay->get_year())
            {
                if ($b_month < $now->get_month())
                    $next_anniversary = new Date($now->get_year() + 1 . '-' . $b_month . '-' . $b_day);
                else
                    $next_anniversary = new Date($now->get_year() . '-' . $b_month . '-' . $b_day);
            }
            else
            {
                $next_anniversary = new Date($now->get_year() . '-' . $b_month . '-' . $b_day);
            }

            if ($next_anniversary > $now && $next_anniversary <= $delay)
            {
                $this->upcoming_birthdays[] = $row_next;
            }
		}
	}

	public function get_users_birthday()
	{
		return $this->users_birthday;
	}

	public function get_upcoming_birthdays()
	{
		return $this->upcoming_birthdays;
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
