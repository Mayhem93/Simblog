<?php

class Test_Plugin extends SBPlugin implements ITrigger {
	
	public function __construct() {
		parent::__construct("Test_Plugin");
		SBEventObserver::addEventListener(SBEvent::ON_POST_ADD, $this);
        SBFactory::getCurrentPage()->addResource(array('plugins\Test_Plugin\test_style.css'));
	}
	
	public function render() {
		
	}
	
	public function admin() {
		
	}
	
	public function trigger(SBEvent $evt) {
		if($evt->getType() == SBEvent::ON_POST_ADD)
			SBFactory::getCurrentPage()->addNotifyMessage("Test", SBPage::MESSAGE_NOTICE);
	}
	
}