<?php

class BlogEventHandler 
{
	//private $event;
	private $callback;
	
	public function __construct($callback, &$object) {
		$this->callback = $callback;
	}
	
	public function trigger($sender, $args) {
		if($this->callback)
			eval($this->callback);
	}
	
	private function prepareCallback($callback) {
		if ($pos = strpos($callback, '('))
			$callback = substr($callback, 0, $pos);
		
		$callback .= '($sender, $args);';
		return $callback;
	}
	
}