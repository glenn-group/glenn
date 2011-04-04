<?php

namespace glenn\router\datastructures;

use glenn\router\datastructures\Tree;
// implements Tree
class ClosureTree {

	/** Array containing tree
	 */
	private $tree = array();
	
	private function stringToRoute($config) {
		$getRoute = array();
		$routeInfo = explode('#', $config);
		$getRoute['controller'] = $routeInfo[0];
		$getRoute['action'] = $routeInfo[1];
		return $getRoute;
	}

	private function arrayToRoute($config, $manager) {
		$routes = array();

		foreach ($config as $method => $conf) {
			$route[$method] = $this->stringToRoute($conf);
			$routes = array_merge($routes, $route);
		}

		return $routes;
	}
	
	public function add($pattern,$config = null,$name = null,$block = null){
		
		$manager = true;
		$routes = array();
		
		if($config == null){
			$config = $pattern;
		}
		
		if (is_string($config)) {
			$route = $this->stringToRoute($config);
			$routes['get'] = $route;
			if(!$name){
				$name = ucfirst($route['action']);
			}
		}

		if (is_array($config)) {
			$routes = array_merge($routes,$this->arrayToRoute($config, $manager));
		}
	
		$routes = array_merge($routes,array('name' => $name,'pattern' => $pattern));
		
		// Check if node is a entry or manager
		if (!empty($pattern) && $pattern[0] == '<' && $len = $pattern[strlen($pattern) - 1] == '>') {
			$manager = false;
			$pattern = substr($pattern, 1, $len);
		}
	
		$tmpTree = new ClosureTree();
		if ($block){
			$block($tmpTree);
		}
		$routes['children'] = $tmpTree->toArray();
		
		$this->tree[$pattern] = $routes;
	}
	
	public function toArray() {
		return $this->tree;
	}

}
