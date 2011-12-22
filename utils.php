<?php

DEFINE("HTTP_UNAUTHORIZED", 401);
DEFINE("HTTP_NOT_FOUND", 	404);
DEFINE("HTTP_FORBIDDEN",	403);

/**
 * 
 * Sends a HTTP Response code.
 * @param int $code - the HTTP response code.
 * @uses One of the following codes: 503, 504, 403.
 * @return void
 */

function setHTTP($code) {
	switch($code) { //TODO
		case HTTP_UNAUTHORIZED:	header('HTTP/1.1 401 Unauthorized');exit();break;
		case HTTP_NOT_FOUND:	header('HTTP/1.1 404 Not Found');exit();break;
		case HTTP_FORBIDDEN:	header('HTTP/1.1 403 Forbidden');exit();break;
	}
}

/**
 * 
 * Prints an inrrecovereable error and exits the script execution.
 * @param string $text Error message.
 * @return void
 */

function throwError($text) {
	header('Content-type: text/plain');
	echo $text;
	exit();
}

/**
 * 
 * Sets a message so administrators can review errors, notices and warnings.
 * @param string $text Error message.
 * @param int $type MSG_NOTICE, MSG_ERROR, MSG_WARNING.
 * @return void
 */

function notifyMessage($text, $type) {
	global $simblog;
	
	$simblog['messages'][$type][] = $text;
}

function packCSSfiles() {
	global $simblog;
	
	$css_file = fopen(BLOG_PUBLIC_ROOT."/plugins.css","w+");

	foreach($simblog['plugin_manager'] as $name => $plugin)
		fwrite($css_file, file_get_contents(PLUGIN_DIR."/".$name."/".$plugin->getCSSfile()));
	fclose($css_file);
}

function containsBit($value, $mask) {
	return $mask&$value ? true : false;
}