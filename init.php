<?php
//error_reporting(E_ERROR | E_WARNING | E_PARSE);

// The "super global" of the web site
$simblog = array();

DEFINE('BLOG_PUBLIC_ROOT',getcwd());
DEFINE('BLOG_ROOT',BLOG_PUBLIC_ROOT.'/..');
DEFINE('PLUGIN_DIR',BLOG_ROOT.'/plugins');
DEFINE('MSG_NOTICE',0);
DEFINE('MSG_WARNING',1);
DEFINE('MSG_ERROR', 2);

include 'libs/Lite.php';
include 'libs/class.MySQL.php';
include 'smarty/Smarty.class.php';

include 'utils.php';
include 'settings.php';
include 'internal/Plugin.class.php';
include 'internal/Plugin.controller.php';

$simblog['db'] = new MySQL();
$simblog['smarty'] = new Smarty();
$simblog['smarty']->setCacheDir('templates_c');
$simblog['smarty']->setCompileDir('templates_c');
$simblog['smarty']->setTemplateDir('templates');