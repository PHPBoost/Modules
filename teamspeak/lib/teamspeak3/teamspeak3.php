<?php

/**
 * @file
 * TeamSpeak 3 PHP Framework
 *
 * $Id: TeamSpeak3.php 12/3/2010 9:53:21 scp@orilla $
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package   TeamSpeak3
 * @version   1.1.2-beta
 * @author    Sven 'ScP' Paulsen
 * @copyright Copyright (c) 2010 by Planet TeamSpeak. All rights reserved.
 */

/**
 * @class TeamSpeak3
 * @brief Factory class all for TeamSpeak 3 PHP Framework objects.
 */
class TeamSpeak3
{
  /**
   * TeamSpeak 3 protocol welcome message.
   */
  const READY = "TS3";

  /**
   * TeamSpeak 3 protocol greeting message prefix.
   */
  const GREET = "Welcome";

  /**
   * TeamSpeak 3 protocol error message prefix.
   */
  const ERROR = "error";

  /**
   * TeamSpeak 3 protocol event message prefix.
   */
  const EVENT = "notify";

  /**
   * TeamSpeak 3 PHP Framework version.
   */
  const LIB_VERSION = "1.1.2-beta";

  /*@
   * TeamSpeak 3 protocol separators.
   */
  const SEPARATOR_LINE = "\n"; //!< protocol line separator
  const SEPARATOR_LIST = "|";  //!< protocol list separator
  const SEPARATOR_CELL = " ";  //!< protocol cell separator
  const SEPARATOR_PAIR = "=";  //!< protocol pair separator

  /*@
   * TeamSpeak 3 log levels.
   */
  const LOGLEVEL_CRITICAL         = 0x00; //!< 0: these messages stop the program
  const LOGLEVEL_ERROR            = 0x01; //!< 1: everything that is really bad
  const LOGLEVEL_WARNING          = 0x02; //!< 2: everything that might be bad
  const LOGLEVEL_DEBUG            = 0x03; //!< 3: output that might help find a problem
  const LOGLEVEL_INFO             = 0x04; //!< 4: informational output

  /*@
   * TeamSpeak 3 token types.
   */
  const TOKEN_SERVERGROUP         = 0x00; //!< 0: server group token  (id1={groupID} id2=0)
  const TOKEN_CHANNELGROUP        = 0x01; //!< 1: channel group token (id1={groupID} id2={channelID})

  /*@
   * TeamSpeak 3 codec identifiers.
   */
  const CODEC_SPEEX_NARROWBAND    = 0x00; //!< 0: speex narrowband     (mono, 16bit, 8kHz)
  const CODEC_SPEEX_WIDEBAND      = 0x01; //!< 1: speex wideband       (mono, 16bit, 16kHz)
  const CODEC_SPEEX_ULTRAWIDEBAND = 0x02; //!< 2: speex ultra-wideband (mono, 16bit, 32kHz)
  const CODEC_CELT_MONO           = 0x03; //!< 3: celt mono            (mono, 16bit, 48kHz)

  /*@
   * TeamSpeak 3 codec encryption modes.
   */
  const CODEC_CRYPT_INDIVIDUAL    = 0x00; //!< 0: configure per channel
  const CODEC_CRYPT_DISABLED      = 0x01; //!< 1: globally disabled
  const CODEC_CRYPT_ENABLED       = 0x02; //!< 2: globally enabled

  /*@
   * TeamSpeak 3 kick reason types.
   */
  const KICK_CHANNEL              = 0x04; //!< 4: kick client from channel
  const KICK_SERVER               = 0x05; //!< 5: kick client from server

  /*@
   * TeamSpeak 3 text message target modes.
   */
  const TEXTMSG_CLIENT            = 0x01; //!< 1: target is a client
  const TEXTMSG_CHANNEL           = 0x02; //!< 2: target is a channel
  const TEXTMSG_SERVER            = 0x03; //!< 3: target is a virtual server

