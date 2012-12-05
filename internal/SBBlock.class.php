<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Kingaap
 * Date: 11/16/12
 * Time: 12:37 AM
 * To change this template use File | Settings | File Templates.
 */
class SBBlock {

	const TYPE_BLOCK_PLUGIN = 0;
	const TYPE_BLOCK_ARTICLE = 1;
	const TYPE_BLOCK_PAGETAB = 2;
	const TYPE_BLOCK_ARCHIVE = 3;
	const TYPE_BLOCK_CATEGORIES = 4;
	const TYPE_BLOCK_RECENT_COMMENTS = 5;

	private $_contentType	=	null;
	private $_contentId = '';
	private $_contentVars;

	public function __construct($type, $contentId=null, $contentVars=null) {
		$this->_contentType = $type;
		$this->_contentId = $contentId;
		$this->_contentVars = $contentVars;
	}

	public function __toString() {
		switch ($this->_contentType) {
			case self::TYPE_BLOCK_PLUGIN: {
				$plugin = SBFactory::PluginManager()->offsetGet($this->_contentId);

				if ($plugin instanceof IRenderable) {
					return $plugin->getRenderContent($this->_contentVars);
				}

				break;
			}

			case self::TYPE_BLOCK_ARTICLE: {
				//TODO: output the template for a block article
				break;
			}

			case self::TYPE_BLOCK_ARCHIVE: {
				//TODO: output the archives block template
				break;
			}

			case self::TYPE_BLOCK_CATEGORIES: {
				//TODO: output the categories block template
				break;
			}

			case self::TYPE_BLOCK_RECENT_COMMENTS: {
				//TODO: output the recent comments block template
				break;
			}
		}

		return $this->_contentId;
	}
}
