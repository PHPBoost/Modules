<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 12 07
 * @since       PHPBoost 5.1 - 2018 03 15
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class SmalladsCategoriesCache extends DefaultRichCategoriesCache
{
	public function get_module_identifier()
	{
		return 'smallads';
	}

	protected function get_category_elements_number($id_category)
	{
		$now = new Date();
		return SmalladsService::count('WHERE id_category = :id_category AND (published = 1 OR (published = 2 AND publishing_start_date < :timestamp_now AND (publishing_end_date > :timestamp_now OR publishing_end_date = 0)))',
			array(
				'timestamp_now' => $now->get_timestamp(),
				'id_category' => $id_category
			)
		);
	}

	protected function get_root_category_authorizations()
	{
		return SmalladsConfig::load()->get_authorizations();
	}

	protected function get_root_category_description()
	{
		$description = SmalladsConfig::load()->get_root_category_description();
		if (empty($description))
			$description = StringVars::replace_vars(LangLoader::get_message('smallads.seo.description.root', 'common', 'smallads'), array('site' => GeneralConfig::load()->get_site_name()));
		return $description;
	}
}
?>
