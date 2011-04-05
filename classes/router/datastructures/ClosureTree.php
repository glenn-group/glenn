<?php

namespace glenn\router\datastructures;

use glenn\router\datastructures\Tree;

// implements Tree
class ClosureTree
{

	/**
	 * Array containing tree
	 */
	private $tree = array();
	private $controller;

	private function stringToRoute($config)
	{
		$getRoute = array();
		if(\strpos($config, '#') !== false) {
			$routeInfo = explode('#', $config);
			$getRoute['controller'] = $routeInfo[0];
			$getRoute['action'] = $routeInfo[1];
		} else {
			$getRoute['controller'] = null;
			$getRoute['action'] = $config;
		}
		return $getRoute;
	}

	private function arrayToRoute($config, $manager)
	{
		$routes = array();

		foreach ($config as $method => $conf) {
			$route[$method] = $this->stringToRoute($conf);
			$routes = array_merge($routes, $route);
		}

		return $routes;
	}

	public function add($pattern, $config = null, $name = null, $block = null)
	{
		$manager = true;

		if ($config == null) {
			$config = $pattern;
		}

		// Set routes
		if (is_string($config)) {
			$route = $this->stringToRoute($config);
			$routes = array('get' => $route);
			if (!$name) {
				$name = ucfirst($route['action']);
			}
		} else if (is_array($config)) {
			$routes = $this->arrayToRoute($config, $manager);
		}
		
		// Add name and pattern for look-up
		$routes = array_merge($routes, array('name' => $name, 'pattern' => $pattern));

		// Check if node is a entry or manager
		if (!empty($pattern) && $pattern[0] == '<' && $len = $pattern[strlen($pattern) - 1] == '>') {
			$manager = false;
			$pattern = substr($pattern, 1, $len);
		}

		if ($block) {
			$tmpTree = new ClosureTree();
			$tmpTree->controller = $routes['get']['controller'];
			$block($tmpTree);
			$routes['children'] = $tmpTree->toArray();
		} else {
			$routes['children'] = array();
		}

		$this->tree[$pattern] = $routes;
	}

	public function toArray()
	{
		return $this->tree;
	}

}
