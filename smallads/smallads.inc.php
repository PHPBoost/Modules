<?php
/**
 *   smallads.inc.php
 *
 *   @author            julienseth78
 *   @license          	GPL Version 2
 */

defined('PHPBOOST') or exit;

define('SMALLADS_ITEMS_PER_PAGE',	10);
define('SMALLADS_MAX_LINKS',		3);
define('MAX_MINIMENU',		1);
define('MAX_FILESIZE_KO',	300);
define('MAXLEN_CONTENTS',	1000);
define('MAX_WEEKS',			12);

define('SMALLADS_OWN_CRUD_ACCESS',	0x01);
define('SMALLADS_UPDATE_ACCESS',	0x02);
define('SMALLADS_DELETE_ACCESS',	0x04);
define('SMALLADS_LIST_ACCESS',		0x08);
define('SMALLADS_CONTRIB_ACCESS',	0x10);
