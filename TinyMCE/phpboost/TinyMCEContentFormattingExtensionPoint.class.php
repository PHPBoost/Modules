<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 09
 * @since       PHPBoost 3.0 - 2011 10 11
*/

class TinyMCEContentFormattingExtensionPoint extends AbstractContentFormattingExtensionPoint
{
	public function get_name(): string
	{
		return 'TinyMCE';
	}

	public function get_parser(): TinyMCEParser
	{
		$parser = new TinyMCEParser();
		$parser->set_forbidden_tags($this->get_forbidden_tags());
		$parser->set_html_auth($this->get_html_auth());
		return $parser;
	}

	public function get_unparser(): TinyMCEUnparser
	{
		return new TinyMCEUnparser();
	}

	public function get_second_parser(): ContentSecondParser
	{
		return new ContentSecondParser();
	}

	public function get_editor(): TinyMCEEditor
	{
		$editor = new TinyMCEEditor();
		$editor->set_forbidden_tags($this->get_forbidden_tags());
		return $editor;
	}
}
?>
