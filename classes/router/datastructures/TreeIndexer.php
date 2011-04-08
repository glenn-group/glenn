<?php
namespace glenn\router\datastructures;

/**
 * Description of TreeTraverser
 *
 * @author peter
 */
class TreeIndexer
{
	/** Stack to traverse tree
	*/
	private $stack;
	private $root;

	private $nameIndex = null;

	public function buildNameIndex($routes)
	{
		$this->root = $routes;
		$test = $this;
		$this->traverseWrapper($routes, 'addNameIndexElement');
		
		return $this->nameIndex;
	}

	private function addNameIndexElement($tree) {
		$namePath = $tree->data['name'];
		$patternPath = $tree->data['pattern'];

		// Get parents
		$elem = $tree;
		while($elem->hasParent != $this->root) {
			$elem = $elem->hasParent;
			$namePath = $elem->data['name'] . '/' . $namePath;
			$patternPath = $elem->data['pattern'] . '/' . $patternPath;
		}

		$this->nameIndex[$namePath] = $patternPath;
	}

	/** Wrapper method to start recursin
	*	@param $tree Array representing tree.
	*/
	private function traverseWrapper(array $tree, $callback)
	{
		$this->root = $tree;
		$this->stack = new \SplStack();

		// Get children
		$children = array_keys($tree);
		$children = array_reverse($children);

		// Put on stack
		for($i = 0; $i < $c = count($children); $i++) {
			if(isset($tree[$children[$i]]['children'])) {
				$this->stack->push(new aNode($tree, count($tree[$children[$i]]['children']), $tree[$children[$i]]));
			} else {
				$this->stack->push(new aNode($tree, 0, $tree[$children[$i]]));
			}
		}

		// Let traverse handle rest
		return $this->traverseRecursive($this->stack->pop(), $callback);
	}

	/** Go through all nodes in tree
	*	@param $tree Array representing a tree.
	*/
	private function traverseRecursive($tree, $callback)
	{
		$this->$callback($tree);

		// If has no children
		if ($tree->children > 0) {
			// Get children
			$children = array_keys($tree->data['children']);
			$nr_children = count($children);

			// Put on stack if has children
			for($i = 0; $i < $c = count($children); $i++) {
				if (isset($tree->data['children'][$children[$i]]['children'])) {
					$this->stack->push(new aNode($tree,count($tree->data['children'][$children[$i]]['children']),$tree->data['children'][$children[$i]]));

				} else {
					$this->stack->push(new aNode($tree,0,$tree->data['children'][$children[$i]]));
				}
			}
		}

		// Let traverse handle rest
		if( ! $this->stack->isEmpty()) {
			$this->traverseRecursive($this->stack->pop(), $callback);
		}
	}
}

/** Represents a node, used to make it easy to keep track of
 * 	which nodes has a parent and children.
 */
class aNode {

	public $hasParent;
	public $children;
	public $data;

	public function __construct($hasParent, $children, $data) {
		$this->hasParent = $hasParent;
		$this->children = $children;
		$this->data = $data;
	}

}