<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);

// The "super global" of the web site
$simblog = array();

DEFINE('BLOG_PUBLIC_ROOT',getcwd());
DEFINE('BLOG_ROOT',realpath(BLOG_PUBLIC_ROOT.'/..'));
DEFINE('PLUGIN_DIR',realpath(BLOG_PUBLIC_ROOT.'/plugins'));
DEFINE('POSTS_DIR',realpath(BLOG_PUBLIC_ROOT."/posts"));
DEFINE('COMMENTS_DIR',realpath(BLOG_PUBLIC_ROOT."/comments"));
DEFINE('MSG_NOTICE',0);
DEFINE('MSG_WARNING',1);
DEFINE('MSG_ERROR', 2);

require_once BLOG_ROOT."/internal/SPL_autoload.php";
include 'smarty/Smarty.class.php';
include BLOG_ROOT.'/utils.php';
include BLOG_ROOT.'/settings.php';

if($simblog['conf']['database_support'])
	$simblog['db'] = new MySQL();

$simblog['plugin_manager'] = Plugin_Controller::init();
$simblog['smarty'] = new Smarty();
$simblog['smarty']->setCacheDir('templates_c');
$simblog['smarty']->setCompileDir('templates_c');
$simblog['smarty']->setTemplateDir('templates');
$simblog['smarty']->assign('simblog_conf',$simblog['conf']);

require BLOG_ROOT.'/internal/smarty_functions.php';

if(!file_exists(BLOG_PUBLIC_ROOT."/plugins.css"))
	packCSSfiles();

$js_file_paths = array();
$js_file_paths['all'] = array();
$js_file_paths['admin'] = array();
$js_file_paths['plugin_page'] = array();
$js_file_paths['blog_pages'] = array();

foreach($simblog['plugin_manager'] as $name => $plugin) {
	$js_req = $plugin->getJSreq();

	foreach($js_req as $where)
	$js_file_paths[$where][] = "plugins/".$name."/".$plugin->getJSfile();
}