<?php

DEFINE('IN_SITE',true);
include 'smarty/Smarty.class.php';
include '../../libs/class.MySQL.php';
include '../../libs/Lite.php';

if(!count($_POST)) {
	$smarty = new Smarty();
	$smarty->setTemplateDir('templates');
	$smarty->setCacheDir('templates_c');
	$smarty->setCompileDir('templates_c');
	$smarty->display('index.tpl');
}

else {
	//TODO: set configurations and display page with errors if errors exist
}