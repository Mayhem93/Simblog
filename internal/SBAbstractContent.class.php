<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Kingaap
 * Date: 11/10/12
 * Time: 1:22 PM
 * To change this template use File | Settings | File Templates.
 */
abstract class SBAbstractContent {

	protected $_id		= '';
	protected $_content	= '';
	protected $_dirty	= '';

	public abstract function __construct($id);
	public abstract function commit();

	public function __set($var, $value) {
		//this requires PHP 5.3.x+
		if (property_exists('SBPage', '_'.$var) && $var != 'dirty' && $var != 'id') {
			$this->_dirty = true;
			$propName = '_'.$var;
			$this->$propName = $value;

			return true;
		}

		throw new Exception(__CLASS__.' class does not have property _'.$var);
	}

	public function __get($var) {
		//this requires PHP 5.3.x+
		if (property_exists('SBPage', '_'.$var)) {
			$propName = '_'.$var;

			return $this->$propName;
		}

		throw new Exception(__CLASS__.' class does not have property _'.$var);
	}

	public function __destruct() {
		$this->commit();
	}

}
