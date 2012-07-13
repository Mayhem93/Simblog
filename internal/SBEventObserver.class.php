<?php
/**
 * EventObserver, static class, used to attach event listeners and fire events.
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

final class SBEventObserver
{
	/**
	 * @var array SBPlugin
	 */
	private static $_attachedListeners = array();
	/**
	 * @var bool
	 */
	private static $_init = false;
	
	/**
	 * Used to attach an object to listen to a specific event triggering.
	 * @param int $eventType See SBEvent
	 * @param SBPlugin $object The object that will listen to the event. Must implement ITrigger.
	 * @throws InvalidArgumentException Thrown if object class does not implement ITrigger.
	 * @throws UnexpectedValueException Event type doesn't exist.
	 * @throws Exception Thrown when events are not initialised.
	 */
	public static function addEventListener($eventType, SBPlugin &$object) {
		if (!self::$_init)
			throw new Exception('Events not initialised.');

		if(!($object instanceof ITrigger))
			throw new InvalidArgumentException("[ERR_DOES_NOT_IMPLEMENT]: Cannot add object of class '"
					.get_class($object)."'. Object cannot trigger events. The class must implement ITrigger.");

		if(!isset(self::$_attachedListeners[$eventType]))
			throw new UnexpectedValueException("[ERR_EVENT_DOES_NOT_EXIST]: Event type ".$eventType." does not exist.");
		
		self::$_attachedListeners[$eventType][] = $object;
	}
	
	/**
	 * @param int $eventType see SBEvent
	 * @param SBPlugin $object The object that will be removed from the listeners.
	 * @throws InvalidArgumentException Object class does not implement ITrigger.
	 * @throws UnexpectedValueException Event doesn't exist.
	 * @throws Exception Thrown when events are not initialised.
	 */
	public static function removeEventListener($eventType, SBPlugin &$object) {
		if (!self::$_init)
			throw new Exception('Events not initialised.');

		if(!($object instanceof ITrigger))
			throw new InvalidArgumentException("[ERR_DOES_NOT_IMPLEMENT]: Cannot add object of class '"
					.get_class($object)."'. Object cannot trigger events. The class must implement ITrigger.");
		
		if(!isset(self::$_attachedListeners[$eventType]))
			throw new UnexpectedValueException("[ERR_EVENT_DOES_NOT_EXIST]: Event type ".$eventType." does not exist.");
		
		$index = array_search($object, self::$_attachedListeners[$eventType]);
		if($index !== false) 
			unset(self::$_attachedListeners[$eventType][$index]);
	}
	
	/**
	 * Called when attachedListeners is not initialised.
	 */
	public static function populateEvents() {
		for($i=0; $i < SBEvent::NUMBER_OF_EVENTS; $i++)
			self::$_attachedListeners[] = array();
		self::$_init = true;
	}
	
	/**
	 * When an event should be fired a call to SBEventObserver::fire() is needed in order for all
	 * listeners that registered to execute.
	 * @param SBEvent $evt The event that is fired.
	 * @throws Exception Thrown when events are not initialised.
	 */
	public static function fire(SBEvent $evt) {
		if (!self::$_init)
			throw new Exception('Events not initialised.');

		foreach(self::$_attachedListeners[$evt->getType()] as $objects)
			$objects->trigger($evt);
	}

	/**
	 *
	 * @return bool
	 */
	public static function isInitialised() {
		return self::$_init;
	}
}