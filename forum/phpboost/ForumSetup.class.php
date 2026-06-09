<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 3.0 - 2010 05 27
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @author      Arnaud GENET <elenwii@phpboost.com>
*/

class ForumSetup extends DefaultModuleSetup
{
	public static $forum_alerts_table;
	public static $forum_cats_table;
	public static $forum_history_table;
	public static $forum_message_table;
	public static $forum_poll_table;
	public static $forum_topics_table;
	public static $forum_track_table;
	public static $forum_view_table;
	public static $forum_ranks_table;

	private static $member_extended_field_last_view_forum_column = 'last_view_forum';

	private $querier;

	/**
	 * @var string[string] localized messages
	 */
	private $install_lang;

	public static function __static()
	{
		self::$forum_alerts_table  = PREFIX . 'forum_alerts';
		self::$forum_cats_table    = PREFIX . 'forum_cats';
		self::$forum_history_table = PREFIX . 'forum_history';
		self::$forum_message_table = PREFIX . 'forum_msg';
		self::$forum_poll_table    = PREFIX . 'forum_poll';
		self::$forum_topics_table  = PREFIX . 'forum_topics';
		self::$forum_track_table   = PREFIX . 'forum_track';
		self::$forum_view_table    = PREFIX . 'forum_view';
		self::$forum_ranks_table   = PREFIX . 'forum_ranks';
	}

	public function __construct()
	{
		$this->querier = PersistenceContext::get_querier();
	}

	public function install()
	{
		$this->drop_tables();
		$this->create_tables();
		$this->insert_data();
	}

	public function uninstall()
	{
		$this->drop_tables();
		ConfigManager::delete('forum', 'config');
		$this->delete_member_extended_field();
	}

	private function drop_tables()
	{
		PersistenceContext::get_dbms_utils()->drop([
			self::$forum_alerts_table,
			self::$forum_cats_table,
			self::$forum_history_table,
			self::$forum_message_table,
			self::$forum_poll_table,
			self::$forum_topics_table,
			self::$forum_track_table,
			self::$forum_view_table,
			self::$forum_ranks_table
		]);
	}

	private function create_tables()
	{
		$this->create_forum_alerts_table();
		$this->create_forum_cats_table();
		$this->create_forum_history_table();
		$this->create_forum_message_table();
		$this->create_forum_poll_table();
		$this->create_forum_topics_table();
		$this->create_forum_track_table();
		$this->create_forum_view_table();
		$this->create_forum_ranks_table();
	}

