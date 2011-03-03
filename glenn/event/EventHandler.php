<?php
namespace glenn\event;

class EventHandler
{
	private $events = array();
	
	private static $globalInstance;
	
	/**
	 * Bind an event action to a specific trigger.
	 *
	 * @param type $name event action
	 * @param type $callable action
	 */
	public static function bind($name, $callable)
	{
		if (\is_callable($callable)) {
			static::instance()->events[$name][] = $callable;
		}
	}
	
	/**
	 * Trigger the complete event chain.
	 * 
	 * @param Event $e
	 * @return void 
	 */
	public static function trigger(Event $e)
	{
		if (!array_key_exists($e->name(), static::instance()->events)) {
			return;
		}
		foreach (static::instance()->events[$e->name()] as $callable) {
			\call_user_func($callable, $e);
		}
	}
	
	/**
	 * Trigger the event chain until a function returns true.
	 * 
	 * @param Event $e
	 * @return void 
	 */
	public static function triggerUntil(Event $e)
	{
		if (!array_key_exists($e->name(), static::instance()->events)) {
			return;
		}
		foreach ($this->events[$e->name()] as $callable) {
			if (\call_user_func($callable, $e)) {
				return;
			}
			
		}
	}
	
	/**
	 * Return the EventHandler singleton.
	 *
	 * @return EventHandler
	 */
	private static function instance()
	{
		if (!isset(self::$globalInstance)) {
			self::$globalInstance = new self;
		}
		return self::$globalInstance;
	}
}