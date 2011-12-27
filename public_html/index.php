<?php

/**
 * Where it all starts.
 *
 * @author		Răzvan Botea<utherr.ghujax@gmail.com>
 * @license 	http://www.gnu.org/licenses/gpl.txt
 * @copyright	2011-2012 Răzvan Botea
 * @link		https://github.com/Mayhem93/Simblog
 * 
 * 	 PHP 5
 *
 *	 This file is part of Simblog.
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
ob_start('ob_gzhandler');
if(!file_exists("../global.conf")) {
	echo "Configuration file does not exist. Installation has not been carried out. <a href=\"install\">Install here.</a>";
	exit();
}
DEFINE('IN_SITE', true);

require '../init.php';

if (!isset($_GET['action']))
	$_GET['action'] = 'show';

session_start();
$js_files = array();

switch ($_GET['action']) {
	case 'show':

		foreach($simblog['plugin_manager'] as $name => $plugin)
			if (containsBit($plugin->getJSreq(), PLUGIN_JS_BLOG_PAGES))
				$js_files[] = "plugins/{$name}/".$plugin->getJSfile();

		$simblog['smarty']->assign("blog_posts", blog_getPosts());
		$simblog['smarty']->assign("page_template", "main.tpl");
		$simblog['smarty']->assign("js_files", $js_files);

		break;

	case 'addpost':
		//the user interface to add posts
		if (!count($_POST)) {
			//TODO add posts user interface
		}
		//where the posts are added
		else {
			blog_addPost($_POST['title'], $_POST['content']);
		}
		break;
}

try {
	$simblog['smarty']->display('index.tpl');
}
catch (SmartyException $e) {
	echo $e->getMessage();
}

ob_end_flush();
?>
