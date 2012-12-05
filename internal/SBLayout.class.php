<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Kingaap
 * Date: 11/14/12
 * Time: 2:21 PM
 * To change this template use File | Settings | File Templates.
 */
class SBLayout {

	private $_skinSettings;
	/**
	 * @var SBZone[] Zones available in the current skin.
	 */
	private $_zones;

	public function __construct($name) {
		if (file_exists(SKINS_DIR.'/'.$name.'/layout.json')) {
			$this->loadSettings();
		} else {
			throw new Exception('Skin '.$name.' does not exist or configuration file is missing.');
		}

		$this->buildZones();
	}

	public function getZone($id) {
		return isset($this->_zones[$id]) ? $this->_zones[$id] : null;
	}

	public function countZones() {
		return $this->_skinSettings['zones_count'];
	}

	private function loadSettings() {
		$decodedFile = json_decode(SKINS_DIR.'/'.$this->_currentSkin.'/layout.json', true);

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

		return true;
	}

	private function buildZones() {
		foreach ($this->_skinSettings['zones'] as $zone) {
			$this->_zones[$zone['html_id']] = new SBZone($zone['html_id'], (int)$zone['role'], $zone['tpl_path']);
			$result = SBFactory::Database()->selectRows(DB_TABLE_PREFIX.'skin_content', "*", array('zone_id' => $zone['html_id']));

			$layoutContent = json_decode($result[0]['content'], true);

			foreach ($layoutContent['content'] as $block) {
				$this->_zones[$zone['html_id']]->addBlock(new SBBlock((int)$block['type'], $block['id'], $block['content_vars']));
			}
		}

		return true;
	}

	public function getSkinName() {
		return $this->_skinSettings['displayed_name'];
	}
}
