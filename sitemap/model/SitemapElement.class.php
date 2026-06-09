<?php
/**
 * This abstract is the root of every object which can be contained by a Sitemap object.
 * Some SitemapElements objects can contain one or many SitemapElement objects therefore the elements
 * can be represented by a tree an each element has a depth in the tree.
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 3.0 - 2009 02 03
 * @author      Arnaud GENET <elenwii@phpboost.com>
*/

abstract class SitemapElement
{
	/**
	 * @var int Depth of the element in the elements tree
	 */
	var $depth = 1;

	/**
	 * Builds a SitemapElement object
	 * @param string $name Name of the object
	 */
	public function __construct()
	{
	}

	/**
	 * Returns the depth of the element in the tree
	 * @return int depth
	 */
	public function get_depth()
	{
		return $this->depth;
	}

	/**
	 * Sets the depth of the element
	 * @param int $depth the depth of the element
	 */
	public function set_depth($depth)
	{
		$this->depth = $depth;
	}

	/**
	 * Returns the name of the menu
	 * @return string name
	 */
	public abstract function get_name();

	/**
	 * Exports the element
	 * @param SitemapExportConfig $export_config Export configuration
	 * @param int $depth Depth of the element
	 * @return string The exported code
	 */
	public abstract function export(SitemapExportConfig  $export_config);
}
?>
