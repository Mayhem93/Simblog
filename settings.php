<?php
$config_file = new Config_Lite(BLOG_ROOT.'/global.conf');

try {
	#General
	$simblog['conf']['install_plugin_default']	=	$config_file->get("General", "install_plugin_default");
	$simblog['conf']['disable_plugins']			=	$config_file->getBool("General", "disable_plugins");
	$simblog['conf']['author_name']				= 	$config_file->get("General", "author_name");
	$simblog['conf']['blog_title']				= 	$config_file->get("General", "blog_title");
	$simblog['conf']['email']					=	$config_file->get("General", "email");
	$simblog['conf']['database_support']		=	$config_file->getBool("General", "database_support");
	$simblog['conf']['no_posts_per_page']		=	10;
	
	#Database
	if($simblog['conf']['database_support']) {
		define('MYSQL_HOST',		$config_file->get("Database","host"));
		define('MYSQL_USER',		$config_file->get("Database","user"));
		define('MYSQL_PASS',		$config_file->get("Database","password"));
		define('MYSQL_NAME',		$config_file->get("Database","database"));
	}
}
catch (Config_Lite_Exception_UnexpectedValue $e) {
	throwError("Error: ".$e->getMessage().".\nFile \"global.conf\" may not be configured correctly or does not exit.");
}