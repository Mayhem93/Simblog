<?php

require '../ajaxInit.php';//TODO

switch($_GET['action']) {
	case 'login':
		session_start();
		if(($_POST['username'] == $simblog['conf']['admin_username']) && ($_POST['password'] == $simblog['conf']['admin_password'])) {
			$_SESSION[$_SERVER['REMOTE_ADDR']]['admin'] = true;
			$simblog['smarty']->fetch('bits/admin_box.tpl');
		}
		else
			header("HTTP/1.1 401 Unauthorized");
	
		break;
	case 'logout':
		session_start();
		if(isAdminSession()) {
			unset($_SESSION[$_SERVER['REMOTE_ADDR']]['admin']);
			$simblog['smarty']->fetch('bits/admin_login.tpl');
		}
		break;
}