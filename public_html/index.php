<?php

DEFINE('IN_SITE', true);

require realpath('../init.php');

if(!isset($_GET['action']))
	$_GET['action'] = 'show';

session_start();

switch($_GET['action']) {
	case 'show': 
		
		$simblog['smarty']->assign("js_files", array_merge($js_file_paths['all'],$js_file_paths['blog_pages']));
		$simblog['smarty']->display('index.tpl');
		break;
	
	case 'addpost': 
		//the user interface to add posts
		if(!count($_POST)) {
			//TODO add posts user interface
		}
		//where the posts are added
		else {
			cleanInput();
			Posts::addPost($_POST['title'], $_POST['content']);
		}
		break;
}