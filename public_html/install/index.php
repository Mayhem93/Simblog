<?php

DEFINE('IN_SITE',true);
include 'smarty/Smarty.class.php';
include '../../libs/class.MySQL.php';
include '../../libs/Lite.php';

$smarty = new Smarty();
$smarty->setTemplateDir('templates');
$smarty->setCacheDir('templates_c');
$smarty->setCompileDir('templates_c');

if(!count($_POST)) {
	$smarty->assign('stage','stage1');
}
else {
	//TODO: set configurations and display page with errors if errors exist
	#General
	$config_file = new Config_Lite(getcwd()."/../../global.conf");
	$config_file->set("General", "author_name", $_POST['author']);
	$config_file->set("General", "blog_title", $_POST['title']);
	$config_file->set("General", "email", $_POST['email']);
	$config_file->set("General", "install_plugin_default", isset($_POST['disabled_plugins']) ? "disabled" : "enabled");
	$config_file->set("General", "disable_plugins", false);
	
	#Database
	$config_file->set("Database", "host", $_POST['hostname']);
	$config_file->set("Database", "port", ($_POST['port'] == "") ? 3306 : intval($_POST['port']));
	$config_file->set("Database", "user", $_POST['username']);
	$config_file->set("Database", "password", $_POST['password']);
	$config_file->set("Database", "database", $_POST['database']);
	$config_file->save();
	$smarty->assign('stage','success');
}

$smarty->display('index.tpl');