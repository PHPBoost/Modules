<?php
/**
 *   quotes.inc.php
 *
 *   @author            alain91
 *   @license          	GPL Version 2
 */

defined('PHPBOOST') or exit;

define('QUOTES_LIST_ACCESS',		0x01);
define('QUOTES_CONTRIB_ACCESS',		0x02);
define('QUOTES_WRITE_ACCESS',		0x04);

class Quotes
{
	var $cats = null;
	
	function __construct()
	{
		$this->cats = new QuotesCats();
	}
	
	function Quotes()
	{
		$this->__construct();
	}

	function lang_get($value)
	{
		global $QUOTES_LANG, $LANG;
		
		if (is_string($value)) {
			if (!empty($QUOTES_LANG[$value]))
				return $QUOTES_LANG[$value]; // recherche locale
			if (!empty($LANG[$value]))
				return $LANG[$value]; // recherche globale
			return $value;
		}
		return 'invalid_value';
	}
	
	function sanitize($args)
	{
		if (is_array($args))
		{
			$data = array();
			foreach ($args as $key => $val)
			{
				$data[$key] = $this->sanitize($val);
			}
			return $data;
		}

		if(  (function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc())
			|| (ini_get('magic_quotes_sybase') && (strtolower(ini_get('magic_quotes_sybase')) != 'off')) )
		{
			$data = stripslashes($args);
		}
		else
		{
			$data = $args;
		}
		$data = str_replace(array("\r\n", "\r"), "\n", $data);
		return $data;
	}
}
