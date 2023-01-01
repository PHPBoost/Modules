<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 10 17
 * @since       PHPBoost 6.0 - 2022 08 26
 */

class RecipeSetup extends DefaultModuleSetup
{
	public static $recipe_table;
	public static $recipe_cats_table;

	/**
	 * @var string[string] localized messages
	 */
	private $messages;

	public static function __static()
	{
		self::$recipe_table = PREFIX . 'recipe';
		self::$recipe_cats_table = PREFIX . 'recipe_cats';
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
		ConfigManager::delete('recipe', 'config');
		CacheManager::invalidate('module', 'recipe');
		KeywordsService::get_keywords_manager()->delete_module_relations();
	}

	private function drop_tables()
	{
		PersistenceContext::get_dbms_utils()->drop(array(self::$recipe_table, self::$recipe_cats_table));
	}

	private function create_tables()
	{
		$this->create_recipe_table();
		$this->create_recipe_cats_table();
	}

	private function create_recipe_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'id_category' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'thumbnail' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'title' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'rewrited_title' => array('type' => 'string', 'length' => 255, 'default' => "''"),
			'content' => array('type' => 'text', 'length' => 65000),
			'summary' => array('type' => 'text', 'length' => 65000),
			'views_number' => array('type' => 'integer', 'length' => 11, 'default' => 0),
			'author_custom_name' => array('type' =>  'string', 'length' => 255, 'default' => "''"),
			'author_user_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'published' => array('type' => 'integer', 'length' => 1, 'notnull' => 1, 'default' => 0),
			'publishing_start_date' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'publishing_end_date' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'creation_date' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'update_date' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'persons_number' => array('type' => 'integer', 'length' => 11, 'default' => 0),
			'ingredients' => array('type' => 'text', 'length' => 65000),
			'steps' => array('type' => 'text', 'length' => 65000),
		);
		$options = array(
			'primary' => array('id'),
			'indexes' => array(
				'id_category' => array('type' => 'key', 'fields' => 'id_category'),
				'title' => array('type' => 'fulltext', 'fields' => 'title'),
				'content' => array('type' => 'fulltext', 'fields' => 'content'),
				'summary' => array('type' => 'fulltext', 'fields' => 'summary')
			)
		);
		PersistenceContext::get_dbms_utils()->create_table(self::$recipe_table, $fields, $options);
	}

	private function create_recipe_cats_table()
	{
		RichCategory::create_categories_table(self::$recipe_cats_table);
	}

	private function insert_data()
	{
		$this->messages = LangLoader::get('install', 'recipe');
		$this->insert_recipe_cats_data();
		$this->insert_recipe_data();
	}

	private function insert_recipe_cats_data()
	{
		PersistenceContext::get_querier()->insert(self::$recipe_cats_table, array(
			'id' => 1,
			'id_parent' => 0,
			'c_order' => 1,
			'auth' => '',
			'rewrited_name' => Url::encode_rewrite($this->messages['default.cat.name']),
			'name' => $this->messages['default.cat.name'],
			'description' => $this->messages['default.cat.description'],
			'thumbnail' => FormFieldThumbnail::DEFAULT_VALUE
		));
	}

	private function insert_recipe_data()
	{
		PersistenceContext::get_querier()->insert(self::$recipe_table, array(
			'id' => 1,
			'id_category' => 1,
			'title' => $this->messages['default.recipe.name'],
			'rewrited_title' => Url::encode_rewrite($this->messages['default.recipe.name']),
			'content' => $this->messages['default.recipe.content'],
			'summary' => '',
			'published' => RecipeItem::PUBLISHED,
			'publishing_start_date' => 0,
			'publishing_end_date' => 0,
			'creation_date' => time(),
			'update_date' => time(),
			'author_custom_name' => '',
			'author_user_id' => 1,
			'views_number' => 0,
			'persons_number' => 4,
			'ingredients' => TextHelper::serialize(array()),
			'steps' => TextHelper::serialize(array()),
			'thumbnail' => FormFieldThumbnail::DEFAULT_VALUE
		));
	}
}
?>
