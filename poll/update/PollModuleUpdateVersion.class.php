<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 6.0 - 2021 04 06
*/

class PollModuleUpdateVersion extends ModuleUpdateVersion
{
	public function __construct()
	{
		parent::__construct('poll');

		$this->content_tables = [PREFIX . 'poll'];
		self::$delete_old_files_list = [
			'/lang/english/poll_english.php',
			'/lang/french/poll_french.php',
			'/phpboost/PollExtensionPointProvider.class.php',
			'/phpboost/PollHomePageExtensionPoint.class.php',
			'/phpboost/PollTreeLinks.class.php',
			'/templates/admin_poll_add.tpl',
			'/templates/admin_poll_config.tpl',
			'/templates/admin_poll_management.tpl',
			'/templates/admin_poll_management2.tpl',
			'/templates/poll.tpl',
			'/templates/poll_mini.tpl',
			'/util/PollUrlBuilder.class.php',
			'/admin_poll.php',
			'/admin_poll_add.php',
			'/admin_poll_config.php',
			'/poll.php',
			'/poll_begin.php',
		];
		
		if (in_array(PREFIX . 'poll_ip', $this->tables_list))
		{
			if (in_array(PREFIX . 'poll_voters', $this->tables_list))
				$this->db_utils->drop([PREFIX . 'poll_voters']);
			$this->querier->inject('ALTER TABLE ' . PREFIX . 'poll_ip RENAME ' . PREFIX . 'poll_voters');
		}

		if (!in_array(PREFIX . 'poll_cats', $this->tables_list))
			RichCategory::create_categories_table(PREFIX . 'poll_cats');

		$this->database_columns_to_modify = [
			[
				'table_name' => PREFIX . 'poll',
				'columns' => [
					'question'  => 'question TEXT',
					'type'      => 'answers_type INT(11) NOT NULL DEFAULT 1',
					'user_id'   => 'author_user_id INT(11) NOT NULL DEFAULT 0',
					'timestamp' => 'creation_date INT(11) NOT NULL DEFAULT 0',
					'archive'   => 'close_poll INT(1) NOT NULL DEFAULT 0',
					'visible'   => 'published INT(1) NOT NULL DEFAULT 0',
					'start'     => 'publishing_start_date INT(11) NOT NULL DEFAULT 0',
					'end'       => 'publishing_end_date INT(11) NOT NULL DEFAULT 0',
				]
			],
			[
				'table_name' => PREFIX . 'poll_voters',
				'columns' => [
					'ip'        => 'voter_ip VARCHAR(50) NOT NULL DEFAULT ""',
					'user_id'   => 'voter_user_id INT(11) DEFAULT 0',
					'idpoll'    => 'poll_id INT(11) NOT NULL DEFAULT 0',
					'timestamp' => 'vote_timestamp INT(11) NOT NULL DEFAULT 0',
				]
			]
		];

		$this->database_columns_to_add = [
			[
				'table_name' => PREFIX . 'poll',
				'columns' => [
					'title'              => ['type' => 'string',  'length' => 255, 'notnull' => 1, 'default' => "''"],
					'rewrited_title'     => ['type' => 'string',  'length' => 255, 'default' => "''"],
					'id_category'        => ['type' => 'integer', 'length' => 11,  'notnull' => 1, 'default' => 0],
					'author_custom_name' => ['type' => 'string',  'length' => 255, 'default' => "''"],
					'update_date'        => ['type' => 'integer', 'length' => 11,  'notnull' => 1, 'default' => 0],
					'views_number'       => ['type' => 'integer', 'length' => 11,  'default' => 0],
					'thumbnail'          => ['type' => 'string',  'length' => 255, 'notnull' => 1, 'default' => "''"],
					'votes_number'       => ['type' => 'integer', 'length' => 11,  'notnull' => 1, 'default' => 0],
					'countdown_display'  => ['type' => 'integer', 'length' => 1,   'notnull' => 1, 'default' => 2],
				]
			]
		];

		$this->database_keys_to_add = [
			[
				'table_name' => PREFIX . 'poll',
				'keys' => [
					'title'       => true,
					'id_category' => false
				]
			]
		];
	}

	protected function execute_module_specific_changes()
	{
		// Set update_date to creation_date if update_date = 0, title and count actual poll votes
		$result = $this->querier->select('SELECT id, update_date, creation_date, question, answers, votes
			FROM ' . PREFIX . 'poll'
		);

		while ($row = $result->fetch())
		{
			$this->querier->update(PREFIX . 'poll', ['title' => $row['question'], 'rewrited_title' => Url::encode_rewrite($row['question'])], 'WHERE title = \'\' AND id = :id', ['id' => $row['id']]);
			$this->querier->update(PREFIX . 'poll', ['update_date' => $row['creation_date']], 'WHERE update_date = 0 AND id = :id', ['id' => $row['id']]);
			
			if (preg_match('/|/', $row['answers']) || preg_match('/|/', $row['votes']))
			{
				$answers_titles = explode('|', $row['answers']);
				$answers = [];
				foreach ($answers_titles as $answer)
				{
					$answers[Url::encode_rewrite($answer)] = [
						'is_default' => false,
						'title' => addslashes($answer)
					];
				}

				$votes = [];
				$votes_number = 0;
				foreach (explode('|', $row['votes']) as $id => $vote)
				{
					$votes[$answers_titles[$id]] = (int)$vote;
					$votes_number++;
				}
				$this->querier->update(PREFIX . 'poll', ['answers' => TextHelper::serialize($answers), 'votes_number' => $votes_number, 'votes' => TextHelper::serialize($votes)], 'WHERE id = :id', ['id' => $row['id']]);
			}
		}
		$result->dispose();
	}
}
?>
