<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 6.0 - 2022 11 03
*/

class NewsletterConfigUpdateVersion extends ConfigUpdateVersion
{

	public function __construct()
	{
		parent::__construct('newsletter');

		$this->config_parameters_to_modify = [
			'root_category_description' => [
				'parameter_name' => 'root_category_description',
				'value' => $this->get_parsed_old_content('NewsletterConfig', 'default_content')
			]
		];
	}
}
?>
