<?php

DEFINE('IN_SITE', true);

require realpath('../init.php');

if(!isset($_GET['action']))
	$_GET['action'] = 'show';

switch($_GET['action']) {
	case 'show': 
		$plugin_css = array();
		$plugin_js = array();
		//TODO: make it work
		foreach($simblog['plugin_manager'] as $name => $plugin) {
			if(count($plugin->css_files) >= 1) {
				$simblog['smarty']->assign('plugin_css_files',$plugin->getCSSfiles());
				$plugin_css[] = $name;
			}
			if(count($plugin->js_files) >= 1) {
				$simblog['smarty']->assign('plugin_js_files',$plugin->getJSfiles());
				$plugin_js[] = $name;
			}
		}
		
		$simblog['smarty']->assign('plugin_css',$plugin_css);
		$simblog['smarty']->assign('plugin_js',$plugin_js);
		$simblog['smarty']->assign('author_name',$simblog['conf']['author_name']);
		$simblog['smarty']->assign('email',$simblog['conf']['email']);
		$simblog['smarty']->assign('blog_title',$simblog['conf']['blog_title']);
		$simblog['smarty']->display('index.tpl');
		break;
}