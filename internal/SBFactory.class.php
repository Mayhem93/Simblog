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
	
	/**
	 * Simblog Database.
	 * @return SBDatabase Returns the database object.
	 */
	public static function Database() {
		if (!self::$db_instance instanceof SBDatabase) {
			$host 		= self::Settings()->getSetting("host");
			$database 	= self::Settings()->getSetting("database");
			$username	= self::Settings()->getSetting("user");
			$password	= self::Settings()->getSetting("password");
			$port 		= self::Settings()->getSetting("port");
		
			self::$db_instance = new SBDatabase($host, $database, $username, $password, $port);
		}
		
		return self::$db_instance;
	}
	
	/**
	 * Simblog templating system, based on smarty.
	 * @return Smarty Returns a smarty object.
	 */
	public static function Template() {
		if (!self::$smarty_instance instanceof Smarty) {
			self::$smarty_instance = new Smarty();
			self::$smarty_instance->setCacheDir('templates_c');
			self::$smarty_instance->setCompileDir('templates_c');
			self::$smarty_instance->setTemplateDir('templates');
		}
		
		return self::$smarty_instance;
	}
	
	/**
	 * Simblog settings
	 * @return SBSettings Returns an object used for managing settings.
	 */
	public static function Settings() {
		if (!self::$settings_instance instanceof SBSettings)
			self::$settings_instance = new SBSettings();
		
		return self::$settings_instance;		
	}
	
	/**
	 * Simblog plugin manager
	 * @return SBPlugin_Controller Returns an object used for managing plugins.
	 */
	public static function PluginManager() {
		if (!self::$plugin_controller_instance instanceof SBPlugin_Controller)
			self::$plugin_controller_instance = new SBPlugin_Controller();
			
		return self::$plugin_controller_instance;
	}
}
