<?php

DEFINE('IN_SITE', true);

require realpath('../init.php');

if(!isset($_GET['action']))
	$_GET['action'] = 'show';

session_start();
$js_files = array();

switch($_GET['action']) {
	case 'show': 
		
		foreach($simblog['plugin_manager'] as $name => $plugin)
			if(containsBit($plugin->getJSreq(), PLUGIN_JS_BLOG_PAGES))
				$js_files[] = "plugins/{$name}/".$plugin->getJSfile();
		
		$simblog['smarty']->assign("js_files", $js_files);
		try {
			$simblog['smarty']->display('index.tpl');
		}
		catch(SmartyException $e) {
			echo $e->getMessage();
		}
		break;
	
	case 'addpost': 
		//the user interface to add posts
		if(!count($_POST)) {
			//TODO add posts user interface
		}
		//where the posts are added
		else {
			blog_addPost($_POST['title'], $_POST['content']);
		}
		break;
}