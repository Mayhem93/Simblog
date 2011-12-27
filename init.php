<?php
/**
* Script that initializes everything.
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
	$simblog['db'] = new MySQL(true, MYSQL_DB, MYSQL_HOST, MYSQL_USER, MYSQL_PASS);

$simblog['plugin_manager'] = Plugin_Controller::init();
$simblog['smarty'] = new Smarty();
$simblog['smarty']->setCacheDir('templates_c');
$simblog['smarty']->setCompileDir('templates_c');
$simblog['smarty']->setTemplateDir('templates');
$simblog['smarty']->assign('simblog_conf',$simblog['conf']);

require BLOG_ROOT.'/internal/smarty_functions.php';
include BLOG_ROOT.'/internal/Posts.php';

if(!file_exists(BLOG_PUBLIC_ROOT."/plugins.css"))
	packCSSfiles();