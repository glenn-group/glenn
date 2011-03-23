<?php

namespace glenn\loader;

class G
{
	
	public static function make($class, array $params = array())
	{
		$class = Loader::resolve($class);
		if(empty($params)) {
			return new $class();
		} else {
			$reflector = new \ReflectionClass($class);
			return $reflector->newInstanceArgs($params);
		}
	}
	
	public static function call($call, array $params = array())
	{
		list($class, $method) = \explode('::', $call);
		$class = Loader::resolve($class);
		\call_user_func_array(array($class, $method), $params);
	}
	
	public static function resolve($class)
	{
		return Loader::resolve($class);
	}
	
}