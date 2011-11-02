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
	$simblog['messages'][$type][] = $text;
}