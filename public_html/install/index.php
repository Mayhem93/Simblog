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

	$port = $_POST['port'];
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
		/*It is very important that the sql file uses ';' as a delimiter
		between statements. Other workaround would be to use shell exec.
		*/
		$prefix = isset($_POST['tbl_prefix']) ? $_POST['tbl_prefix'] : "";

		$sql = explode(';',file_get_contents("database.sql"));
		for($i=0; $i < count($sql); $i++) {
			$sql[$i] = str_replace("{prefix}", $prefix, $sql[$i]);
			mysql_query($sql[$i], $db);
		}
	}
	
	$inputErrors = array();
	
	if($_POST['author'] == "")
		$inputErrors['author'] = true;
	if($_POST['title'] == "")
		$inputErrors['title'] = true;
	if($_POST['description'] == "") 
		$inputErrors['description'] = true;
	if($_POST['email'] == "")
		$inputErrors['email'] = true;
	if($_POST['admin_username'] == "")
		$inputErrors['admin_username'] = true;
	if($_POST['admin_password'] == "")
		$inputErrors['admin_password'] = true;
	
	if(is_writable(realpath(getcwd()."/../.."))) {
		if(!count($inputErrors)) {
			$config_file = new Config_Lite(getcwd()."/../../global.conf");

			$config_file->set(null, "author_name", $_POST['author']);
			$config_file->set(null, "blog_title", $_POST['title']);
			$config_file->set(null, "blog_description", $_POST['description']);
			$config_file->set(null, "email", $_POST['email']);
			$config_file->set(null, "install_plugin_default", isset($_POST['disabled_plugins']) ? "disabled" : "enabled");
			$config_file->set(null, "disable_plugins", false);
			$config_file->set(null, "admin_username", $_POST['admin_username']);
			$config_file->set(null, "admin_password", $_POST['admin_password']);
			$config_file->set(null, "no_posts_per_page", 10);
			
			$config_file->set(null, "host", isset($_POST['hostname']) ? $_POST['hostname'] : "");
			$config_file->set(null, "port", $port);
			$config_file->set(null, "user", isset($_POST['username']) ? $_POST['username'] : "");
			$config_file->set(null, "password", isset($_POST['password']) ? $_POST['password'] : "");
			$config_file->set(null, "database", isset($_POST['database']) ? $_POST['database'] : "");
			$config_file->set(null, "tbl_prefix", isset($_POST['tbl_prefix']) ? $_POST['tbl_prefix'] : "");
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