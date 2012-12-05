<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Kingaap
 * Date: 11/16/12
 * Time: 12:24 AM
 * To change this template use File | Settings | File Templates.
 */
class SBZone implements IteratorAggregate {

	const ROLE_HEADER 	= 0;
	const ROLE_FOOTER 	= 1;
	const ROLE_MAIN		= 2;
	const ROLE_PANEL	= 3;

	private $_id = '';
	private $_role = null;

	/**
	 * @var SBBlock[] Blocks of content found in this zone.
	 */
	private $_blocks = array();

	public function __construct($id, $role, $tpl_path) {
		$this->_id = $id;
		$this->_role = $role;
	}

	public function addBlock(SBBlock $block) {
		$name = (string)$block;

		if (empty($this->_blocks)) {
			$this->_blocks[$name] = $block;
		}

	}

	public function removeBlock(SBBlock $block) {
		$name = (string)$block;

		if (isset($this->_blocks[$name])) {
			unset($this->_blocks[$name]);

			return true;
		}

		return false;
 	}

	public function getID() {
		return $this->_id;
	}

	public function getIterator() {
		return new ArrayIterator($this->_blocks);
	}

}
