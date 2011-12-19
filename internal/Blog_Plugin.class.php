<?php

DEFINE("PLUGIN_JS_BLOG_PAGES");
DEFINE("PLUGIN_JS_ADMIN_PAGE");
DEFINE("PLUGIN_JS_PLUGIN_PAGE");

abstract class Blog_Plugin {
	
	private $type			= "";
	private $name 			= "";
	private $description 	= "";
	private $date 			= "";
	private $author 		= "";
	private $disabled;
	
	private $js_file		= "";
	private $css_file		= "";
	
	//0 - none; 1 - blog pages; 2 - admin page; 4 - plugin page;
	protected $jsRequired	= 0;
	protected $events;
	
	public function __construct($name) {
		$config = new Config_Lite(PLUGIN_DIR.'/'.$name.'/plugin.conf');
		$this->events = array();
		$this->name = $config->get(null,'name');
		$this->type = $config->get(null,'type');
		$this->description = $config->get(null,'description');
		$this->date = $config->get(null, 'date');
		$this->author = $config->get(null, 'author');
		$this->disabled = $config->getBool(null,'disabled',false);
		
		$dir = new DirectoryIterator(PLUGIN_DIR.'/'.$name);
		foreach($dir as $d)
			if($d->isFile()) {
				if(substr((string)$d, -3) == '.js')
					$this->js_file = (string)$d;
				else if(substr((string)$d, -4) == '.css')
					$this->css_file = (string)$d;
				if($this->css_file && $this->js_file)	//break when there has been found 1 JS and 1 CSS file
					break;
			}
	}
	
	public function getType() {
		return $this->type;
	}
	
	public function getDescription() {
		return $this->description;
	}
	
	public function getName() {
		return $this->name;
	}
	
	public function getDate() {
		return $this->date;
	}
	
	public function getAuthor() {
		return $this->author;
	}
	
	public function isDisabled() {
		return $this->disabled;
	}
	
	public function getCSSfile() {
		return $this->css_file;
	}
	
	public function getJSfile() {
		return $this->js_file;
	}
	
	public function getJSreq() {
		return $this->jsRequired;
	}
	
	final public function attachEvent(BlogEvent $evt) {
		$evt_name = $evt->getName();
		if(!isset($this->events[$evt_name]))
			$this->events[$evt_name] = $evt;
	}
	
	final public function dettachEvent(BlogEvent $evt) {
		$evt_name = $evt->getName();
		if(isset($this->events[$evt_name]))
			unset($this->events[$evt_name]);
	}
	
	/**
	 * 
	 * The rendering of the plugin on the plugin page.
	 */
	abstract public function render();
	
	/**
	 * 
	 * The admin page (configuration).
	 */
	abstract public function admin();
	
	public function __toString() {
		return $this->name;
	}
}