<?php

/**
 * 
 * Sends a HTTP Response code.
 * @param int $code - the HTTP response code.
 * @uses One of the following codes: 503, 504, 403.
 * @return void
 */

function setHTTP($code) {
	switch($code) {
		case 404:
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

function cleanInput() {
	foreach($_POST as $key => $content) 
		$_POST[$key] = mysql_escape_string($content);
	foreach($_GET as $key => $content)
		$_GET[$key] = mysql_escape_string($content);
}

function packCSSfiles() {
	global $simblog;
	
	$css_file = fopen(BLOG_PUBLIC_ROOT."/plugins.css","w+");

	foreach($simblog['plugin_manager'] as $name => $plugin)
		fwrite($css_file, file_get_contents(PLUGIN_DIR."/".$name."/".$plugin->getCSSfile()));
	fclose($css_file);
}