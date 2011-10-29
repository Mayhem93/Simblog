<?php

DEFINE('IN_SITE',true);
include 'smarty/Smarty.class.php';
include 'libs/class.MySQL.php';
include 'libs/Config_File.class.php';

$smarty = new Smarty();
$smarty->setTemplateDir('templates');
$smarty->setCacheDir('.');
$smarty->display('index.tpl');