  /*@
   * TeamSpeak 3 log levels.
   */
  const HOSTMSG_NONE              = 0x00; //!< 0: display no message
  const HOSTMSG_LOG               = 0x01; //!< 1: display message in chatlog
  const HOSTMSG_MODAL             = 0x02; //!< 2: display message in modal dialog
  const HOSTMSG_MODALQUIT         = 0x03; //!< 3: display message in modal dialog and close connection

  /*@
   * TeamSpeak 3 client identification types.
   */
  const CLIENT_TYPE_REGULAR       = 0x00; //!< 0: regular client
  const CLIENT_TYPE_SERVERQUERY   = 0x01; //!< 1: query client

  /*@
   * TeamSpeak 3 permission group database types.
   */
  const GROUP_DBTYPE_TEMPLATE     = 0x00; //!< 0: template group     (used for new virtual servers)
  const GROUP_DBTYPE_REGULAR      = 0x01; //!< 1: regular group      (used for regular clients)
  const GROUP_DBTYPE_SERVERQUERY  = 0x02; //!< 2: global query group (used for ServerQuery clients)

  /*@
   * TeamSpeak 3 permission group name modes.
   */
  const GROUP_NAMEMODE_HIDDEN     = 0x00; //!< 0: display no name
  const GROUP_NAMEMODE_BEFORE     = 0x01; //!< 1: display name before client nickname
  const GROUP_NAMEMODE_BEHIND     = 0x02; //!< 2: display name after client nickname

  /*@
   * TeamSpeak 3 permission group identification types.
   */
  const GROUP_IDENTIFIY_STRONGEST = 0x01; //!< 1: identify most powerful group
  const GROUP_IDENTIFIY_WEAKEST   = 0x02; //!< 2: identify weakest group

  /*@
   * TeamSpeak 3 permission types.
   */
  const PERM_TYPE_SERVERGROUP     = 0x00; //!< 0: server group permission
  const PERM_TYPE_CLIENT          = 0x01; //!< 1: client specific permission
  const PERM_TYPE_CHANNEL         = 0x02; //!< 2: channel specific permission
  const PERM_TYPE_CHANNELGROUP    = 0x03; //!< 3: channel group permission
  const PERM_TYPE_CHANNELCLIENT   = 0x04; //!< 4: channel-client specific permission

  /*@
   * TeamSpeak 3 file types.
   */
  const FILE_TYPE_DIRECTORY       = 0x00; //!< 0: file is directory
  const FILE_TYPE_REGULAR         = 0x01; //!< 1: file is regular

  /*@
   * TeamSpeak 3 server snapshot types.
   */
  const SNAPSHOT_STRING           = 0x00; //!< 0: default string
  const SNAPSHOT_BASE64           = 0x01; //!< 1: base64 string
  const SNAPSHOT_HEXDEC           = 0x02; //!< 2: hexadecimal string

  /*@
   * TeamSpeak 3 channel spacer types.
   */
  const SPACER_SOLIDLINE          = 0x00; //!< 0: solid line
  const SPACER_DASHLINE           = 0x01; //!< 1: dash line
  const SPACER_DOTLINE            = 0x02; //!< 2: dot line
  const SPACER_DASHDOTLINE        = 0x03; //!< 3: dash dot line
  const SPACER_DASHDOTDOTLINE     = 0x04; //!< 4: dash dot dot line
  const SPACER_CUSTOM             = 0x05; //!< 5: custom format

  /*@
   * TeamSpeak 3 channel spacer alignments.
   */
  const SPACER_ALIGN_LEFT         = 0x00; //!< 0: alignment left
  const SPACER_ALIGN_RIGHT        = 0x01; //!< 1: alignment right
  const SPACER_ALIGN_CENTER       = 0x02; //!< 2: alignment center
  const SPACER_ALIGN_REPEAT       = 0x03; //!< 3: repeat until the whole line is filled

  /**
   * Stores an array containing various chars which needs to be escaped while communicating
   * with a TeamSpeak 3 Server.
   *
   * @var array
   */
  protected static $escape_patterns = array(
    "\\" => "\\\\", // backslash
    "/"  => "\\/",  // slash
    " "  => "\\s",  // whitespace
    "|"  => "\\p",  // pipe
    ";"  => "\\;",  // semicolon
    "\a" => "\\a",  // bell
    "\b" => "\\b",  // backspace
    "\f" => "\\f",  // formfeed
    "\n" => "\\n",  // newline
    "\r" => "\\r",  // carriage return
    "\t" => "\\t",  // horizontal tab
    "\v" => "\\v"   // vertical tab
  );

