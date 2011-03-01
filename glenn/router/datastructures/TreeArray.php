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

	public function addParent($name, $pattern, $path, $config = null) {

		if ($config != null) {
			$config = explode('#', $config);
			$controller = $config[0];
			$action = $config[1];
		} else {
			$controller = null;
			$action = null;
		}

		$path = $this->pathToArray($path);
		if ( ! $path) {
			$this->pointer = &$this->tree;
		} else {
			// Point to first level node
			$arrayRef = &$this->tree[$path[0]];

			// Point to correct node
			$levels = count($path);
			for($i = 1; $i < $levels; $i++) {
				$arrayRef = &$arrayRef['children'][$path[$i]];
			}

			//Update pointer
			$this->pointer = &$arrayRef;
		}

		// If pointer is not root
		if($this->pointer != $this->tree) {
			$this->pointer['children'][$pattern]['name'] = $name;
			$this->pointer['children'][$pattern]['controller'] = $controller;
			$this->pointer['children'][$pattern]['action'] = $action;
			$this->pointer['children'][$pattern]['pattern'] = $pattern;
			$this->pointer = &$this->pointer['children'][$pattern];

		} else { // Pointer is node
			$this->pointer[$pattern]['name'] = $name;
			$this->pointer[$pattern]['controller'] = $controller;
			$this->pointer[$pattern]['action'] = $action;
			$this->pointer[$pattern]['pattern'] = $pattern;
			$this->pointer = &$this->pointer[$pattern];
		}

		return $this;
	}

	public function addChild($name, $pattern, $config = null) {

		if ($config != null) {
			$config = explode('#', $config);
			$controller = $config[0];
			$action = $config[1];
		} else {
			$controller = null;
			$action = null;
		}

		$this->pointer['children'][$pattern]['name'] = $name;
		$this->pointer['children'][$pattern]['pattern'] = $pattern;
		$this->pointer['children'][$pattern]['controller'] = $controller;
		$this->pointer['children'][$pattern]['action'] = $action;
		return $this;
	}	

	public function removeChild($path) {
		throw new Exception('Unsupported operation');
	}

	/** Split path into segments, seperator is /.
	*	@param string $path Path for node in tree.
	*	$return array Array containing segments of path.
	*/
	private function pathToArray($path) {
		$path = mb_substr($path, 1);
		$path_array = explode('/', $path);
		if($path_array[0] == '') {
			return false;
		}
		return $path_array;
	}

	public function toArray() {
		return $this->tree;
	}

}
