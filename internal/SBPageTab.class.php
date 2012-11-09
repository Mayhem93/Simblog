<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Kingaap
 * Date: 11/9/12
 * Time: 4:13 PM
 * To change this template use File | Settings | File Templates.
 */
class SBPageTab {

	private $_id			= '';
	private $_name			= '';
	private $_description 	= '';
	private $_parent		= null;
	private $_content		= '';
	private $_dirty			= false;

	public function __construct($id) {
		$result = SBFactory::Database()->selectRows(DB_TABLE_PREFIX.'page', '*', array('id' => $id));
		if (empty($result)) {
			throw new Exception('PageTab with id '.$id.' does not exist.');
		}

		$this->_id			= $result[0]['id'];
		$this->_description = $result[0]['description'];
		$this->_name 		= $result[0]['name'];
		$this->_parent		= $result[0]['parent'];
		$this->_content		= $result[0]['content'];
	}

	public function commit() {
		if ($this->_dirty) {
			$updateSet = array('name' => $this->_name, 'description' => $this->_description, 'parent' => $this->_parent,
				'content' => $this->_content);

			$result = SBFactory::Database()->updateRows(DB_TABLE_PREFIX.'page', array('id' => $this->_id), $updateSet);

			if ($result) {
				$this->_dirty = false;
			}

			return $result;
		}

		return false;
	}

	public static function createPageTab($name, $content, $description = '', $parent = null) {
		$insertArray = array('name' => $name, 'content' => $content);

		if ($description) {
			$insertArray['description'] = $description;
		}
		if ($parent) {
			$insertArray['parent'] = $parent;
		}

		$result = SBFactory::Database()->insertRow(DB_TABLE_PREFIX.'page', $insertArray);
		if ($result === false)
			return false;

		return SBFactory::Database()->getLastInsertID();
	}

	public static function deletePageTab($id) {
		return SBFactory::Database()->deleteRows(DB_TABLE_PREFIX.'page', array('id' => $id));
	}

	public function __set($var, $value) {
		//this requires PHP 5.3.x+
		if (property_exists(SBPage, '_'.$var) && $var != 'dirty' && $var != 'id') {
			$this->_dirty = true;
			$propName = '_'.$var;
			$this->$propName = $value;

			return true;
		}

		throw new Exception('SBPageTab class does not have property _'.$var);
		return false;
	}

	public function __get($var) {
		//this requires PHP 5.3.x+
		if (property_exists(SBPage, '_'.$var)) {
			$propName = '_'.$var;

			return $this->$propName;
		}

		throw new Exception('SBPageTab class does not have property _'.$var);
	}

	public function __destruct() {
		$this->commit();
	}
}
