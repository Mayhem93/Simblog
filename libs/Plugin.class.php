<?php

class Blog_Plugin {
	
	protected $type	= "";
	protected $name = "";
	protected $description = "";
	protected $date = "";
	protected $author = "";
	
	protected function __construct(String $name) {
		$conf = new Config_Lite(PLUGIN_DIR.'/'.$name.'/plugin.conf');
		$this->name = $conf->get(null,'name');
		$this->description = $conf->get(null,'description');
		$this->date = $conf->get(null, 'date');
		$this->author = $conf->get(null, 'author');
		
		
	}
	
	protected function getType() {
		return $this->type;
	}
	
	protected function getDescription() {
		$conf = new Config_Lite();
	}
	
}