  /**
   * Factory for TeamSpeak3_Adapter_Abstract classes. $uri must be formatted as
   * "<adapter>://<user>:<pass>@<host>:<port>/<options>#<flags>". All parameters
   * except adapter, host and port are optional.
   *
   * === Supported Options ===
   *   - timeout
   *   - blocking
   *   - nickname
   *   - no_query_clients
   *   - use_offline_as_virtual
   *   - clients_before_channels
   *   - server_id|server_uid|server_port|server_name
   *   - channel_id|channel_name
   *   - client_id|client_uid|client_name
   *
   * === Supported Flags (only one per $uri) ===
   *   - no_query_clients
   *   - use_offline_as_virtual
   *   - clients_before_channels
   *
   * === URI Examples ===
   *   - serverquery://127.0.0.1:10011/
   *   - serverquery://127.0.0.1:10011/?server_port=9987&channel_id=1
   *   - filetransfer://127.0.0.1:30011/
   *   - blacklist
   *   - update
   *
   * @param  string $uri
   * @return TeamSpeak3_Adapter_Abstract
   * @return TeamSpeak3_Node_Abstract
   */
  public static function factory($uri)
  {
    self::init();

    $uri = new TeamSpeak3_Helper_Uri($uri);

    $adapter = self::getAdapterName($uri->getScheme());
    $options = array("host" => $uri->getHost(), "port" => $uri->getPort(), "timeout" => intval($uri->getQueryVar("timeout", 10)), "blocking" => intval($uri->getQueryVar("blocking", 1)));

    self::loadClass($adapter);

    $object = new $adapter($options);

    if($object instanceof TeamSpeak3_Adapter_ServerQuery)
    {
      $node = $object->getHost();

      if($uri->hasUser() && $uri->hasPass())
      {
        $node->login($uri->getUser(), $uri->getPass());
      }

      /* option to pre-define nickname */
      if($uri->hasQueryVar("nickname"))
      {
        $node->setPredefinedQueryName($uri->getQueryVar("nickname"));
      }

      /* flag to use offline servers in virtual mode */
      if($uri->getFragment() == "use_offline_as_virtual")
      {
        $node->setUseOfflineAsVirtual(TRUE);
      }
      elseif($uri->hasQueryVar("use_offline_as_virtual"))
      {
        $node->setUseOfflineAsVirtual($uri->getQueryVar("use_offline_as_virtual") ? TRUE : FALSE);
      }

      /* flag to fetch clients before sub-channels */
      if($uri->getFragment() == "clients_before_channels")
      {
        $node->setLoadClientlistFirst(TRUE);
      }
      elseif($uri->hasQueryVar("clients_before_channels"))
      {
        $node->setLoadClientlistFirst($uri->getQueryVar("clients_before_channels") ? TRUE : FALSE);
      }

      /* flag to hide ServerQuery clients */
      if($uri->getFragment() == "no_query_clients")
      {
        $node->setExcludeQueryClients(TRUE);
      }
      elseif($uri->hasQueryVar("no_query_clients"))
      {
        $node->setExcludeQueryClients($uri->getQueryVar("no_query_clients") ? TRUE : FALSE);
      }

      /* access server node object */
      if($uri->hasQueryVar("server_id"))
      {
        $node = $node->serverGetById($uri->getQueryVar("server_id"));
      }
      elseif($uri->hasQueryVar("server_uid"))
      {
        $node = $node->serverGetByUid($uri->getQueryVar("server_uid"));
      }
      elseif($uri->hasQueryVar("server_port"))
      {
        $node = $node->serverGetByPort($uri->getQueryVar("server_port"));
      }
      elseif($uri->hasQueryVar("server_name"))
      {
        $node = $node->serverGetByName($uri->getQueryVar("server_name"));
      }

      if($node instanceof TeamSpeak3_Node_Server)
      {
        /* access channel node object */
        if($uri->hasQueryVar("channel_id"))
        {
          $node = $node->channelGetById($uri->getQueryVar("channel_id"));
        }
        elseif($uri->hasQueryVar("channel_name"))
        {
          $node = $node->channelGetByName($uri->getQueryVar("channel_name"));
        }

        /* access client node object */
        if($uri->hasQueryVar("client_id"))
        {
          $node = $node->clientGetById($uri->getQueryVar("client_id"));
        }
        if($uri->hasQueryVar("client_uid"))
        {
          $node = $node->clientGetByUid($uri->getQueryVar("client_uid"));
        }
        elseif($uri->hasQueryVar("client_name"))
        {
          $node = $node->clientGetByName($uri->getQueryVar("client_name"));
        }
      }

      return $node;
    }

    return $object;
  }

