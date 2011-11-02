<?php

DEFINE('IN_SITE', true);

require '../init.php';

if(!isset($_GET['action']))
	$_GET['action'] = 'show';

switch($_GET['action']) {
	case 'show': 
		$simblog['smarty']->assign('author_name',$simblog['conf']['author_name']);
		$simblog['smarty']->assign('email',$simblog['conf']['email']);
		$simblog['smarty']->assign('blog_title',$simblog['conf']['blog_title']);
		$simblog['smarty']->display('index.tpl');
		break;
}