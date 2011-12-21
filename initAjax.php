<?php

DEFINE('BLOG_PUBLIC_ROOT',getcwd());
DEFINE('BLOG_ROOT',realpath(BLOG_PUBLIC_ROOT.'/..'));
DEFINE('PLUGIN_DIR',realpath(BLOG_PUBLIC_ROOT.'/plugins'));
DEFINE('POSTS_DIR',realpath(BLOG_PUBLIC_ROOT."/posts"));
DEFINE('COMMENTS_DIR',realpath(BLOG_PUBLIC_ROOT."/comments"));
$simblog = array();

function prepare_ajaxLogin() {
	global $simblog;
	
	require_once BLOG_ROOT."/internal/SPL_autoload.php";
	include 'smarty/Smarty.class.php';
	include BLOG_ROOT.'/utils.php';
	include BLOG_ROOT.'/settings.php';
	
	if($simblog['conf']['database_support'])
		$simblog['db'] = new MySQL();
	$simblog['smarty'] = new Smarty();
	$simblog['smarty']->setCacheDir('templates_c');
	$simblog['smarty']->setCompileDir('templates_c');
	$simblog['smarty']->setTemplateDir('templates');
	$simblog['smarty']->assign('simblog_conf',$simblog['conf']);
}

function prepare_ajaxLogout() {
	global $simblog;

	include 'smarty/Smarty.class.php';
	
	$simblog['smarty'] = new Smarty();
	$simblog['smarty']->setCacheDir('templates_c');
	$simblog['smarty']->setCompileDir('templates_c');
	$simblog['smarty']->setTemplateDir('templates');
	
	require BLOG_ROOT.'/internal/smarty_functions.php';
}