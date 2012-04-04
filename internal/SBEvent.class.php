<?php
/**
 * The events class, implements countable.
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

final class SBEvent implements Countable {
	
	private $_data;
	private $_type;
	
	const NUMBER_OF_EVENTS = 10;
	
	/**
	 * Event data:
	 *  - string Action
	 */
	const ON_USER_ACCESS 		= 0;
	
	/**
	 * Event data:
	 *  - postID | READ-ONLY
	 *  - content
	 *  - author
	 */
	const ON_USER_COMMENT		= 1;
	/**
	 * Event data:
	 *  - username | READ-ONLY
	 *  - passwword | READ-ONLY
	 */
	const ON_ADMIN_LOGIN		= 2;
	/**
	 * Event data: none
	 */
	const ON_ADMIN_LOGOUT		= 3;
	/**
	 * Event data:
	 *  - id | READ-ONLY
	 *  - title
	 *  - content
	 *  - category
	 *  - pinned
	 */
	const ON_POST_ADD			= 4;
	/**
	 * Event data:
	 *  - id | READ-ONLY
	 */
	const ON_POST_DELETE		= 5;
	/**
	 * Event data:
	 *  - id | READ-ONLY
	 */
	const ON_COMMENT_DELETE		= 6;
	/**
	 * NYI
	 */
	const ON_PAGE_ADD			= 7;
	/**
	 * Event data:
	 *  - id | READ-ONLY
	 *  - title
	 *  - content
	 *  - category
	 */
	const ON_POST_MOD			= 8;
	/**
	 * NYI TODO
	 */
	const ON_CATEGORY_ADD		= 9;
	
	/**
	 * Constructs an event object which holds data associated with the event triggered.
	 * Used by event listeners.
	 * @param array $data The data that the event will have.
	 * @param int $type @see SBEvent
	 */
	public function __construct(array $data , $type) {
		$this->_data = $data;
		$this->_type = $type;
	}
	
	/**
	 * Returns the type of the event.
	 * @see SBEvent
	 */
	public function getType() {
		return $this->_type;
	}
	
	public function __get($value) {
		if(isset($this->_data[$value]))
			return $this->_data[$value];
		return null;
	}
	
	public function __set($var, $value) {}
	
	public function count() {
		return count($this->_data);
	}
}