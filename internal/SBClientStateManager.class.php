<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Kingaa
 * Date: 10/26/12
 * Time: 6:19 PM
 * To change this template use File | Settings | File Templates.
 */
class SBClientStateManager {

	public function __construct() {
		if (session_id() !== '') {
			throw new Exception('Session already started.');
		}

		if (ini_get('session.auto_start') != '1') {
			session_name('SBSESSID');
		}

		session_set_cookie_params(60*60*24*30); //1 month
		session_start();
	}

	public function sessionEnd() {
		return session_destroy();
	}

	public function getSessionID() {
		return session_id();
	}

	public function refreshSession() {
		session_regenerate_id(true);

		return session_id();
	}

	public function getCookieVar($name = null) {
		if ($name === null) {
			return $_COOKIE;
		}

		return isset($_COOKIE[$name]) ? $_COOKIE[$name] : null;
	}

	public function setCookieVar($name, $value, $expire) {
		return setcookie($name, $value, $expire);
	}

	public function getSessionVar($name) {
		return isset($_SESSION[$name]) ? $_COOKIE[$name] : null;
	}

	public function setSessionVar($name, $value) {
		$_SESSION[$name] = $value;
	}

	public function unsetSessionVar($name) {
		unset($_SESSION[$name]);
	}

}
