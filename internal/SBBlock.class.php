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

	private $_contentType	=	null;
	private $_contentId = '';
	private $_contentVars = array();

	public function __construct($type, $contentId=null, $contentVars=array()) {
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

			case self::TYPE_BLOCK_POPULAR_TAGS: {
				if (file_exists(SBFactory::getSkin()->getSkinFullPath().'/default_blocks/popular_tags.tpl')) {
					$popularTags = new Smarty();
					$popularTags->setTemplateDir(SBFactory::getSkin()->getSkinFullPath());
					$popularTags->setCacheDir(SBFactory::getSkin()->getSkinFullPath().'/cache');
					$popularTags->setCompileDir(SBFactory::getSkin()->getSkinFullPath().'/cache');

					foreach($this->_contentVars as $name => $value) {
						$popularTags->assign($name, $value);
					}

					return $popularTags->fetch('default_blocks/popular_tags.tpl');
				}

				break;
			}
		}

		return '';
	}
}
