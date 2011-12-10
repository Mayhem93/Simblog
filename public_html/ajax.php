<?php

require '../init.php';

switch($_GET['action']) {
	case 'login':
		session_start();
		if(($_GET['username'] == $simblog['conf']['admin_username']) && ($_GET['password'] == $simblog['conf']['admin_password'])) {
			$_SESSION[$_SERVER['REMOTE_ADDR']]['admin'] = true;
			$simblog['smarty']->fetch('bits/admin_box.tpl');
		}
		else
			echo "0";
	
		break;
	case 'logout':
		session_start();
		if(isAdminSession()) {
			unset($_SESSION[$_SERVER['REMOTE_ADDR']]['admin']);
			$simblog['smarty']->fetch('bits/admin_login.tpl');
		}
		break;
}