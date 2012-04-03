<?php

class Test_Plugin extends SBPlugin implements ITrigger {
	
	public function __construct() {
		parent::__construct("Test_Plugin");
		$this->jsRequired = PLUGIN_JS_ADMIN_PAGE | PLUGIN_JS_BLOG_PAGES | PLUGIN_JS_PLUGIN_PAGE;
		SBEventObserver::addEventListener(SBEvent::ON_POST_ADD, $this);
	}
	
	public function render() {
		
	}
	
	public function admin() {
		
	}
	
	public function trigger(SBEvent $evt) {
		if($evt->getType() == SBEvent::ON_POST_ADD)
			notifyMessage("Hello", MSG_NOTICE);
	}
	
}