<?php

function smarty_isAdminSession() {
	return isset($_SESSION[$_SERVER['REMOTE_ADDR']]['admin']);
}

function smarty_getSetting($setting) {
	return SBFactory::Settings()->getSetting($setting);
}