<?php
namespace Glenn\Routing;

require 'tree.php';
require 'tree_node.php';

class Tree_Array implements Tree {

	private $tree = array();

	public function __constuct() {
		
	}

	public function addTopNode($name, $pattern) {
		$this->tree[$pattern]['name'] = $name;
		return $this;
	}

	public function addChild($parent, $name, $pattern) {
		$path = $this->pathToArray($parent);

		// Point to first level node
		$arrayRef = &$this->tree[$path[0]];

		// Point to correct node
		$levels = count($path);
		for($i = 1; $i < $levels; $i++) {
			$arrayRef = &$arrayRef['children'][$path[$i]];
		}

		// Add child
		$arrayRef['children'][$pattern]['name'] = $name;

		return $this;
	}

	

	public function removeChild($path) {
		throw new Exception('Unsupported operation');
	}

	public function pathToArray($path) {
		return explode('/', $path);
	}

	public function toArray() {
		return $this->tree;
	}

}
