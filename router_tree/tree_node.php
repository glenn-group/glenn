<?php
namespace Glenn\Routing;

class Node {

	private $parent = null;
	private $children = null;

	private $name;
	private $pattern;

	public function __construct($name, $pattern) {
		$this->name = $name;
		$this->pattern = $pattern;
	}

	public function addChild(Node $node) {
		$node->parent = $this;
		$this->children[] = $node;
		return $node;
	}

	public function getChildren() {
		return $this->children;
	}

	public function getParent() {
		return $this->parent;
	}

	public function getName() {
		return $this->name;
	}

}
