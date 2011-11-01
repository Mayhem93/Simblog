<?php
if(!defined('IN_SITE'))
	header('HTTP/1.1 404 Not Found');

global $simblog;

//TODO: some global configurations like database and other useful stuff.

$config_file = new Config_Lite(BLOG_ROOT.'/global.conf');

#General
$simblog['conf']['install_plugin_default']	=	$config_file->get("General","install_plugin_default");
$simblog['conf']['disable_plugins']			=	$config_file->getBool("General","disable_plugins");

#Database
define('MYSQL_HOST',		$config_file->get("Database","host"));
define('MYSQL_USER',		$config_file->get("Database","user"));
define('MYSQL_PASS',		$config_file->get("Database","password"));
define('MYSQL_NAME',		$config_file->get("Database","database"));