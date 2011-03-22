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
	
}