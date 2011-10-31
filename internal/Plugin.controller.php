<?php

class Plugin_Controller implements Iterator, ArrayAccess, Countable {
	
	private static $instance;
	private $plugin_list = array();
	private $position;
	
	private function __construct() {
		$this->position = 0;
		$this->populatePluginList();
	}
	
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
		$dir = new DirectoryIterator('../plugins');
		foreach ($dir as $d)
			if(!$d->isDot() && $d->isDir()) 
				if($this->isValid((string)$d))
					$this->plugin_list[(string)$d] = new Blog_Plugin((string)$d);
	}
	
	/**
	 * 
	 * Checks if a plugin is valid. Used internally.
	 * @param String $name - plugin name.
	 * @return Plugin_Controller boolean - True if it's valid, false otherwise.
	 */
	
	private function isValid($name) {
		// TODO: More validation checks.
		return file_exists('../plugins/'.$name.'/plugin.conf');
	}
	
	public function current() {
		return $this->plugin_list[$this->position];
	}
	
	public function rewind() {
		$this->position = 0;
	}
	
	public function next() {
		++$this->position;
	}
	
	public function key() {
		return $this->position;
	}
	
	public function valid() {
		return isset($this->plugin_list[$this->position]);
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