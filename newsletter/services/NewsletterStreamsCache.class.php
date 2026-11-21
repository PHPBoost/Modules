<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 21
 * @since       PHPBoost 4.0 - 2014 05 21
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class NewsletterStreamsCache extends CategoriesCache
{
	public function get_table_name(): string
	{
		return NewsletterSetup::$newsletter_table_streams;
	}

	public function get_table_name_containing_items(): string
	{
		return NewsletterSetup::$newsletter_table_archives;
	}

	public function get_category_class(): string
	{
		return 'NewsletterStream';
	}

	public function get_module_identifier(): string
	{
		return 'newsletter';
	}

	public function get_root_category(): mixed
	{
		$root = new RichRootCategory();
		$root->set_authorizations(NewsletterConfig::load()->get_authorizations());
		return $root;
	}

	public function get_streams(): array
	{
		return $this->get_categories();
	}

	public function stream_exists(int $id): bool
	{
		return array_key_exists($id, $this->get_categories());
	}

	public function get_stream(int $id): mixed
	{
		return $this->get_category($id);
	}
}
?>
