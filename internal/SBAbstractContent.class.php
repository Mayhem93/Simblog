<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Kingaap
 * Date: 11/10/12
 * Time: 1:22 PM
 * To change this template use File | Settings | File Templates.
 */
abstract class SBAbstractContent {

	protected $_dirty	= false;
	protected $_fields = array(
		'id' => '',
		'content' => ''
	);

	public abstract function __construct($id);
	public abstract function commit();

	public function __set($var, $value) {
		if ($var != 'dirty') {
			$this->_dirty = true;
			$this->_fields[$var] = $value;

			return true;
		}

	}

	public function __get($var) {
		if (!isset($this->_fields[$var]))
			throw new Exception('Invalid property \''.$var.'\' for class '.__CLASS__.'.');

		return  $this->_fields[$var];
	}

	public function __destruct() {
		$this->commit();
	}

}
