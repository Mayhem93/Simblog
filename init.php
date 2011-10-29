<?php

error_reporting(E_ERROR | E_WARNING | E_PARSE);

DEFINE('PLUGIN_DIR','/plugins');
DEFINE('BLOG_ROOT',getcwd());

include 'utils.php';
include 'smarty/Smarty.class.php';
include 'libs/class.MySQL.php';
include 'libs/Config_File.class.php';

$smarty = new Smarty();
$smarty->setCacheDir('templates_c');
$smarty->setTemplateDir('templates');