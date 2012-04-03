<?php

final class SBEventObserver
{
	private static $_attachedListeners = array(
			SBEvent::ON_USER_ACCESS => array(),
			SBEvent::ON_USER_COMMENT => array(),
			SBEvent::ON_ADMIN_LOGIN => array(),
			SBEvent::ON_ADMIN_LOGOUT => array(),
			SBEvent::ON_PLUGIN_LOAD => array(),
			SBEvent::ON_POST_ADD => array(),
			SBEvent::ON_POST_DELETE => array(),
			SBEvent::ON_COMMENT_DELETE => array(),
			SBEvent::ON_PAGE_ADD => array()
	);
	
	public static function addEventListener($eventType, SBPlugin &$object) {
		if(!($object instanceof ITrigger))
			throw new InvalidArgumentException("[ERR_DOES_NOT_IMPLEMENT]: Cannot add object of class '"
					.get_class($object)."'. Object cannot trigger events. The class must implement ITrigger.");
		
		if(!isset(self::$_attachedListeners[$eventType]))
			throw new Exception("Event type ".$eventType." does not exist.");
		
		self::$_attachedListeners[$eventType][] = $object;
	}
	
	public static function removeEventListener($eventType, SBPlugin &$object) {
		if(!($object instanceof ITrigger))
			throw new InvalidArgumentException("[ERR_DOES_NOT_IMPLEMENT]: Cannot add object of class '"
					.get_class($object)."'. Object cannot trigger events. The class must implement ITrigger.");
		
		if(!isset(self::$_attachedListeners[$eventType]))
			throw new UnexpectedValueException("[ERR_EVENT_DOES_NOT_EXIST]: Event type ".$eventType." does not exist.");
		
		$index = array_search($object, self::$_attachedListeners[$eventType]);
		if($index !== false) 
			unset(self::$_attachedListeners[$eventType][$index]);
	}
	
	public static function fire(SBEvent $evt) {
		foreach(self::$_attachedListeners as $eventType => $objects)
			foreach($objects as $obj)
				$obj->trigger($evt);
	}
	
}