  /**
   * Loads a class from a PHP file. The filename must be formatted as "$class.php".
   *
   * include() is not prefixed with the @ operator because if the file is loaded and
   * contains a parse error, execution will halt silently and this is difficult to debug.
   *
   * @param  string $class
   * @throws LogicException
   * @return boolean
   */
  protected static function loadClass($class)
  {
    if(class_exists($class, FALSE) || interface_exists($class, FALSE))
    {
      return;
    }

    if(preg_match("/[^a-z0-9\\/\\\\_.-]/i", $class))
    {
      throw new LogicException("illegal characters in classname '" . $class . "'");
    }

    $file = self::getFilePath($class) . ".php";

    if(!file_exists($file) || !is_readable($file))
    {
      throw new LogicException("file '" . $file . "' does not exist or is not readable");
    }

    if(class_exists($class, FALSE) || interface_exists($class, FALSE))
    {
      throw new LogicException("class '" . $class . "' does not exist");
    }

    return include_once($file);
  }

  /**
   * Generates a possible file path for $name.
   *
   * @param  string $name
   * @return string
   */
  protected static function getFilePath($name)
  {
    $path = str_replace("_", DIRECTORY_SEPARATOR, $name);
    $path = str_replace(__CLASS__, dirname(__FILE__), $path);

    return $path;
  }

  /**
   * Returns the name of an adapter class by $name.
   *
   * @param  string $name
   * @param  string $namespace
   * @throws TeamSpeak3_Adapter_Exception
   * @return string
   */
  protected static function getAdapterName($name, $namespace = "TeamSpeak3_Adapter_")
  {
    $path = self::getFilePath($namespace);
    $scan = scandir($path);

    foreach($scan as $node)
    {
      $file = TeamSpeak3_Helper_String::factory($node)->toLower();

      if($file->startsWith($name) && $file->endsWith(".php"))
      {
        return $namespace . str_replace(".php", "", $node);
      }
    }

    throw new TeamSpeak3_Adapter_Exception("adapter '" . $name . "' does not exist");
  }

  /**
   * spl_autoload() suitable implementation for supporting class autoloading.
   *
   * @param  string $class
   * @return boolean
   */
  public static function autoload($class)
  {
    if(mb_substr($class, 0, mb_strlen(__CLASS__)) != __CLASS__) return;

    try
    {
      self::loadClass($class);

      return TRUE;
    }
    catch(Exception $e)
    {
      return FALSE;
    }
  }

  /**
   * Checks for required PHP features, enables autoloading and starts a default profiler.
   *
   * @throws LogicException
   * @return void
   */
  public static function init()
  {
    if(version_compare(phpversion(), "5.2.1") == -1)
    {
      throw new LogicException("this particular software cannot be used with the installed version of PHP");
    }

    if(!function_exists("stream_socket_client"))
    {
      throw new LogicException("network functions are not available in this PHP installation");
    }

    if(!function_exists("spl_autoload_register"))
    {
      throw new LogicException("autoload functions are not available in this PHP installation");
    }

    if(!class_exists("TeamSpeak3_Helper_Profiler"))
    {
      spl_autoload_register(array(__CLASS__, "autoload"));
    }

    TeamSpeak3_Helper_Profiler::start();
  }