	private function create_forum_alerts_table()
	{
		$fields = [
			'id' => ['type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1],
			'id_category' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'idtopic' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'title' => ['type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"],
			'content' => ['type' => 'text', 'length' => 65000],
			'user_id' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'status' => ['type' => 'boolean', 'notnull' => 1, 'default' => 0],
			'idmodo' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'timestamp' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0]
		];
		$options = [
			'primary' => ['id'],
			'idtopic' => ['type' => 'key', 'fields' => ['idtopic', 'user_id', 'idmodo']]
		];
		PersistenceContext::get_dbms_utils()->create_table(self::$forum_alerts_table, $fields, $options);
	}

	private function create_forum_cats_table()
	{
		ForumCategory::create_categories_table(self::$forum_cats_table);
	}

	private function create_forum_history_table()
	{
		$fields = [
			'id' => ['type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1],
			'action' => ['type' => 'string', 'length' => 50, 'notnull' => 1, 'default' => "''"],
			'user_id' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'user_id_action' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'url' => ['type' => 'text', 'length' => 2048],
			'timestamp' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0]
		];
		$options = [
			'primary' => ['id'],
			'user_id' => ['type' => 'key', 'fields' => 'user_id']
		];
		PersistenceContext::get_dbms_utils()->create_table(self::$forum_history_table, $fields, $options);
	}

	private function create_forum_message_table()
	{
		$fields = [
			'id' => ['type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1],
			'idtopic' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'user_id' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'content' => ['type' => 'text', 'length' => 65000],
			'timestamp' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'timestamp_edit' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'user_id_edit' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'user_ip' => ['type' => 'string', 'length' => 128, 'notnull' => 1, 'default' => "''"],
			'selected' => ['type' => 'boolean', 'notnull' => 1, 'default' => 0],
		];
		$options = [
			'primary' => ['id'],
			'indexes' => [
				'idtopic' => ['type' => 'key', 'fields' => 'idtopic'],
				'content' => ['type' => 'fulltext', 'fields' => 'content']
		]];
		PersistenceContext::get_dbms_utils()->create_table(self::$forum_message_table, $fields, $options);
	}

	private function create_forum_poll_table()
	{
		$fields = [
			'id' => ['type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1],
			'idtopic' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'question' => ['type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"],
			'answers' => ['type' => 'text', 'length' => 65000],
			'voter_id' => ['type' => 'text', 'length' => 65000],
			'votes' => ['type' => 'text', 'length' => 65000],
			'type' => ['type' => 'boolean', 'notnull' => 1, 'default' => 0]
		];
		$options = [
			'primary' => ['id'],
			'idtopic' => ['type' => 'unique', 'fields' => 'idtopic']
		];
		PersistenceContext::get_dbms_utils()->create_table(self::$forum_poll_table, $fields, $options);
	}

	private function create_forum_topics_table()
	{
		$fields = [
			'id' => ['type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1],
			'id_category' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'title' => ['type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"],
			'subtitle' => ['type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"],
			'user_id' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'nbr_msg' => ['type' => 'integer', 'length' => 9, 'notnull' => 1, 'default' => 0],
			'nbr_views' => ['type' => 'integer', 'length' => 9, 'notnull' => 1, 'default' => 0],
			'last_user_id' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'last_msg_id' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'last_timestamp' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'first_msg_id' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'type' => ['type' => 'boolean', 'notnull' => 1, 'default' => 0],
			'status' => ['type' => 'boolean', 'notnull' => 1, 'default' => 0],
			'aprob' => ['type' => 'boolean', 'notnull' => 1, 'default' => 0],
			'display_msg' => ['type' => 'boolean', 'notnull' => 1, 'default' => 0]
		];
		$options = [
			'primary' => ['id'],
			'indexes' => [
				'id_category' => ['type' => 'key', 'fields' => ['id_category', 'last_user_id', 'last_timestamp', 'type']],
				'title' => ['type' => 'fulltext', 'fields' => 'title']

		]];
		PersistenceContext::get_dbms_utils()->create_table(self::$forum_topics_table, $fields, $options);
	}

	private function create_forum_track_table()
	{
		$fields = [
			'id' => ['type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1],
			'idtopic' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'user_id' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'track' => ['type' => 'boolean', 'notnull' => 1, 'default' => 0],
			'pm' => ['type' => 'boolean', 'notnull' => 1, 'default' => 0],
			'mail' => ['type' => 'boolean', 'notnull' => 1, 'default' => 0]
		];
		$options = [
			'primary' => ['id'],
			'idtopic' => ['type' => 'unique', 'fields' => ['idtopic', 'user_id']]
		];
		PersistenceContext::get_dbms_utils()->create_table(self::$forum_track_table, $fields, $options);
	}

	private function create_forum_view_table()
	{
		$fields = [
			'id' => ['type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1],
			'idtopic' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'last_view_id' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'user_id' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'timestamp' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0]
		];
		$options = [
			'primary' => ['id'],
			'idv' => ['type' => 'key', 'fields' => ['idtopic', 'user_id']]
		];
		PersistenceContext::get_dbms_utils()->create_table(self::$forum_view_table, $fields, $options);
	}

	private function create_forum_ranks_table()
	{
		$fields = [
			'id' => ['type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1],
			'name' => ['type' => 'string', 'length' => 150, 'notnull' => 1, 'default' => "''"],
			'msg' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'icon' => ['type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"],
			'special' => ['type' => 'boolean', 'length' => 1, 'notnull' => 1, 'default' => 0]
		];
		$options = [
			'primary' => ['id']
		];
		PersistenceContext::get_dbms_utils()->create_table(self::$forum_ranks_table, $fields, $options);
	}

	private function delete_member_extended_field()
	{
		ExtendedFieldsService::delete_by_field_name(self::$member_extended_field_last_view_forum_column);
		ExtendedFieldsService::delete_by_field_name('user_skype');
		ExtendedFieldsService::delete_by_field_name('user_sign');
	}

	private function insert_data()
	{
		$this->install_lang = LangLoader::get('install', 'forum');
		$this->create_member_extended_field();
		$this->insert_forum_cats_data();
		$this->insert_forum_topics_data();
		$this->insert_forum_msg_data();
		$this->insert_forum_ranks_data();
	}

	private function create_member_extended_field()
	{
		$lang = LangLoader::get_all_langs('forum');

		$extended_field = new ExtendedField();
		$extended_field->set_name(self::$member_extended_field_last_view_forum_column);
		$extended_field->set_field_name(self::$member_extended_field_last_view_forum_column);
		$extended_field->set_field_type('MemberHiddenExtendedField');
		$extended_field->set_is_required(false);
		$extended_field->set_display(false);
		$extended_field->set_is_freeze(true);
		ExtendedFieldsService::add($extended_field);

		//Skype
		$extended_field = new ExtendedField();
		$extended_field->set_name($lang['forum.extended.field.skype']);
		$extended_field->set_field_name('user_skype');
		$extended_field->set_description($lang['forum.extended.field.skype.clue']);
		$extended_field->set_field_type('MemberShortTextExtendedField');
		$extended_field->set_is_required(false);
		$extended_field->set_display(false);
		$extended_field->set_is_freeze(true);
		$extended_field->set_regex(4);
		ExtendedFieldsService::add($extended_field);

		//Sign
		$extended_field = new ExtendedField();
		$extended_field->set_name($lang['forum.extended.field.signing']);
		$extended_field->set_field_name('user_sign');
		$extended_field->set_description($lang['forum.extended.field.signing.clue']);
		$extended_field->set_field_type('MemberLongTextExtendedField');
		$extended_field->set_is_required(false);
		$extended_field->set_display(false);
		$extended_field->set_is_freeze(true);
		ExtendedFieldsService::add($extended_field);
	}

	private function insert_forum_cats_data()
	{
		$this->querier->insert(self::$forum_cats_table, [
			'id'            => 1,
			'name'          => $this->install_lang['forum.default.category.name'],
			'rewrited_name' => Url::encode_rewrite($this->install_lang['forum.default.category.name']),
			'description'   => $this->install_lang['forum.default.category.description'],
			'c_order'       => 1,
			'auth'          => '',
			'id_parent'     => 0,
			'last_topic_id' => 1,
			'url'           => ''
		]);

		$this->querier->insert(self::$forum_cats_table, [
			'id'            => 2,
			'name'          => $this->install_lang['forum.default.forum.name'],
			'rewrited_name' => Url::encode_rewrite($this->install_lang['forum.default.forum.name']),
			'description'   => $this->install_lang['forum.default.forum.description'],
			'c_order'       => 1,
			'auth'          => '',
			'id_parent'     => 1,
			'last_topic_id' => 1,
			'url'           => ''
		]);
	}

	private function insert_forum_topics_data()
	{
		$this->querier->insert(self::$forum_topics_table, [
			'id'             => 1,
			'id_category'    => 2,
			'title'          => $this->install_lang['forum.sample.topic.title'],
			'subtitle'       => $this->install_lang['forum.sample.topic.subtitle'],
			'user_id'        => 1,
			'nbr_msg'        => 1,
			'nbr_views'      => 0,
			'last_user_id'   => 1,
			'last_msg_id'    => 1,
			'last_timestamp' => time(),
			'first_msg_id'   => 1,
			'type'           => 0,
			'status'         => 1,
			'aprob'          => 0,
			'display_msg'    => 0
		]);
	}

	private function insert_forum_msg_data()
	{
		$this->querier->insert(self::$forum_message_table, [
			'id'             => 1,
		 	'idtopic'        => 1,
			'user_id'        => 1,
			'content'        => $this->install_lang['forum.sample.topic.message.content'],
			'timestamp'      => time(),
			'timestamp_edit' => 0,
			'user_id_edit'   => 0,
			'user_ip'        => AppContext::get_request()->get_ip_address(),
			'selected'       => 0,
		]);

		//Mise à jour du nombre de messages du membre.
		$this->querier->inject("UPDATE " . DB_TABLE_MEMBER . " SET posted_msg = posted_msg + 1 WHERE user_id = '1'");
	}

	private function insert_forum_ranks_data()
	{
		$this->querier->insert(self::$forum_ranks_table, [
			'id'      => 1,
			'name'    => $this->install_lang['forum.rank.administrator'],
			'msg'     => -2,
			'icon'    => 'rank_admin.png',
			'special' => 1
		]);
		$this->querier->insert(self::$forum_ranks_table, [
			'id'      => 2,
			'name'    => $this->install_lang['forum.rank.moderator'],
			'msg'     => -1,
			'icon'    => 'rank_modo.png',
			'special' => 1
		]);
		$this->querier->insert(self::$forum_ranks_table, [
			'id'      => 3,
			'name'    => $this->install_lang['forum.rank.inactiv'],
			'msg'     => 0,
			'icon'    => 'rank_0.png',
			'special' => 0
		]);
		$this->querier->insert(self::$forum_ranks_table, [
			'id'      => 4,
			'name'    => $this->install_lang['forum.rank.slingshot'],
			'msg'     => 1,
			'icon'    => 'rank_0.png',
			'special' => 0
		]);
		$this->querier->insert(self::$forum_ranks_table, [
			'id'      => 5,
			'name'    => $this->install_lang['forum.rank.minigun'],
			'msg'     => 25,
			'icon'    => 'rank_1.png',
			'special' => 0
		]);
		$this->querier->insert(self::$forum_ranks_table, [
			'id'      => 6,
			'name'    => $this->install_lang['forum.rank.fuzil'],
			'msg'     => 50,
			'icon'    => 'rank_2.png',
			'special' => 0
		]);
		$this->querier->insert(self::$forum_ranks_table, [
			'id'      => 7,
			'name'    => $this->install_lang['forum.rank.bazooka'],
			'msg'     => 100,
			'icon'    => 'rank_3.png',
			'special' => 0
		]);
		$this->querier->insert(self::$forum_ranks_table, [
			'id'      => 8,
			'name'    => $this->install_lang['forum.rank.rocket'],
			'msg'     => 250,
			'icon'    => 'rank_4.png',
			'special' => 0
		]);
		$this->querier->insert(self::$forum_ranks_table, [
			'id'      => 9,
			'name'    => $this->install_lang['forum.rank.mortar'],
			'msg'     => 500,
			'icon'    => 'rank_5.png',
			'special' => 0
		]);
		$this->querier->insert(self::$forum_ranks_table, [
			'id'      => 10,
			'name'    => $this->install_lang['forum.rank.missile'],
			'msg'     => 1000,
			'icon'    => 'rank_6.png',
			'special' => 0
		]);
		$this->querier->insert(self::$forum_ranks_table, [
			'id'      => 11,
			'name'    => $this->install_lang['forum.rank.spaceship'],
			'msg'     => 1500,
			'icon'    => 'rank_special.png',
			'special' => 0
		]);

	}
}
?>
