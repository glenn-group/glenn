<?php
namespace Glenn\Routing;

require 'tree.php';
require 'tree_node.php';

class Tree_Array {

	private $tree = array();
	private $pointer;

	public function __constuct($arrayRef) {
		$this->tree = $arrayRef;
		$this->pointer = &$this->tree;
	}

	public function addParent($name, $pattern, $path, $show = false) {

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
			$this->pointer = &$this->pointer['children'][$pattern];

		} else { // Pointer is node
			$this->pointer[$pattern]['name'] = $name;
			$this->pointer = &$this->pointer[$pattern];
		}

		return $this;
	}

	public function addChild($name, $pattern) {
		$this->pointer['children'][$pattern]['name'] = $name;
		return $this;
	}	

	public function removeChild($path) {
		throw new Exception('Unsupported operation');
	}

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
