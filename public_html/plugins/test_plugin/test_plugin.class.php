<?php

class Test_Plugin extends Blog_Plugin {
	
	public function __construct() {
		parent::__construct('test_plugin');
	}
	
	public function render() {
		echo "test_plugin render";
	}
}