<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Patrick DUBEAU <daaxwizeman@gmail.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 4.0 - 2013 02 27
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Arnaud GENET <elenwii@phpboost.com>
 * @author      mipel <mipel@phpboost.com>
 * @author      janus57 <janus57@janus57.fr>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @author      xela <xela@phpboost.com>
*/

class ArticlesItem extends RichItem
{
	public function get_real_summary($parsed_content = '')
	{
		$summary = $this->get_additional_property('summary');

		if (!empty($summary))
		{
			return FormatingHelper::second_parse($summary);
		}
		else
		{
			$clean_content = preg_split('`\[page\].+\[/page\](.*)`usU', $this->content, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
			return TextHelper::cut_string(@strip_tags($clean_content[0], '<br><br/>'), (int)ArticlesConfig::load()->get_auto_cut_characters_number());
		}
	}
}
?>
