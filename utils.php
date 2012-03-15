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
	//TODO
}

function packCSSfiles() {
	$css_file = fopen(BLOG_PUBLIC_ROOT."/plugins.css","w+");

	foreach(SBFactory::PluginManager() as $name => $plugin)
		fwrite($css_file, file_get_contents(PLUGIN_DIR."/".$name."/".$plugin->getCSSfile()));
	
	fclose($css_file);
}

function containsBit($value, $mask) {
	return $mask&$value ? true : false;
}

/**
 * Set's a header Location and end's the script.
 */
function redirectMainPage() {
	header('Location: /');
	ob_end_flush();
	exit();
}

function removeBreaksFromLists($doc_string) {
	$doc = new DOMDocument();
	$doc->loadHTML("<body>".$doc_string."</body>");
	
	$ordered_lists = $doc->getElementsByTagName("ol");
	
	foreach($ordered_lists as $list) {
		$childs = $list->getElementsByTagName("br");
		
		while ($childs->length > 0)
			$list->removeChild($childs->item(0));
	}

	$ordered_lists = $doc->getElementsByTagName("ul");
	foreach($ordered_lists as $list) {
		$childs = $list->getElementsByTagName("br");
		
		while ($childs->length > 0)
			$list->removeChild($childs->item(0));
	}
	$string_with_body = $doc->saveHTML($doc->getElementsByTagName("body")->item(0));
	
	return substr($string_with_body, 6, strlen($string_with_body)-13);
}
