<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      LamPDL
 * @version     PHPBoost 6.0 - last update: 2026 05 19
 * @since       PHPBoost 6.0 - 2026 03 01
 */

class DevToolsSetup extends DefaultModuleSetup
{
    // Cache table used by the File Review tab (ReviewService).
    // Declared as a static property so ReviewService can reference it,
    // but the value is always computed inline via self::table() to avoid
    // any dependency on __static() being called before install/uninstall.
    public static $table_name;

    public static function __static()
    {
        self::$table_name = PREFIX . 'devtools_review';
    }

    // Always returns the correct table name, even if __static() was not yet called.
    private static function table()
    {
        return PREFIX . 'devtools_review';
    }

    public function install()
    {
        $this->drop_tables();
        $this->create_review_table();

        $config = new DevToolsConfig();
        $config->set_default_values();
        ConfigManager::save('devtools', $config, 'config');
    }

    public function uninstall()
    {
        $this->drop_tables();
        ConfigManager::delete('devtools', 'config');
    }

    // -------------------------------------------------------------------------

    private function drop_tables()
    {
        PersistenceContext::get_dbms_utils()->drop([self::table()]);
    }

    /**
     * Creates the review cache table.
     * Stores file/content cross-references built by ReviewService so that the
     * File Review tab does not need to rebuild them on every page load.
     */
    private function create_review_table()
    {
        $fields = [
            'id'                 => ['type' => 'integer', 'length' => 11,       'autoincrement' => true, 'notnull' => 1],
            'file_path'          => ['type' => 'text',    'length' => 16777215],
            'file_link'          => ['type' => 'text',    'length' => 16777215],
            'edit_link'          => ['type' => 'text',    'length' => 16777215],
            'module_source'      => ['type' => 'string',  'length' => 255,      'notnull' => 1],
            'id_module_category' => ['type' => 'integer', 'length' => 11,       'notnull' => 1],
            'category_name'      => ['type' => 'string',  'length' => 255],
            'item_id'            => ['type' => 'integer', 'length' => 11,       'notnull' => 1],
            'item_title'         => ['type' => 'string',  'length' => 255,      'notnull' => 1],
            'id_in_module'       => ['type' => 'integer', 'length' => 11,       'notnull' => 1],
            'file_context'       => ['type' => 'text',    'length' => 16777215],
        ];
        $options = ['primary' => ['id']];

        PersistenceContext::get_dbms_utils()->create_table(self::table(), $fields, $options);
    }
}
?>
