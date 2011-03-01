<?php
namespace glenn\event;

class EventHandler
{
	private $events = array();
	
	private static $globalInstance;
	
	public function bind($name, $callable)
	{
		if (\is_callable($callable)) {
			$this->events[$name][] = $callable;
		}
	}
	
	public function trigger(Event $e)
	{
		if (!array_key_exists($e->name(), $this->events)) {
			return;
		}
		foreach ($this->events[$e->name()] as $callable) {
			\call_user_func($callable, $e);
		}
	}
	
	public function triggerUntil(Event $e)
	{
		if (!array_key_exists($e->name(), $this->events)) {
			return;
		}
		foreach ($this->events[$e->name()] as $callable) {
			if (\call_user_func($callable, $e)) {
				return;
			}
			
		}
	}
	
	public static function globalInstance()
	{
		if (!isset(self::$globalInstance)) {
			self::$globalInstance = new self;
		}
		return self::$globalInstance;
	}
}