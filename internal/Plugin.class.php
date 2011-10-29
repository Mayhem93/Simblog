<?php

class Blog_Plugin {
	
	private $type			= "";
	private $name 			= "";
	private $description 	= "";
	private $date 			= "";
	private $author 		= "";
	
	protected function __construct(String $name) {
		$conf = new Config_Lite(PLUGIN_DIR.'/'.$name.'/plugin.conf');
		$this->name = $conf->get(null,'name');
		$this->type = $conf->get(null,'type');
		$this->description = $conf->get(null,'description');
		$this->date = $conf->get(null, 'date');
		$this->author = $conf->get(null, 'author');
		
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
	
	/**
	 * 
	 * To be overwritten.
	 */
	public function render() {}
	
}