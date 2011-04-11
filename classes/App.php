<?php
namespace glenn;

use glenn\controller\Dispatcher;

abstract class App
{
	/**
	 * @var Dispatcher
	 */
	public static $dispatcher;
	
	/**
	 * @return Dispatcher
	 */
	public static function dispatcher()
	{
		if (static::$dispatcher === null) {
			static::$dispatcher = new Dispatcher();
		}
		return static::$dispatcher;
	}
}