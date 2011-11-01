<?php
if(!defined('IN_SITE'))
	header('HTTP/1.1 404 Not Found');
//error_reporting(E_ERROR | E_WARNING | E_PARSE);

DEFINE('BLOG_PUBLIC_ROOT',getcwd());
DEFINE('BLOG_ROOT',BLOG_PUBLIC_ROOT.'/..');
DEFINE('PLUGIN_DIR',BLOG_ROOT.'/plugins');

include 'libs/Lite.php';
include 'libs/class.MySQL.php';
include 'smarty/Smarty.class.php';

include 'utils.php';
include 'settings.php';
include 'internal/Plugin.class.php';
include 'internal/Plugin.controller.php';

// The "super global" of the web site
global $simblog;
$simblog = array();
$simblog['db'] = new MySQL();

$smarty = new Smarty();
$smarty->setCacheDir('templates_c');
$smarty->setTemplateDir('templates');