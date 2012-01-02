<?php
/**
* The class where you get anything (class instances)
* 	Database, Smarty, Settings, Plugin_Controller
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

class SBFactory
{
	/**
	 * 
	 * These objects are only needed once.
	 */
	private static $db_instance;
	private static $smarty_instance;
	private static $settings_instance;
	private static $plugin_controller_instance;
	
	public static function &__callstatic($method_name, $params) {
		switch($method_name) {
			case "Database":
				if (!self::$db_instance instanceof SBDatabase) {
					$host 		= self::Settings()->getSetting("host");
					$database 	= self::Settings()->getSetting("database");
					$username	= self::Settings()->getSetting("user");
					$password	= self::Settings()->getSetting("password");
						
					self::$db_instance = new SBDatabase($host, $database, $username, $password);
				}
				return self::$db_instance;
				break;
		
			case "Smarty":
				if (!self::$smarty_instance instanceof Smarty) {
					self::$smarty_instance = new Smarty();
					self::$smarty_instance->setCacheDir('templates_c');
					self::$smarty_instance->setCompileDir('templates_c');
					self::$smarty_instance->setTemplateDir('templates');
				}
		
				return self::$smarty_instance;
				break;
		
			case "Settings":
				if (!self::$settings_instance instanceof SBSettings)
					self::$settings_instance = new SBSettings();
		
				return self::$settings_instance;
				break;
		
			case "Plugin_Controller":
				if (!self::$plugin_controller_instance instanceof SBPlugin_Controller)
					self::$plugin_controller_instance = new SBPlugin_Controller();
					
				return self::$plugin_controller_instance;
				break;
			
			default:
				throw new Exception("Method {$method_name} not implemented.");
		}
	}
}