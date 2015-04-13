<?php
/**
 * smallads.class.php
 * 
 * @author            alain91
 * @copyright      (C) 2009 Alain Gandon
 * @email              alain091@gmail.com
 * @license           GPL
 */
 
class Smallads
{
	private $current_user;
	
	public function __construct()
	{
		$this->current_user = AppContext::get_current_user();
	}
	
    /**
	*  @method  Actions sur changement de jour
	*/
	function on_changeday()
	{
		global $Cache, $CONFIG_SMALLADS;

		$Cache->load('smallads');

		$delay = empty($CONFIG_SMALLADS['max_weeks']) ? 0 : abs(intval($CONFIG_SMALLADS['max_weeks']));
		if (!empty($delay))
		{
			$sql = "DELETE FROM ".PREFIX."smallads
				WHERE (approved = 1)
					AND (DATEDIFF(NOW(), FROM_UNIXTIME(date_approved)) > 7 * IFNULL(max_weeks, " . $delay . "))";
			$result = PersistenceContext::get_sql()->query_inject($sql, __LINE__, __FILE__);
		}

		$Cache->generate_module_file('smallads');
	}
	
    /**
     * @desc Check if access OK
     * @param int mask of the access
     * @return bool True, if the access is OK, false otherwise.
     */
	function access_ok($mask)
	{
		global $CONFIG_SMALLADS;

		if ($this->current_user->is_admin())
		{
			return TRUE;
		}
		elseif (isset($CONFIG_SMALLADS['auth']))
		{
			return $this->current_user->check_auth($CONFIG_SMALLADS['auth'], $mask);
		}
		return FALSE; //Fall thru
	}

    /**
     * @desc Check if access global or local
     * @param int global mask
     * @param int local mask
     * @param int owner id
     * @return bool True, if the access is OK, false otherwise.
     */
	function check_access($global, $local, $owner)
	{
		$user = $this->current_user->get_id();
		$result = (($user >= 0)
					&& ($this->access_ok($global) 
						|| ($this->access_ok($local) && ($owner == $user))
						)
					);
		return $result;
	}
	
    /**
     * @desc Check authorisation
     * @param int mask of the access
     * @return bool True, if the access is OK, throw exception otherwise.
     */
	function check_autorisation($mask)
	{
		if (!$this->access_ok($mask))
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}

    /**
     * @desc Get a config parameter
     * @param string name of parameter
     * @param string default value
     * @return value of parameter, default value otherwise
     */
	function config_get($name, $default)
	{
		global $CONFIG_SMALLADS;
		
		if (is_string($name))
		{
			return !empty($CONFIG_SMALLADS[$name]) ? $CONFIG_SMALLADS[$name] : $default;
		}
		return FALSE;
	}

    /**
     * @desc Build selected attribute
     * @param string name of argument
     * @param mixed  value of argument
     * @return selected string if match, empty string otherwise
     */
	function selected($name, $value)
	{
		return ($name == $value) ? 'selected="selected"' : '';
	}

    /**
     * @desc Upload a picture
     * @param int id of reference
     * @return void, Throw an exception in case of error
     */	
	function upload_picture($id)
	{
		if ( !empty($_FILES['smallads_picture']['name']) ) //Upload
		{ 
			$dir = 'pics/';
			$upload = new Upload($dir);
			
			if (is_writable($dir))
			{
				$upload->file('smallads_picture', '`\.(jpeg|jpg|gif|png)$`i', FALSE, MAX_FILESIZE_KO, FALSE);
				if (!empty($upload->error)) //Erreur, on arrête ici
				{
					$controller = new UserErrorController(LangLoader::get_message('error', 'status-messages-common'), $upload->error);
					DispatchManager::redirect($controller);
				}
				else
				{
					$path = $dir . $upload->get_filename();
					
					$width  = $this->config_get('width_maxi', 130);
					$height = $this->config_get('height_maxi', 130);
					
					$res = $this->Resize_pics($path, $id, $width, $height);
					if ( !empty($smallads->error) )
					{
						$controller = new UserErrorController(LangLoader::get_message('error', 'status-messages-common'), $smallads->error);
						DispatchManager::redirect($controller);
					}
				}
			}
			else
			{
				DispatchManager::redirect(PHPBoostErrors::unknow());
			}
		}
	}
	
