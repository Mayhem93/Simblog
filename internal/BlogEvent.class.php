<?php

class BlogEvent {
	
	private $name;
	private $callback;
	private $params;
	
	public function __construct($name, array $params) {
		$this->name = $name;
		$this->params = $params;
	}
	
	public function getName() {
		return $this->name;
	}
}