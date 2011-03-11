<?php
namespace glenn\event;

class Events
{
	private static $events = array();
	
	/**
	 * Bind a callback function to a named event.
	 *
	 * @param string   $name     Event name
	 * @param Callable $callable Callback function to execute
	 */
	public static function bind($name, $callable)
	{
		if (\is_callable($callable)) {
			self::$events[$name][] = $callable;
		}
	}
	
	/**
	 * Trigger the complete event chain.
	 * 
	 * @param  Event $e The event to trigger
	 * @return array    Results returned by executed callback functions
	 */
	public static function trigger(Event $e)
	{
		if (!array_key_exists($e->name(), self::$events)) {
			return;
		}
		$responses = array();
		foreach (self::$events[$e->name()] as $callable) {
			$responses[] = \call_user_func_array($callable, $e);
		}
		return $responses;
	}
	
	/**
	 * Trigger the event chain until a callback function returns true.
	 * 
	 * @param  Event $e The event to trigger
	 * @return array    Results returned by executed callback functions 
	 */
	public static function triggerUntil(Event $e)
	{
		if (!array_key_exists($e->name(), self::$events)) {
			return;
		}
		$responses = array();
		foreach (self::$events[$e->name()] as $callable) {
			$response = \call_user_func_array($callable, $e);
			if ($response === true) {
				break;
			}
			$responses[] = $response;
		}
		return $responses;
	}
}