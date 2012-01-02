<?php

class Test_Plugin extends SBPlugin {
	
	public function __construct() {
		parent::__construct("Test_Plugin");
		$this->jsRequired = PLUGIN_JS_ADMIN_PAGE | PLUGIN_JS_BLOG_PAGES | PLUGIN_JS_PLUGIN_PAGE;
	}
	
	public function render() {
		
	}
	
	public function admin() {
		
	}
}