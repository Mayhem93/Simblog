<?php

class Blog_Plugin {
	
	private $type			= "";
	private $name 			= "";
	private $description 	= "";
	private $date 			= "";
	private $author 		= "";
	private $disabled;
	
	public function __construct($name) {
		
		$config = new Config_Lite(PLUGIN_DIR.'/'.$name.'/plugin.conf');
		$this->name = $config->get(null,'name');
		$this->type = $config->get(null,'type');
		$this->description = $config->get(null,'description');
		$this->date = $config->get(null, 'date');
		$this->author = $config->get(null, 'author');
		$this->disabled = $config->getBool(null,'disabled',false); 
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
	
	/**
	 * 
	 * To be overwritten.
	 */
	public function render() {}
	
	public function __toString() {
		return $this->getName();
	}
	
}