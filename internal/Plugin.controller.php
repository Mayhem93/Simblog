<?php

class Plugin_Controller implements Iterator, ArrayAccess, Countable {
	
	private static $instance = null;
	private $plugin_list = array();
	private $plugin_keys = array();
	private $position;
	
	private function __construct() {
		$this->position = 0;
		$this->populatePluginList();
	}
	
	public function __clone() {}
	
	/**
	 * 
	 * Uses singleton design pattern.
	 * @return Plugin_Controller
	 */

	public static function init() {
		if(!(self::$instance instanceof self))
			self::$instance = new self();
		return self::$instance;
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
	private function populatePluginList() {
		$dir = new DirectoryIterator(PLUGIN_DIR);
		foreach ($dir as $d)
			if(!$d->isDot() && $d->isDir()) 
				if($this->isValid((string)$d)) {
					$class = ucfirst((string)$d);
					$this->plugin_list[(string)$d] = new $class;
					$this->plugin_keys[] = (string)$d;
				}
	}
	
	/**
	 * 
	 * Checks if a plugin is valid. Used internally.
	 * @param String $name - plugin name.
	 * @return boolean - True if it's valid, false otherwise.
	 */
	
	private function isValid($name) {
		$file_name = PLUGIN_DIR.'/'.$name.'/plugin.conf';
		$class_file = realpath(PLUGIN_DIR.'/'.$name.'/'.$name.'.class.php');
		$valid = true;
		
		if(!file_exists($file_name)) {
			notifyMessage('Configuration file'.realpath($file_name)."does not exist. The plugin associated with this file has not been loaded.", MSG_ERROR);
			$valid = false;	
		}
		if(!is_writable($file_name)) {
			notifyMessage('Configuration file'.realpath($file_name)."is not writeable. The plugin associated with this file has not been loaded.", MSG_ERROR);
			$valid = false;	
		}
		if(!is_readable($file_name)) {
			notifyMessage('Configuration file'.realpath($file_name)."is not readable. The plugin associated with this file has not been loaded.", MSG_ERROR);
			$valid = false;	
		}
		if(!is_readable($class_file)) {
			notifyMessage("Class file for plugin ".$name." is not readable or does not exist. The plugin associated with this file has not been loaded.", MSG_ERROR);
			$valid = false;	
		}
		else {
			include $class_file;
			if(!class_exists(ucfirst($name))) {
				notifyMessage("Class name in ".$name." plugin does not match ".ucfirst($name).". The plugin was not loaded.", MSG_ERROR);
				$valid = false;	
			}
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