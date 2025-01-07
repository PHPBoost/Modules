<?php

/**
 * @file
 * TeamSpeak 3 PHP Framework
 *
 * $Id: Convert.php 12/3/2010 9:53:21 scp@orilla $
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
 * @class TeamSpeak3_Helper_Convert
 * @brief Helper class for data conversion.
 */
class TeamSpeak3_Helper_Convert
{
  /**
   * Converts bytes to a human readable value.
   *
   * @param  integer $bytes
   * @return string
   */
  public static function bytes($bytes)
  {
    $kbytes = sprintf("%.02f", $bytes/1024);
    $mbytes = sprintf("%.02f", $kbytes/1024);
    $gbytes = sprintf("%.02f", $mbytes/1024);
    $tbytes = sprintf("%.02f", $gbytes/1024);

    if($tbytes >= 1)
      return $tbytes . " TB";
    if($gbytes >= 1)
      return $gbytes . " GB";
    if($mbytes >= 1)
      return $mbytes . " MB";
    if($kbytes >= 1)
      return $kbytes . " KB";

    return $bytes . " B";
  }

  /**
   * Converts seconds/milliseconds to a human readable value.
   *
   * @param  integer $seconds
   * @param  boolean $is_ms
   * @param  string  $format
   * @return string
   */
  public static function seconds($seconds, $is_ms = FALSE, $format = "%dD %02d:%02d:%02d")
  {
    if($is_ms) $seconds = $seconds/1000;

    return sprintf($format, $seconds/60/60/24, ($seconds/60/60)%24, ($seconds/60)%60, $seconds%60);
  }

  /**
   * Converts a given codec ID to a human readable name.
   *
   * @param  integer $codec
   * @return string
   */
  public static function codec($codec)
  {
    if($codec == TeamSpeak3::CODEC_SPEEX_NARROWBAND)
      return "Speex Narrowband (8 kHz)";
    if($codec == TeamSpeak3::CODEC_SPEEX_WIDEBAND)
      return "Speex Wideband (16 kHz)";
    if($codec == TeamSpeak3::CODEC_SPEEX_ULTRAWIDEBAND)
      return "Speex Ultra-Wideband (32 kHz)";
    if($codec == TeamSpeak3::CODEC_CELT_MONO)
      return "CELT Mono (48 kHz)";

    return "Unknown";
  }

  /**
   * Converts a given group type ID to a human readable name.
   *
   * @param  integer $type
   * @return string
   */
  public static function groupType($type)
  {
    if($type == TeamSpeak3::GROUP_DBTYPE_TEMPLATE)
      return "Template";
    if($type == TeamSpeak3::GROUP_DBTYPE_REGULAR)
      return "Regular";
    if($type == TeamSpeak3::GROUP_DBTYPE_SERVERQUERY)
      return "ServerQuery";

    return "Unknown";
  }

  /**
   * Converts a given permission type ID to a human readable name.
   *
   * @param  integer $type
   * @return string
   */
  public static function permissionType($type)
  {
    if($type == TeamSpeak3::PERM_TYPE_SERVERGROUP)
      return "Server Group";
    if($type == TeamSpeak3::PERM_TYPE_CLIENT)
      return "Client";
    if($type == TeamSpeak3::PERM_TYPE_CHANNEL)
      return "Channel";
    if($type == TeamSpeak3::PERM_TYPE_CHANNELGROUP)
      return "Channel Group";
    if($type == TeamSpeak3::PERM_TYPE_CHANNELCLIENT)
      return "Channel Client";

    return "Unknown";
  }

  /**
   * Converts a given log level ID to a human readable name.
   *
   * @param  integer $level
   * @return string
   */
  public static function logLevel($level)
  {
    if($level == TeamSpeak3::LOGLEVEL_CRITICAL)
      return "CRITICAL";
    if($level == TeamSpeak3::LOGLEVEL_ERROR)
      return "ERROR";
    if($level == TeamSpeak3::LOGLEVEL_DEBUG)
      return "DEBUG";
    if($level == TeamSpeak3::LOGLEVEL_WARNING)
      return "WARNING";
    if($level == TeamSpeak3::LOGLEVEL_INFO)
      return "INFO";

    return "DEVELOP";
  }

  /**
   * Converts a given string to a ServerQuery password hash.
   *
   * @param  string $plain
   * @return string
   */
  public static function password($plain)
  {
    return base64_encode(sha1($plain, TRUE));
  }
}
