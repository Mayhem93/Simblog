<?php
/**
* Initializes resources for use with AJAX requests through lazy loading.
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