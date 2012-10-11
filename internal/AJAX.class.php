<?php
/**
* Abstract class for AJAX requests where plugins can inherit.
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

abstract class AJAX {
	protected $actions_allowed 	= array();
	protected $response			= "";
	private $mime_type			= "text/plain";
	
	/**
	 * Constructs AJAX object. Should always be called in child classes
	 * in their constructor and be the first instruction.
	 * @param array $actions Array of strings containing valid actions. Actions
	 * are handled in doAction() method by the child class.
	 */
	public function __construct(array $actions) {
		$this->actions_allowed = $actions;
		$this->checkHXR(); 
		
		DEFINE('BLOG_PUBLIC_ROOT',getcwd());
		DEFINE('BLOG_ROOT',realpath(BLOG_PUBLIC_ROOT.'/..'));
		DEFINE('PLUGIN_DIR',realpath(BLOG_PUBLIC_ROOT.'/plugins'));
		include BLOG_ROOT."/internal/ITrigger.php";
		require_once BLOG_ROOT."/internal/SPL_autoload.php";
		SBFactory::PluginManager();

	}

    /**
     * Runs the ajax request.
     */
    public function run() {
        ob_start("ob_gzhandler");
        $this->beforeAction();
        $this->doAction();
        header("Content-type: ".$this->mime_type);

        echo $this->response;
        $this->afterAction();

        ob_end_flush();
        exit();
    }

	/**
	 * Checks if the incoming connection is made with AJAX (not secure).
	 * Also checks if the action supplied is valid.
	 */
	protected function checkHXR() {
		if(!isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
			header('HTTP/1.1 403 Forbidden');
			exit();
		}
		
		if(!isset($_GET['action']))
			$_GET['action'] = "";
		
		if(!in_array($_GET['action'], $this->actions_allowed)) {
			echo "Unrecognized action: \"".$_GET['action']."\"";
			exit();
		}
	}
	
	/**
	 * Sets a "Content-type" header which will be sent after doAction().
	 * @param string $type The MIME-type of the content.
	 */
	protected function setMimeType($type) {
		$this->mime_type = $type;
	}
	
	/**
	 * Main processing of data is being done here. This function
	 * should set the responses variable if it's to send a response.
	 */
	abstract protected function doAction();
	
	/**
	 * Method that is called before doAction().
	 */
	abstract protected function beforeAction();
	
	/**
	 * Method that is called after doAction();
	 */
	abstract protected function afterAction();
}
