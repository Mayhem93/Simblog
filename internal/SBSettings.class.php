<?php
/**
* Settings class to handle settings file.
*
* @author		Răzvan Botea<utherr.ghujax@gmail.com>
* @license 	http://www.gnu.org/licenses/gpl.txt
* @copyright	2011-2012 Răzvan Botea
* @link		https://github.com/Mayhem93/Simblog
*
* 	 PHP 5
*
*	 This file is part of Simblog.
*
*   Simblog is free software: you can redistribute it and/or modify
*   it under the terms of the GNU General Public License as published by
*   the Free Software Foundation, either version 3 of the License, or
*   (at your option) any later version.
*
*   Simblog is distributed in the hope that it will be useful,
*   but WITHOUT ANY WARRANTY; without even the implied warranty of
*   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*   GNU General Public License for more details.
*
*   You should have received a copy of the GNU General Public License
*   along with Simblog.  If not, see <http://www.gnu.org/licenses/>.
*/
class SBSettings
{
	private $_config_file;
	private $_settings = array();
	
	public function __construct() {
		$this->_loadConfigFile();
	}
	
	public function __destruct() {
		$this->_config_file->save();
	}
	
	public function getAll() {
		return $this->_settings;
	}
	
	public function getSetting($setting) {
		if (isset($this->_settings[$setting]))
			return $this->_settings[$setting];
		
		throw new Exception("Setting '$setting' does not exist.");
	}
	
	public function setSetting($setting, $value) {
		if (isset($this->_settings[$setting]))
			$this->_config_file->set(null, $setting, $value);
	}
	
	private function _loadConfigFile() {
		$this->_config_file = new Config_Lite(BLOG_ROOT.'/global.conf');
		
		try {
			foreach($this->_config_file as $key => $value) 
				$this->_settings[$key] = $value;
		}
		catch (Exception $e) {
			throwError("Error: ".$e->getMessage().".\nFile \"global.conf\" is not be configured correctly.");
		}
	}
}
