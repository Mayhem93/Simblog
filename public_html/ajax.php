<?php

if(!isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
	header('HTTP/1.1 403 Forbidden');
	exit();
}

require '../initAjax.php';

switch($_GET['action']) {
	case 'login':
		session_start();
		prepare_ajaxLogin();
		
		if(($_POST['username'] == $simblog['conf']['admin_username']) && ($_POST['password'] == $simblog['conf']['admin_password'])) {
			$_SESSION[$_SERVER['REMOTE_ADDR']]['admin'] = true;
			echo $simblog['smarty']->fetch('bits/admin_box.tpl');
		}
		else {
			header('HTTP/1.1 401 Unauthorized');
			exit();
		}
		break;
	case 'logout':
		session_start();
		prepare_ajaxLogout();
		
		if(smarty_isAdminSession()) {
			unset($_SESSION[$_SERVER['REMOTE_ADDR']]['admin']);
			echo $simblog['smarty']->fetch('bits/admin_login.tpl');
		}
		break;
}