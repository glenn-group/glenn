<?php
namespace glenn\event;

class Events
{
	private static $events = array();
	
	/**
	 * Bind an event action to a specific trigger.
	 *
	 * @param type $name event action
	 * @param type $callable action
	 */
	public static function bind($name, $callable)
	{
		if (\is_callable($callable)) {
			static::$events[$name][] = $callable;
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
		if (!array_key_exists($e->name(), static::$events)) {
			return;
		}
		foreach (static::$events[$e->name()] as $callable) {
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
		if (!array_key_exists($e->name(), static::$events)) {
			return;
		}
		foreach (static::$events[$e->name()] as $callable) {
			if (\call_user_func($callable, $e) === true) {
				return;
			}
			
		}
	}
}