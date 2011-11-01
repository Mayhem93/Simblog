<?php

DEFINE('IN_SITE',true);
include 'smarty/Smarty.class.php';
include '../../libs/class.MySQL.php';
include '../../libs/Lite.php';

$smarty = new Smarty();
$smarty->setTemplateDir('templates');
$smarty->setCacheDir('templates_c');
$smarty->setCompileDir('templates_c');
$smarty->display('index.tpl');