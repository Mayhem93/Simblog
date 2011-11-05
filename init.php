<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);

// The "super global" of the web site
$simblog = array();

DEFINE('BLOG_PUBLIC_ROOT',getcwd());
DEFINE('BLOG_ROOT',realpath(BLOG_PUBLIC_ROOT.'/..'));
DEFINE('PLUGIN_DIR',realpath(BLOG_ROOT.'/plugins'));
DEFINE('MSG_NOTICE',0);
DEFINE('MSG_WARNING',1);
DEFINE('MSG_ERROR', 2);

include BLOG_ROOT.'/libs/Lite.php';
include BLOG_ROOT.'/libs/class.MySQL.php';
include 'smarty/Smarty.class.php';

include BLOG_ROOT.'/utils.php';
include BLOG_ROOT.'/settings.php';
include BLOG_ROOT.'/internal/Plugin.class.php';
include BLOG_ROOT.'/internal/Plugin.controller.php';

$simblog['db'] = new MySQL();
$simblog['smarty'] = new Smarty();
$simblog['smarty']->setCacheDir('templates_c');
$simblog['smarty']->setCompileDir('templates_c');
$simblog['smarty']->setTemplateDir('templates');