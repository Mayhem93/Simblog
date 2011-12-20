<?php

function smarty_isAdminSession() {
	return isset($_SESSION[$_SERVER['REMOTE_ADDR']]['admin']);
}

$simblog['smarty']->registerPlugin("function","isAdminSession","smarty_isAdminSession",true);