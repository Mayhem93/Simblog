<?php
/**
* The file where all AJAX requests are made.
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