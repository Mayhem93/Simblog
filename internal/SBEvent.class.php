<?php

final class SBEvent {
	
	private $_data;
	private $_type;
	
	const ON_USER_ACCESS 		= 0;
	const ON_USER_COMMENT		= 1;
	const ON_ADMIN_LOGIN		= 2;
	const ON_ADMIN_LOGOUT		= 3;
	const ON_PLUGIN_LOAD		= 4;
	const ON_POST_ADD			= 5;
	const ON_POST_DELETE		= 6;
	const ON_COMMENT_DELETE		= 7;
	const ON_PAGE_ADD			= 8;
	
	public function __construct($data , $type) {
		$this->_data = $data;
		$this->_type = $type;
	}
	
	public function getType() {
		return $this->_type;
	}
	
	public function __get($value) {
		if(isset($this->_data[$value]))
			return $this->_data[$value];
		return null;
	}
	
	public function __set($var, $value) {}
}