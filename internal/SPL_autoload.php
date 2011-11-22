<?php

spl_autoload_register("simblogAutoloadLibraries");
spl_autoload_register("simblogAutoloadInternals");
spl_autoload_register("simblogAutoloadPlugins");

function simblogAutoloadLibraries($name) {
	$class_file = BLOG_ROOT."/libs/{$name}.class.php";
	if(file_exists($class_file))
		require_once $class_file;
}

function simblogAutoloadInternals($name) {
	$class_file = BLOG_ROOT."/internal/{$name}.class.php";
	if(file_exists($class_file))
		require_once $class_file;
}

function simblogAutoloadPlugins($name) {
	$class_file = BLOG_PUBLIC_ROOT."/plugins/{$name}/{$name}.class.php";
	if(file_exists($class_file))
		require_once $class_file;
}