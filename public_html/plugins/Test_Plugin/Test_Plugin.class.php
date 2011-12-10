<?php

class Test_Plugin extends Blog_Plugin {
	
	public function __construct() {
		parent::__construct("Test_Plugin");
		$this->jsRequired[] = "all";
	}
	
	public function render() {
		
	}
	
	public function admin() {
		
	}
}