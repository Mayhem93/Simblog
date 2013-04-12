<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Kingaap
 * Date: 11/9/12
 * Time: 4:13 PM
 * To change this template use File | Settings | File Templates.
 */
class SBPageTab extends SBAbstractContent {

	public function __construct($id) {
		$result = SBFactory::Database()->selectRows(DB_TABLE_PREFIX.'page', '*', array('id' => $id));
		if (empty($result)) {
			throw new Exception('PageTab with id '.$id.' does not exist.');
		}

		$this->_fields['id']			= $result[0]['id'];
		$this->_fields['description']	= $result[0]['description'];
		$this->_fields['name']	 		= $result[0]['name'];
		$this->_fields['parent']		= $result[0]['parent'];
		$this->_fields['content']		= $result[0]['content'];
	}

	public function commit() {
		if ($this->_dirty) {
			$updateSet = array(
				'name' => $this->_fields['name'],
				'description' => $this->_fields['description'],
				'parent' => $this->_fields['parent'],
				'content' => $this->_fields['content']);

			$result = SBFactory::Database()->updateRows(DB_TABLE_PREFIX.'page', array('id' => $this->_fields['id']), $updateSet);

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
}
