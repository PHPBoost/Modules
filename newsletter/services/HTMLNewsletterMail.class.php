<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 3.0 - 2011 02 01
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class HTMLNewsletterMail extends AbstractNewsletterMail
{
	public function send_mail($subscribers, $sender, $subject, $content)
	{
		$content = $this->parse_content($content) . $this->add_unsubscribe_link();

		parent::send_mail($subscribers, $sender, $subject, $content);
	}

	public function display_mail($subject, $content)
	{
		return TextHelper::htmlspecialchars_decode(stripslashes($content));
	}

	public function parse_content($content)
	{
		$content = stripslashes($content);
		$content = $this->clean_html($content);
		return ContentSecondParser::export_html_text($content);
	}

	private function clean_html($content)
	{
		$content = TextHelper::htmlspecialchars($content, ENT_NOQUOTES);
		$content = str_replace(['&amp;', '&lt;', '&gt;'], ['&', '<', '>'], $content);
		return $content;
	}
}
?>
