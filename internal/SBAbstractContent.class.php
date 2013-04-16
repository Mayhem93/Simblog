<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Kingaap
 * Date: 11/10/12
 * Time: 1:22 PM
 * To change this template use File | Settings | File Templates.
 */
abstract class SBAbstractContent {

	/**
	 * If fields have been modified this variable will be true.
	 * It is used to know if data should be commited to the database.
	 * @var bool
	 */
	protected $_dirty	= false;
	/**
	 * The fields of the Abstract content that this class models
	 * @var array
	 */
	protected $_fields = array(
		'id' => '',
		'content' => ''
	);

	/**
	 * Implemented by child classes
	 * @param $id
	 */
	public abstract function __construct($id);

	/**
	 * This method should save the fields to the database.
	 * @return mixed
	 */
	public abstract function commit();

	/**
	 * Access to a field of the object. The field name cannot be dirty.
	 * @param $var the name of the field
	 * @param $value value of the field
	 * @return bool
	 */
	public function __set($var, $value) {
		if ($var != 'dirty') {
			$this->_dirty = true;
			$this->_fields[$var] = $value;

			return true;
		}

	}

	/**
	 * Gets name of the field.
	 * @param $var The name of the field
	 * @return string The value of the field
	 * @throws Exception
	 */
	public function __get($var) {
		if (!isset($this->_fields[$var]))
			throw new Exception('Invalid property \''.$var.'\' for class '.__CLASS__.'.');

		return  $this->_fields[$var];
	}

	/**
	 * When the object destructs the fields are commited.
	 */
	public function __destruct() {
		if(!$this->commit()) {
			throw new Exception('Error saving data to database.');
		}
	}

}
