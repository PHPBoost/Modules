<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 06
 * @since       PHPBoost 6.0 - 2021 10 30
*/

class FluxCategoriesCache extends DefaultRichCategoriesCache
{
	public function get_module_identifier(): string
	{
		return 'flux';
	}

	protected function get_category_elements_number(int $id_category): int
	{
		$now = new Date();
		return FluxService::count('WHERE id_category = :id_category AND published = 1',
			[
				'timestamp_now' => $now->get_timestamp(),
				'id_category' => $id_category
            ]
		);
	}

	protected function get_root_category_authorizations(): array
	{
		return FluxConfig::load()->get_authorizations();
	}

	protected function get_root_category_description(): string
	{
		$description = FluxConfig::load()->get_root_category_description();
		if (empty($description))
        {
			$description = StringVars::replace_vars(LangLoader::get_message('flux.seo.description.root', 'common', 'flux'), ['site' => GeneralConfig::load()->get_site_name()]);
        }
		return $description;
	}
}
?>
