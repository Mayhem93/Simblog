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

header("Content-type: text/html; charset=utf-8");

require '../init.php';

if (!isset($_GET['action']))
	$_GET['action'] = 'show';

session_start();
$js_files = array();
$plugin_manager = SBFactory::PluginManager();
SBFactory::Template()->assign("simblog_conf", SBFactory::Settings()->getAll());

switch ($_GET['action']) {
	case 'show':

		foreach($plugin_manager as $name => $plugin)
			if (containsBit($plugin->getJSreq(), PLUGIN_JS_BLOG_PAGES))
				$js_files[] = "plugins/{$name}/".$plugin->getJSfile();

		SBFactory::Template()->assign("blog_posts", blog_getPosts());
		SBFactory::Template()->assign("page_template", "main.tpl");
		SBFactory::Template()->assign("js_files", $js_files);

		break;

	case 'post':
		$post = blog_getPost($_GET['id']);
		$comments = blog_getComments($_GET['id']);
		
		SBFactory::Template()->assign("post", $post);
		SBFactory::Template()->assign("comments", $comments);
		SBFactory::Template()->assign("page_template", "post_page.tpl");
		
		break;
		
	case 'addpost':
		if (!smarty_isAdminSession())
			setHTTP(HTTP_UNAUTHORIZED);
		
		//the user interface to add posts
		if (!count($_POST))
			SBFactory::Template()->assign("page_template", "addPost.tpl");
		
		//where the posts are added
		else {
			$pinned = isset($_POST['pinned']);
			blog_addPost($_POST['post_title'], $_POST['post_content'], $_POST['category'], $pinned);
			header("Location: /");
		}
		break;
		
	case 'modifyPost':
		if (!smarty_isAdminSession())
			setHTTP(HTTP_UNAUTHORIZED);
		
		if (!count($_POST)) {
			$post = blog_getPost($_GET['id']);
			
			SBFactory::Template()->assign("post", $post);
			SBFactory::Template()->assign("page_template", "modifyPost.tpl");
		} else {
			$post_id = $_POST['post_id'];
			$content = $_POST['post_content'];
			$title = $_POST['post_title'];
			$category = $_POST['category'];
			
			var_dump(blog_modifyPost($post_id, $title, $content, $category));
			//header("Location: /");
		}
			
		break;
}

try {
	SBFactory::Template()->display('index.tpl');
}
catch (SmartyException $e) {
	echo $e->getMessage();
}

ob_end_flush();
