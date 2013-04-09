<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Kingaap
 * Date: 11/14/12
 * Time: 2:21 PM
 * To change this template use File | Settings | File Templates.
 */
class SBLayout implements ArrayAccess {

	private $_skinSettings;
	/**
	 * @var SBZone[] Zones available in the current skin.
	 */
	private $_zones = array();
	private $_skinId = '';

	public function __construct($name) {
		if (file_exists(SKINS_DIR.'/'.$name.'/layout.json')) {
			$this->_skinId = $name;
			$this->loadSettings();
		} else {
			throw new Exception('Skin '.$name.' does not exist or configuration file is missing.');
		}

		$this->addSkinResources();
		$this->buildZones();
	}

	public function getSkinId() {
		return $this->_skinId;
	}

	public function getSkinFullPath() {
		return SKINS_DIR.'/'.$this->getSkinId();
	}

	public function getSkinWWWuri() {
		return 'skins/'.$this->getSkinId();
	}

	public function getZone($id) {
		return isset($this->_zones[$id]) ? $this->_zones[$id] : null;
	}

	public function getIndexTpl() {
		return $this->_skinSettings['index_tpl'];
	}

	public function getIndexTplPath() {
		return $this->getSkinFullPath().$this->getIndexTpl();
	}

	public function countZones() {
		return $this->_skinSettings['zones_count'];
	}

	private function loadSettings() {
		$decodedFile = json_decode(file_get_contents(SKINS_DIR.'/'.$this->getSkinId().'/layout.json'), true);

		if ($decodedFile === null) {
			throw new Exception('JSON error when reading skin configuration file: '.json_last_error());
		}

		if (!isset($decodedFile['id'])) {
			throw new Exception('Skin configuration file misconfiguration: missing "id" field.');
		}
		$this->_skinSettings['id'] = $decodedFile['id'];
		if (!isset($decodedFile['author'])) {
			throw new Exception('Skin configuration file misconfigured: missing "author" field.');
		}
		$this->_skinSettings['author'] = $decodedFile['author'];
		if (!isset($decodedFile['displayed_name'])) {
			throw new Exception('Skin configuration file misconfigured: missing "displayed_name" field.');
		}
		$this->_skinSettings['displayed_name'] = $decodedFile['displayed_name'];
		if (!isset($decodedFile['version'])) {
			throw new Exception('Skin configuration file misconfigured: missing "version" field.');
		}
		$this->_skinSettings['version'] = (int)$decodedFile['version'];
		if (!isset($decodedFile['zones_count'])) {
			throw new Exception('Skin configuration file misconfigured: missing "zones_count" field.');
		}
		$this->_skinSettings['zones_count'] = (int)$decodedFile['zones_count'];
		if (!isset($decodedFile['zones']) || !is_array(($decodedFile['zones']))) {
			throw new Exception('Skin configuration file misconfigured: missing "zones" field or zones is not array.');
		}
		$this->_skinSettings['zones'] = $decodedFile['zones'];
		if (!isset($decodedFile['index_tpl'])) {
			throw new Exception('Skin configuration file misconfigured: missing "index_tpl" field.');
		}

		$this->_skinSettings['index_tpl'] = $decodedFile['index_tpl'];

		if (!isset($decodedFile['resources'])) {
			throw new Exception('Skin configuration file misconfigured: missing "resources" field (it can be an empty array).');
		}
		$this->_skinSettings['resources'] = $decodedFile['resources'];

		return true;
	}

	private function buildZones() {
		foreach ($this->_skinSettings['zones'] as $zone) {
			$this->_zones[$zone['role']][$zone['html_id']] = new SBZone($zone['html_id'], (int)$zone['role'], $zone['tpl_path']);
			$result = SBFactory::Database()->selectRows(DB_TABLE_PREFIX.'skin_content', "*", array('zone_id' => $zone['html_id']));

			if (empty($result)) {
				continue;
			}

			$layoutContent = json_decode($result[0]['content'], true);

			foreach ($layoutContent['content'] as $block) {
				$this->_zones[$zone['role']][$zone['html_id']]->addBlock(new SBBlock((int)$block['type'], $block['id'], $block['content_vars']));
			}
		}

		return true;
	}

	private function addSkinResources() {
		$resources = $this->_skinSettings['resources']['css'] + $this->_skinSettings['resources']['js'];
		$resources = array_map(function($string) { return 'skins/'.$this->getSkinId().'/'.$string;}, $resources);
		SBFactory::getCurrentPage()->addResource($resources);
	}

	public function getSkinName() {
		return $this->_skinSettings['displayed_name'];
	}

	public function offsetExists($offset) {
		return isset($this->_zones[$offset]);
	}

	public function offsetGet($offset) {
		return $this->_zones[$offset];
	}

	public function offsetSet($offset, $value) {
		return ;
	}

	public function offsetUnset($offset) {
		return ;
	}
}