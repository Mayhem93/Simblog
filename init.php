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

if(!file_exists("../global.conf")) {
	header("Location: /install");
	exit();
}

error_reporting(-1);

DEFINE('BLOG_PUBLIC_ROOT',getcwd());
DEFINE('BLOG_ROOT',realpath(BLOG_PUBLIC_ROOT.'/..'));
DEFINE('PLUGIN_DIR',BLOG_PUBLIC_ROOT.'\plugins');
DEFINE('MSG_NOTICE',0);
DEFINE('MSG_WARNING',1);
DEFINE('MSG_ERROR', 2);

require_once BLOG_ROOT."/internal/SPL_autoload.php";
include 'smarty/Smarty.class.php';
include BLOG_ROOT.'/utils.php';
require BLOG_ROOT.'/internal/smarty_functions.php';
include BLOG_ROOT.'/internal/Posts.php';
include BLOG_ROOT.'/internal/ITrigger.php';

SBEventObserver::populateEvents();
