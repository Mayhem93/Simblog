<?php
/**
* The AJAX request class used by the blog.
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

include "../internal/AJAX.class.php";
include "../utils.php";

final class AjaxRequest extends AJAX {

	public function __construct($allowed_actions) {
		parent::__construct($allowed_actions);
	}

	protected function beforeAction() {}
	protected function afterAction() {}
	
	protected function doAction() {
		switch($_GET['action']) {
			case "login":
				include 'smarty/Smarty.class.php';
				require_once BLOG_ROOT."/internal/smarty_functions.php";

				$this->actionLogin();
				break;
					
			case "logout":
				include 'smarty/Smarty.class.php';
				require BLOG_ROOT.'/internal/smarty_functions.php';

				$this->actionLogout();
				break;
			case "deletePost":
				include 'smarty/Smarty.class.php';
				require_once BLOG_ROOT."/internal/Posts.php";
				require BLOG_ROOT.'/internal/smarty_functions.php';

				$this->actionDeletePost();
				break;

			case "addComment":
				include 'smarty/Smarty.class.php';
				require_once BLOG_ROOT."/internal/Posts.php";
				require BLOG_ROOT.'/internal/smarty_functions.php';

				$this->actionAddComment();
				break;

			case "deleteComment":
				require_once BLOG_ROOT."/internal/Posts.php";

				$this->actionDeleteComment();
				break;
		}
	}
	
	private function actionLogin() {
		session_start();
		
		$admin_username = SBFactory::Settings()->getSetting("admin_username");
		$admin_password = SBFactory::Settings()->getSetting("admin_password");
		
		if(($_POST['username'] == $admin_username) && ($_POST['password'] == $admin_password)) {
			$_SESSION[$_SERVER['REMOTE_ADDR']]['admin'] = true;
			SBFactory::Template()->assign('simblog_conf', SBFactory::Settings()->getAll());
			$this->response = SBFactory::Template()->fetch('bits/admin_box.tpl');
			$this->setMimeType("text/html");
		}
		else
			setHTTP(HTTP_FORBIDDEN);
	}
	
	private function actionLogout() {
		session_start();
		
		if(smarty_isAdminSession()) {
			unset($_SESSION[$_SERVER['REMOTE_ADDR']]);
			$this->response = SBFactory::Template()->fetch('bits/admin_login.tpl');
			session_regenerate_id(true);
			$this->setMimeType("text/html");
		}
	}
	
	private function actionDeletePost() {
		session_start();
		
		if(!smarty_isAdminSession())
			setHTTP(HTTP_UNAUTHORIZED);
		
		$this->response = blog_deletePost($_POST['id']) ? "1" : "0";
	}
	
	private function actionDeleteComment() {
		
		$this->response = blog_deleteComment($_POST['cid']);
	}
	
	private function actionAddComment() {
		
		blog_addComment($_POST['post_id'], $_POST['commentBody'], $_POST['commentName']);
		$comment = blog_getCommentById(SBFactory::Database()->getLastInsertID());
		SBFactory::Template()->assign("comment", $comment);
		
		$this->setMimeType("text/html");
		$this->response = SBFactory::Template()->fetch("bits/comment.tpl");
	}
}