    /**
     * @desc Resize a picture
	 * @param string path of picture
     * @param int id of reference
	 * @param int maximum width
	 * @param int maximum height
     * @return boolean, Throw exception in case of error
     */	
	function Resize_pics($path, $id, $dst_w = 150, $dst_h = 150)
	{
		global $LANG, $Sql;
		
		$this->error = '';
		if (file_exists($path))
		{
			$pic_infos = @getimagesize($path);
			if ($pic_infos === false)
			{
				DispatchManager::redirect(PHPBoostErrors::unknow());
			}
			
			$width = $dst_w;
			$height = $dst_h;
			
			list($src_w, $src_h, $src_type) = $pic_infos;
			// Teste les dimensions tenant dans la zone
			$test_h = round(($dst_w / $src_w) * $src_h);
			$test_w = round(($dst_h / $src_h) * $src_w);
			// Si Height ou Width final non précisé (0)
			if (!$dst_h)
				$height = $test_h;
			elseif (!$dst_w)
				$width = $test_w;
			// Sinon teste quel redimensionnement tient dans la zone
			elseif ($test_h > $dst_h)
				$width = $test_w;
			else
				$height = $test_h;

			switch($src_type)
			{
				case IMAGETYPE_JPEG :
					$source = @imagecreatefromjpeg($path);
					$ext = '.jpg';
					break;
				case IMAGETYPE_GIF :
					$source = @imagecreatefromgif($path);
					$ext = '.gif';
					break;
				case IMAGETYPE_PNG :
					$source = @imagecreatefrompng($path);
					$ext = '.png';
					break;
				default: 
					$source = false;
			}
			
			if ($source === false)
			{
				$this->error = 'sa_unsupported_format';
				return  false;				
			}
			else
			{
				//Préparation de l'image redimensionnée.
				if (!function_exists('imagecreatetruecolor'))
				{	
					$thumbnail = @imagecreate($width, $height);
					if ($thumbnail === false)
					{
						$this->error = 'sa_unabled_create_pics';
						return false;
					}
				}
				else
				{	
					$thumbnail = @imagecreatetruecolor($width, $height);
					if ($thumbnail === false)
					{
						$this->error = 'sa_unabled_create_pics';
						return false;
					}
				}
				
				//Redimensionnement.
				if (!function_exists('imagecopyresampled'))
				{	
					if (@imagecopyresized($thumbnail, $source, 0, 0, 0, 0, $width, $height, $src_w, $src_h) === false)
					{
						$this->error = 'sa_error_resize';
						return false;
					}
				}
				else
				{	
					if (@imagecopyresampled($thumbnail, $source, 0, 0, 0, 0, $width, $height, $src_w, $src_h) === false)
					{
						$this->error = 'sa_error_resample';
						return false;
					}
				}
			}

			switch($src_type)
			{
				case IMAGETYPE_JPEG :
					$res = @imagejpeg($thumbnail, dirname($path).'/'.$id.$ext);
					break;
				case IMAGETYPE_GIF :
					$res = @imagegif($thumbnail, dirname($path).'/'.$id.$ext);
					break;
				case IMAGETYPE_PNG :
					$res = @imagepng($thumbnail, dirname($path).'/'.$id.$ext);
					break;
				default: 
					$res = false;
			}
			@imagedestroy($thumbnail);
			@unlink($path);
	
			if ($res === false)
			{
				$this->error = 'sa_unsupported_format';
			}
			else
			{

				$Sql->query_inject(
					"UPDATE ".PREFIX."smallads
					SET picture = '" . $id.$ext . "'
					WHERE id ='".$id."'
					LIMIT 1",
					__LINE__, __FILE__);
			}
			return empty($this->error); // true si not error
		}
		
		$this->error = 'sa_unabled_create_pics'; // FALL THRU
		return false;
	}
	
	function contribution_add($id, $description)
	{
		global $CONFIG_SMALLADS, $LANG;

		$user_id = $this->current_user->get_id();
		$contribution = new Contribution();
		$contribution->set_id_in_module($id);
		$contribution->set_description($description);
		$contribution->set_entitled(sprintf($LANG['sa_contribution_entitled'], $id));
		$contribution->set_fixing_url('/smallads/smallads.php?edit=' . $id);
		$contribution->set_poster_id($user_id);
		$contribution->set_module('smallads');
		$contribution->set_auth(Authorizations::capture_and_shift_bit_auth($CONFIG_SMALLADS['auth'], SMALLADS_UPDATE_ACCESS, SMALLADS_CONTRIB_ACCESS));
		ContributionService::save_contribution($contribution);
	}
	
	function contribution_set_processed($id)
	{
		$contributions = ContributionService::find_by_criteria('smallads', $id);
		if (count($contributions) > 0)
		{
			foreach($contributions as $contribution) {
				$contribution->set_status(EVENT_STATUS_PROCESSED);
				ContributionService::save_contribution($contribution);
			}
		}
	}
	
	function contribution_update($id, $description)
	{
		global $LANG;
		
		$contributions = ContributionService::find_by_criteria('smallads', $id);
		if (count($contributions) > 0)
		{
			foreach($contributions as $contribution) { // On tente d'actualiser une demande existante
				if ( EVENT_STATUS_UNREAD == $contribution->get_status())
				{
					$date = new Date();
					$contribution->set_creation_date($date);
					$contribution->set_description($description);
					$contribution->set_entitled(sprintf($LANG['sa_contribution_entitled'], $id));
					ContributionService::save_contribution($contribution);
					return true;
				}
			}
		}
		return false;
	}
	
	function contribution_delete($id)
	{
		$contributions = ContributionService::find_by_criteria('smallads', $id);	
		if (count($contributions) > 0)
		{
			foreach($contributions as $contribution) {
				if ( EVENT_STATUS_UNREAD == $contribution->get_status())
				{
					ContributionService::delete_contribution($contribution);						
				}
			}
		}
	}
	
	function contribution_is_in_progress($id)
	{
		$contributions = ContributionService::find_by_criteria('smallads', $id);
		$in_progress = false;
		if (count($contributions) > 0)
		{
			foreach($contributions as $contribution) {
				if ( EVENT_STATUS_BEING_PROCESSED == $contribution->get_status())
				{
					$in_progress = true;
					break;
				}
			}
		}
		return $in_progress;
	}
	
	function save_cgu($str)
	{
		global $LANG;
		$filename = dirname(__FILE__).'/lang/'.get_ulang().'/cgu.html';
		$ret = @file_put_contents($filename, $str);
		if ($ret == 0) 
		{
			$controller = new UserErrorController(LangLoader::get_message('error', 'status-messages-common'), $LANG['sa_e_cgu_file_invalid']);
			DispatchManager::redirect($controller);
		}
	}
	
	function get_cgu()
	{
		$filename = dirname(__FILE__).'/lang/'.get_ulang().'/cgu.html';
		$str = @file_get_contents($filename);
		if ($str === FALSE) 
		{
			return 'Renseigner ici vos Conditions Générales / Fill in there your general usage terms';
		}		
		return $str;
	}
}
