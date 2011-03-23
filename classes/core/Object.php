<?php
namespace glenn\core;

class Object
{
	
	/**
	 *
	 * @param type $class
	 * @param array $params 
	 */
	protected function _create($class, array $params = array())
	{
		$class = Loader::resolve($class);
		if(empty($params)) {
			return new $class();
		} else {
			$reflector = new \ReflectionClass($class);
			return $reflector->newInstanceArgs($params);
		}
	}
	
	/**
	 * Make a static method call.
	 * 
	 * Example: $hash = $this->_call('glenn\security\Hash::sha1', array('password'));
	 *
	 * @param type $call
	 * @param array $params 
	 */
	public function _call($call, array $params = array())
	{
		list($class, $method) = \explode('::', $call);
		$class = Loader::resolve($class);
		\call_user_func_array(array($class, $method), $params);
	}
	
}