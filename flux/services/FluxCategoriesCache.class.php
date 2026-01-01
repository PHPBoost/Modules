<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 11 09
 * @since       PHPBoost 6.0 - 2021 10 30
*/

class FluxCategoriesCache extends DefaultRichCategoriesCache
{
	public function get_module_identifier()
	{
		return 'flux';
	}

	protected function get_category_elements_number($id_category)
	{
		$now = new Date();
		return FluxService::count('WHERE id_category = :id_category AND published = 1',
			array(
				'timestamp_now' => $now->get_timestamp(),
				'id_category' => $id_category
			)
		);
	}

	protected function get_root_category_authorizations()
	{
		return FluxConfig::load()->get_authorizations();
	}

	protected function get_root_category_description()
	{
		$description = FluxConfig::load()->get_root_category_description();
		if (empty($description))
			$description = StringVars::replace_vars(LangLoader::get_message('flux.seo.description.root', 'common', 'flux'), array('site' => GeneralConfig::load()->get_site_name()));
		return $description;
	}
}
?>
