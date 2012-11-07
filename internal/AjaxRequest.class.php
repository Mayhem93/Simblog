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

final class AjaxRequest extends AJAX {

	protected function beforeAction() {}
	protected function afterAction() {}
	
	protected function doAction() {
		switch($_GET['action']) {
			case 'login':

				$this->actionLogin();
				break;
					
			case 'logout':

				$this->actionLogout();
				break;
			case 'deletePost':

				$this->actionDeletePost();
				break;

			case 'addComment':

				$this->actionAddComment();
				break;

			case 'deleteComment':

				$this->actionDeleteComment();
				break;

			case 'getTemplate':

				$this->getTemplate();
				break;
		}
	}
	
	private function actionLogin() {

		if(SBFactory::getCurrentUser()->adminLogIn($_POST['username'], $_POST['password'])) {
			$eventData = array();
			
			SBEventObserver::fire(new SBEvent($eventData, SBEvent::ON_ADMIN_LOGIN));
			SBFactory::Template()->assign('simblog_conf', SBFactory::Settings()->getAll());
			$this->response = SBFactory::Template()->fetch('bits/admin_box.tpl');
			$this->setMimeType('text/html');
		}
		else {
			setHTTP(HTTP_FORBIDDEN);
		}
	}
	
	private function actionLogout() {
		
		if(SBFactory::getCurrentUser()->isAdmin()) {
			SBEventObserver::fire(new SBEvent(array(), SBEvent::ON_ADMIN_LOGOUT));
			$this->response = SBFactory::Template()->fetch('bits/admin_login.tpl');
			$this->setMimeType('text/html');
		}
	}
	
	private function actionDeletePost() {
		
		$this->response = SBFactory::getCurrentUser()->deletePost($_POST['id']) ? '1' : '0';
	}
	
	private function actionDeleteComment() {
		
		$this->response = SBFactory::getCurrentUser()->deleteComment($_POST['cid']);
	}
	
	private function actionAddComment() {

		SBFactory::getCurrentUser()->addComment($_POST['post_id'], $_POST['commentBody'], $_POST['commentName'], $_POST['email']);
		$comment = blog_getCommentById(SBFactory::Database()->getLastInsertID());
		SBFactory::Template()->assign('comment', $comment);
		
		$this->setMimeType('text/html');
		$this->response = SBFactory::Template()->fetch('bits/comment.tpl');
	}

	private function getTemplate() {
		$this->setMimeType('text/html');

		if (isset($_GET['templateData'])) {
			foreach($_GET['templateData'] as $key => $value) {
				SBFactory::Template()->assign($key, $value);
			}
		}

		$this->response = SBFactory::Template()->fetch($_GET['template']);
	}
}
