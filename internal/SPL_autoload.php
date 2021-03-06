<?php
/**
* SPL Autoload: autoloads class files.
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
spl_autoload_register("simblogAutoloadLibraries");
spl_autoload_register("simblogAutoloadInternals");
spl_autoload_register("simblogAutoloadPlugins");

function simblogAutoloadLibraries($name) {
	$class_file = BLOG_ROOT."/libs/".$name.".class.php";
	if(file_exists($class_file))
		require_once $class_file;
}

function simblogAutoloadInternals($name) {
	$class_file = BLOG_ROOT."/internal/".$name.".class.php";
	if(file_exists($class_file))
		require_once $class_file;
}

function simblogAutoloadPlugins($name) {
	$class_file = PLUGIN_DIR."/".$name."/".$name.".class.php";
	if(file_exists($class_file))
		require_once $class_file;
}