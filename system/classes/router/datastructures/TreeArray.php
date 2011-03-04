<?php

namespace glenn\router\datastructures;

use glenn\router\datastructures\Tree;

class TreeArray implements Tree {

	/** Array containing tree
	 */
	private $tree = array();
	/** Pointer to specific location in tree
	 */
	private $pointer;

	private function stringToRoute($config) {
		$getRoute = array();

		$routeInfo = explode('#', $config);
		$getRoute['controller'] = $routeInfo[0];
		$getRoute['action'] = $routeInfo[1];

		return $getRoute;
	}

	private function arrayToRoute($config, $manager) {
		$routes = array();

		// Create routes for resource
		if (isset($config['resource'])) {
			$controller = $config['resource'];

			if ($manager) {
				$routes['get'] = array(
					'controller' => $controller,
					'action' => 'index'
				);
				$routes['post'] = array(
					'controller' => $controller,
					'action' => 'create'
				);
			} else { // Use specified routes
				$routes['get'] = array(
					'controller' => $controller,
					'action' => 'show'
				);
				$routes['post'] = array(
					'controller' => $controller,
					'action' => 'create'
				);
				$routes['put'] = array(
					'controller' => $controller,
					'action' => 'update'
				);
				$routes['delete'] = array(
					'controller' => $controller,
					'action' => 'destroy'
				);
			}
		}

		// Overwrite if any other routes where specified
		unset($config['resource']);
		foreach ($config as $method => $conf) {
			$route[$method] = $this->stringToRoute($conf);
			$routes = array_merge($routes, $route);
		}

		return $routes;
	}

	public function addParent($name, $pattern, $path, $config = null) {
		$routes = array();
		$routes['get'] = null;
		$routes['post'] = null;
		$routes['delete'] = null;
		$routes['put'] = null;
		$manager = true;

		// Check if node is a entry or manager
		if ($pattern[0] == '<' && $len = $pattern[strlen($pattern) - 1] == '>') {
			$manager = false;
			$pattern = substr($pattern, 1, $len);
		}

		// Handle route configuration if exist
		if ($config != null) {

			if (is_string($config)) {
				$routes['get'] = $this->stringToRoute($config);
			}

			if (is_array($config)) {
				$routes = $this->arrayToRoute($config, $manager);
			}
		}

		// Add node
		$path = $this->pathToArray($path);
		if (!$path) {
			$this->pointer = &$this->tree;
		} else {
			// Point to first level node
			$arrayRef = &$this->tree[$path[0]];

			// Point to correct node
			$levels = count($path);
			for ($i = 1; $i < $levels; $i++) {
				$arrayRef = &$arrayRef['children'][$path[$i]];
			}

			//Update pointer
			$this->pointer = &$arrayRef;
		}

		// If pointer is not root
		if ($this->pointer != $this->tree) {
			$this->pointer = &$this->pointer['children'];
		}

		$this->pointer[$pattern]['name'] = $name;
		$this->pointer[$pattern]['pattern'] = $pattern;
		$this->pointer[$pattern] = \array_merge($this->pointer[$pattern], $routes);

		$this->pointer = &$this->pointer[$pattern];

		return $this;
	}

	public function addChild($name, $pattern, $config = null) {

		$routes = array();
		$routes['get'] = null;
		$routes['post'] = null;
		$routes['delete'] = null;
		$routes['put'] = null;
		$manager = true;

		// Check if node is a entry or manager
		if ($pattern[0] == '<' && $len = $pattern[strlen($pattern) - 1] == '>') {
			$manager = false;
			$pattern = substr($pattern, 1, $len);
		}

		// Handle route configuration if exist
		if ($config != null) {

			if (is_string($config)) {
				$routes['get'] = $this->stringToRoute($config);
			}

			if (is_array($config)) {
				$routes = $this->arrayToRoute($config, $manager);
			}
		}

		$this->pointer['children'][$pattern]['name'] = $name;
		$this->pointer['children'][$pattern]['pattern'] = $pattern;
		$this->pointer['children'][$pattern] = \array_merge($this->pointer['children'][$pattern], $routes);
		
		return $this;
	}

	public function removeChild($path) {
		throw new Exception('Unsupported operation');
	}

	/** Split path into segments, seperator is /.
	 * 	@param string $path Path for node in tree.
	 * 	$return array Array containing segments of path.
	 */
	private function pathToArray($path) {
		$path = mb_substr($path, 1);
		$path_array = explode('/', $path);
		if ($path_array[0] == '') {
			return false;
		}
		return $path_array;
	}

	public function toArray() {
		return $this->tree;
	}

}
