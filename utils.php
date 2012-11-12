<?php
/**
* Some utilities used by various blog components
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

DEFINE("HTTP_UNAUTHORIZED", 401);
DEFINE("HTTP_NOT_FOUND", 	404);
DEFINE("HTTP_FORBIDDEN",	403);
DEFINE("HTTP_UNAVAILABLE",	503);

/**
 * 
 * Sends a HTTP Response code.
 * @param int $code - the HTTP response code.
 * @uses One of the following codes: 401, 403, 404, 503.
 * @return void
 */

function setHTTP($code) {
	
	switch($code) {
		case HTTP_UNAUTHORIZED:	header('HTTP/1.1 401 Unauthorized');break;
		case HTTP_NOT_FOUND:	header('HTTP/1.1 404 Not Found');break;
		case HTTP_FORBIDDEN:	header('HTTP/1.1 403 Forbidden');break;
		case HTTP_UNAVAILABLE:	header('HTTP/1.1 503 Service Unavailable');break;
		default: return;
	}
	if(constant($code) >= 400)
		exit(0);
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

function containsBit($value, $mask) {
	return $mask&$value ? true : false;
}

/**
 * Set's a header Location and ends the script.
 */
function redirectMainPage() {
	header('Location: /');
	ob_end_flush();
	exit();
}

/**
 * Get either a Gravatar URL or complete image tag for a specified email address.
 *
 * @param string $email The email address
 * @param string $s Size in pixels, defaults to 80px [ 1 - 512 ]
 * @param string $d Default imageset to use [ 404 | mm | identicon | monsterid | wavatar ]
 * @param string $r Maximum rating (inclusive) [ g | pg | r | x ]
 * @param boole $img True to return a complete IMG tag False for just the URL
 * @param array $atts Optional, additional key/value attributes to include in the IMG tag
 * @return String containing either just a URL or a complete image tag
 * @source http://gravatar.com/site/implement/images/php/
 */
function get_gravatar( $email, $s = 80, $d = 'mm', $r = 'g', $img = false, $atts = array() ) {
    $url = 'http://www.gravatar.com/avatar/';
    $url .= md5( strtolower( trim( $email ) ) );
    $url .= "?s=$s&d=$d&r=$r";
    if ( $img ) {
        $url = '<img src="' . $url . '"';
        foreach ( $atts as $key => $val )
            $url .= ' ' . $key . '="' . $val . '"';
        $url .= ' />';
    }
	return $url;
}