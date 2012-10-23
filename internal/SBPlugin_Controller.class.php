<?php
/**
* The plugin controller class which controls plugins.
*
* @author		Răzvan Botea<utherr.ghujax@gmail.com>
* @license 		http://www.gnu.org/licenses/gpl.txt
* @copyright	2011-2012 Răzvan Botea
*
* 	PHP 5
*
*	This file is part of Simblog.
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

class SBPlugin_Controller implements Iterator, ArrayAccess, Countable 
{
	private $plugin_list = array();
	private $plugin_keys = array();
	private $position;
	
	public function __construct() {
		$this->position = 0;
		$this->_populatePluginList();
	}
	
	/**
	 * 
	 * Enables a plugin.
	 * @param String $name - The plugin to enable.
	 * @return boolean - True if successful, false otherwise.
	 */
	public static function enablePlugin($name) {
		$c = new Config_Lite(PLUGIN_DIR.'/'.$name.'/plugin.conf');
		$c->set(null, disabled, false);
		$c->save();
	}
	/**
	 * 
	 * Disables a plugin.
	 * @param String $name - The plugin to disable.
	 * @return boolean - True if successful, false otherwise.
	 */
	public static function disablePlugin($name) {
		$c = new Config_Lite(PLUGIN_DIR.'/'.$name.'/plugin.conf');
		$c->set(null, disabled, true);
		$c->save();
	}
	/**
	 * 
	 * Populates the plugin list. Called in the constructor.
	 * @return void
	 */
	private function _populatePluginList() {
		$dir = new DirectoryIterator(PLUGIN_DIR);
		foreach ($dir as $d) {

			$dirName = (string)$d;

			if(!$d->isDot() && $d->isDir()) {
				if($this->_isValidPlugin($dirName)) {
					$class = ucfirst($dirName);
					$this->plugin_list[$dirName] = new $class;
					echo $class;
					$this->plugin_keys[] = $dirName;
				}
			}
		}
	}
	
	/**
	 * 
	 * Checks if a plugin is valid. Used internally.
	 * @param String $name - plugin name.
	 * @return boolean - True if it's valid, false otherwise.
	 */
	
	private function _isValidPlugin($name) {
		$file_name = PLUGIN_DIR.'/'.$name.'/plugin.conf';
		$class_file = realpath(PLUGIN_DIR.'/'.$name.'/'.$name.'.class.php');
		$valid = true;
		
		if(!file_exists($file_name)) {
			SBFactory::getCurrentPage()->addNotifyMessage('Configuration file'.realpath($file_name)." does not exist. The plugin associated with this file has not been loaded.", SBPage::MESSAGE_ERROR);
			$valid = false;	
		}
		if(!is_writable($file_name)) {
			SBFactory::getCurrentPage()->addNotifyMessage('Configuration file'.realpath($file_name)." is not writeable. The plugin associated with this file has not been loaded.", SBPage::MESSAGE_ERROR);
			$valid = false;	
		}
		if(!is_readable($file_name)) {
			SBFactory::getCurrentPage()->addNotifyMessage('Configuration file'.realpath($file_name)." is not readable. The plugin associated with this file has not been loaded.", SBPage::MESSAGE_ERROR);
			$valid = false;	
		}
		if(!is_readable($class_file)) {
			SBFactory::getCurrentPage()->addNotifyMessage("Class file for plugin ".$name." is not readable or does not exist. The plugin associated with this file has not been loaded.", SBPage::MESSAGE_ERROR);
			$valid = false;	
		}
		return $valid;
	}
	
	public function current() {
		return $this->plugin_list[$this->plugin_keys[$this->position]];
	}
	
	public function rewind() {
		$this->position = 0;
	}
	
	public function next() {
		++$this->position;
	}
	
	public function key() {
		return $this->plugin_keys[$this->position];
	}
	
	public function valid() {
		return isset($this->plugin_keys[$this->position]);
	}
	
	public function offsetExists($offset) {
		return isset($this->plugin_list[$offset]);
	}
	
	public function offsetGet($offset) {
		return $this->plugin_list[$offset];
	}
	
	public function offsetSet($offset, $value) {
		return;
	}
	
	public function offsetUnset($offset) {
		return; 	
	}
	
	public function count() {
		return count($this->plugin_list);
	}
	
}