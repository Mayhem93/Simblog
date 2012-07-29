<?php

/**
 * Where it all starts.
 *
 * @author		Răzvan Botea<utherr.ghujax@gmail.com>
 * @license 	http://www.gnu.org/licenses/gpl.txt
 * @copyright	2011-2012 Răzvan Botea
 * @link		https://github.com/Mayhem93/Simblog
 * 
 * 	 PHP 5
 *
 *	 This file is part of Simblog.
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
require_once '../init.php';

ob_start('ob_gzhandler');
header("Content-type: text/html; charset=utf-8");

if (!isset($_GET['action']))
	$_GET['action'] = 'main';

session_start();
$eventData = array(
		"action" => &$_GET['action']);
SBEventObserver::fire(new SBEvent($eventData, SBEvent::ON_USER_ACCESS));

$page = new SBPage();
$page->run();

ob_end_flush();