  /**
   * Returns an assoc array containing all escape patterns available on a TeamSpeak 3
   * Server.
   *
   * @return array
   */
  public static function getEscapePatterns()
  {
    return self::$escape_patterns;
  }

  /**
   * Debug helper function. This is a wrapper for var_dump() that adds the pre-format tags,
   * cleans up newlines and indents, and runs htmlentities() before output.
   *
   * @param  mixed  $var
   * @param  bool   $echo
   * @return string
   */
  public static function dump($var, $echo = TRUE)
  {
    ob_start();
    var_dump($var);

    $output = ob_get_clean();
    $output = preg_replace("/\]\=\>\n(\s+)/m", "] => ", $output);

    if(PHP_SAPI == "cli")
    {
      $output = PHP_EOL . PHP_EOL . $output . PHP_EOL;
    }
    else
    {
      $output = "<pre>" . htmlspecialchars($output, ENT_QUOTES) . "</pre>";
    }

    if($echo) echo($output);

    return $output;
  }
}

/*!
 * \mainpage API Documentation
 *
 * \section welcome_sec Introduction
 *
 * \subsection welcome1 What is the TS3 PHP Framework?
 * Initially released in January 2010, the TS3 PHP Framework is a powerful, open source, object-oriented framework
 * implemented in PHP 5 and licensed under the GNU General Public License. It's based on simplicity and a rigorously
 * tested agile codebase. Extend the functionality of your servers with scripts or create powerful web applications
 * to manage all features of your TeamSpeak 3 Server instances.
 *
 * Tested. Thoroughly. Enterprise-ready and built with agile methods, the TS3 PHP Framework has been unit-tested from
 * the start to ensure that all code remains stable and easy for you to extend, re-test with your extensions, and
 * further maintain.
 *
 * \subsection welcome2 Why should I use the TS3 PHP Framework rather than other PHP libraries?
 * The TS3 PHP Framework is a is a modern use-at-will framework that provides individual components to communicate
 * with the TeamSpeak 3 Server.
 *
 * There are lots of arguments for the TS3 PHP Framework in comparison with other PHP based libraries. It is the most
 * dynamic and feature-rich piece of software in its class. In addition, it's the only PHP library that parses the data
 * spit out of the ServerQuery interface correctly.
 *
 * \section sysreqs_sec Requirements
 * The TS3 PHP Framework currently supports PHP 5.2.1 or later, but we strongly recommend the most current release of
 * PHP for critical security and performance enhancements. If you want to create a web application using the TS3 PHP
 * Framework, you need a PHP 5 interpreter with a web server configured to handle PHP scripts correctly.
 *
 * Note that the majority of TS3 PHP Framework development and deployment is done on Apache, so there is more community
 * experience and testing performed on Apache than on other web servers.
 *
 * \section feature_sec Features
 * Features of the TS3 PHP Framework include:
 *
 *   - Fully object-oriented PHP 5 and E_STRICT compliant components
 *   - Access to all TeamSpeak 3 Server features via ServerQuery
 *   - Integrated full featured and customizable TSViewer interfaces
 *   - Full support for file transfers to up- and /or download custom icons and other stuff
 *   - Powerful error handling capablities using exceptions and customizable error messages
 *   - Query mechanisms for several official services such as the blacklist and auto-update servers
 *   - Dynamic signal slots for event based scripting
 *   - ...
 *
 * \section example_sec Usage Examples
 *
 * \subsection example1 1. Kick all Clients from a Virtual Server
 * @code
 *   // connect to local server, authenticate and quickly spawn an object for the virtual server on port 9987
 *   $ts3_VirtualServer = TeamSpeak3::factory("serverquery://username:password@127.0.0.1:10011/?server_port=9987");
 *
 *   // query clientlist from virtual server
 *   $arr_ClientList = $ts3_VirtualServer->clientList();
 *
 *   // kick all clients online with a single command
 *   $ts3_VirtualServer->clientKick($arr_ClientList, TeamSpeak3::KICK_SERVER, "evil kick XD");
 * @endcode
 *
 * \subsection example2 2. Modify the Settings of each Virtual Server
 * @code
 *   // connect to local server, authenticate and quickly spawn an object for the server instance
 *   $ts3_ServerInstance = TeamSpeak3::factory("serverquery://username:password@127.0.0.1:10011/?use_offline_as_virtual=1");
 *
 *   // walk through list of virtual servers
 *   foreach($ts3_ServerInstance as $ts3_VirtualServer)
 *   {
 *     // modify the virtual servers hostbanner URL only
 *     $ts3_VirtualServer["virtualserver_hostbanner_gfx_url"] = "http://www.example.com/banners/banner01_468x60.jpg";
 *
 *     // modify multiple virtual server properties at once
 *     $ts3_VirtualServer->modify(
 *       "virtualserver_hostbutton_tooltip" => "My Company",
 *       "virtualserver_hostbutton_url"     => "http://www.example.com",
 *       "virtualserver_hostbutton_gfx_url" => "http://www.example.com/buttons/button01_24x24.jpg",
 *     );
 *   }
 * @endcode
 *
 * \subsection example3 3. Modify the Permissions of Admins on each Virtual Server
 * @code
 *   // connect to local server, authenticate and quickly spawn an object for the server instance
 *   $ts3_ServerInstance = TeamSpeak3::factory("serverquery://username:password@127.0.0.1:10011/?use_offline_as_virtual=1");
 *
 *   // walk through list of virtual servers
 *   foreach($ts3_ServerInstance as $ts3_VirtualServer)
 *   {
 *     // identify the most powerful group on the virtual server
 *     $ts3_ServerGroup = $ts3_VirtualServer->serverGroupIdentify();
 *
 *     // assign a new permission
 *     $ts3_ServerGroup->permAssign("b_virtualserver_modify_hostbanner", TRUE);
 *
 *     // revoke an existing permission
 *     $ts3_ServerGroup->permRemove("b_virtualserver_modify_maxclients");
 *   }
 * @endcode
 *
 * \subsection example4 4. Create a hierarchical Channel Stucture
 * @code
 *   // connect to local server, authenticate and quickly spawn an object for the virtual server on port 9987
 *   $ts3_VirtualServer = TeamSpeak3::factory("serverquery://username:password@127.0.0.1:10011/?server_port=9987");
 *
 *   // create a top-level channel and get its ID
 *   $top_cid = $ts3_VirtualServer->channelCreate(
 *     "channel_name"           => "My Channel",
 *     "channel_topic"          => "This is a top-level channel",
 *     "channel_codec"          => TeamSpeak3::CODEC_SPEEX_WIDEBAND,
 *     "channel_flag_permanent" => TRUE,
 *   );
 *
 *   // create a sub-level channel and get its ID
 *   $sub_cid = $ts3_VirtualServer->channelCreate(
 *     "channel_name"           => "My Sub-Channel",
 *     "channel_topic"          => "This is a sub-level channel",
 *     "channel_codec"          => TeamSpeak3::CODEC_SPEEX_NARROWBAND,
 *     "channel_flag_permanent" => TRUE,
 *     "cpid"                   => $top_cid,
 *   );
 * @endcode
 *
 * \subsection example5 5. Send a Text Message to outdated Clients
 * @code
 *   // connect to local server, authenticate and quickly spawn an object for the virtual server on port 9987
 *   $ts3_VirtualServer = TeamSpeak3::factory("serverquery://username:password@127.0.0.1:10011/?server_port=9987");
 *
 *   // connect to default update server
 *   $ts3_UpdateServer = TeamSpeak3::factory("update");
 *
 *   // walk through list of clients on virtual server
 *   foreach($ts3_VirtualServer->clientList() as $ts3_Client)
 *   {
 *     // skip query clients
 *     if($ts3_Client["client_type"]) continue;
 *
 *     // send test message if client build is outdated
 *     if($ts3_Client->getRev() < $ts3_UpdateServer->getRev())
 *     {
 *       $ts3_Client->message("[COLOR=red]your client is [B]outdated[/B]... update now![/COLOR]");
 *     }
 *   }
 * @endcode
 *
 * Speed up new development and reduce maintenance costs by using the TS3 PHP Framework!
 */
