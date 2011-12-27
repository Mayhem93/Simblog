<?php
/**
* The settings are loaded from the config file.
*
* @author		Răzvan Botea<utherr.ghujax@gmail.com>
* @license 		http://www.gnu.org/licenses/gpl.txt
* @copyright	2011-2012 Răzvan Botea
*
* 	PHP 5
*
*	This file is part of Simblog.
*
*   Simblog is free software: you can redistribute it and/or modify
*   it under the terms of the GNU General Public License as published by
*   the Free Software Foundation, either version 3 of the License, or
*   (at your option) any later version.
*
*   Simblog is distributed in the hope that it will be useful,
*   but WITHOUT ANY WARRANTY; without even the implied warranty of
*   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*   GNU General Public License for more details.
*
*   You should have received a copy of the GNU General Public License
*   along with Simblog.  If not, see <http://www.gnu.org/licenses/>.
*/
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
	$simblog['conf']['admin_username']			=	$config_file->get("General", "admin_username");
	$simblog['conf']['admin_password']			=	$config_file->get("General", "admin_password");
	
	#Database
	if($simblog['conf']['database_support']) {
		define('MYSQL_HOST',		$config_file->get("Database","host"));
		define('MYSQL_USER',		$config_file->get("Database","user"));
		define('MYSQL_PASS',		$config_file->get("Database","password"));
		define('MYSQL_DB',		$config_file->get("Database","database"));
	}
}
catch (Config_Lite_Exception_UnexpectedValue $e) {
	throwError("Error: ".$e->getMessage().".\nFile \"global.conf\" is not be configured correctly.");
}