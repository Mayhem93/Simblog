<?php

DEFINE('IN_SITE',true);
include 'smarty/Smarty.class.php';
include '../../libs/Config_Lite.class.php';

$smarty = new Smarty();
$smarty->setTemplateDir('templates');
$smarty->setCacheDir('templates_c');
$smarty->setCompileDir('templates_c');

if(!count($_POST)) {
	$smarty->assign('stage','stage1');
}
else {
	if(!file_exists("../posts"))
		mkdir("../posts");
	if(!file_exists("../comments"))
		mkdir("../comments");
	$stage = 'success';	//we presume that the data provided is correct
	
	if(isset($_POST['db_support'])) {
		$port = ($_POST['port'] == "") ? '3306' : $_POST['port'];
		$db = @mysql_connect($_POST['hostname'].':'.$port,$_POST['username'],$_POST['password']);
		
		if(!$db) {
			$stage = 'stage1';
			$smarty->assign("mysql_error",true);
			$smarty->assign("mysql_error_msg", "Could not connect to database server.");
		}
		else if(!mysql_select_db($_POST['database'])) {
			$stage = 'stage1';
			$smarty->assign("mysql_error",true);
			$smarty->assign("mysql_error_msg", mysql_error());
		}
		else {
			//TODO: execute SQL file
			/*It is very important that the sql file uses ';' as a delimiter
			between statements. Other workaround would be to use shell exec.
			*/
		$sql = explode(';',file_get_contents("database.sql"));
		foreach ($sql as $query)
			mysql_query($query,$db);
		}
	}
	
	$inputErrors = array();
	
	if($_POST['author'] == "")
		$inputErrors['author'] = true;
	if($_POST['title'] == "")
		$inputErrors['title'] = true;
	if($_POST['email'] == "")
		$inputErrors['email'] = true;
	if($_POST['admin_username'] == "")
		$inputErrors['admin_username'] = true;
	if($_POST['admin_password'] == "")
		$inputErrors['admin_password'] = true;
	
	if(is_writable(realpath(getcwd()."/../.."))) {
		if(!count($inputErrors)) {
			$config_file = new Config_Lite(getcwd()."/../../global.conf");
			#General
			$config_file->set("General", "author_name", $_POST['author']);
			$config_file->set("General", "blog_title", $_POST['title']);
			$config_file->set("General", "email", $_POST['email']);
			$config_file->set("General", "install_plugin_default", isset($_POST['disabled_plugins']) ? "disabled" : "enabled");
			$config_file->set("General", "disable_plugins", false);
			$config_file->set("General", "database_support", isset($_POST['db_support']));
			$config_file->set("General", "admin_username", $_POST['admin_usernmae']);
			$config_file->set("General", "admin_password", $_POST['admin_password']);
			
			#Database
			$config_file->set("Database", "host", isset($_POST['hostname']) ? $_POST['hostname'] : "");
			$config_file->set("Database", "port", isset($port) ? $port : "");
			$config_file->set("Database", "user", isset($_POST['username']) ? $_POST['username'] : "");
			$config_file->set("Database", "password", isset($_POST['password']) ? $_POST['password'] : "");
			$config_file->set("Database", "database", isset($_POST['database']) ? $_POST['database'] : "");
			$config_file->save();
		}
		else
			$stage = "stage1";
	}
	else {
		$stage = "stage1";
		$smarty->assign('not_writable',true);
	}
	$smarty->assign('inputErrors',$inputErrors);
	$smarty->assign('stage',$stage);
}
$smarty->display('index.tpl');