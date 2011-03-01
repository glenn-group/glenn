<?php
namespace glenn\router\datastructures;

use \SplStack as SplStack;

/** Helper class to test tree routes by creating a graphical site map
*/
class TreePrinter
{
	/** Stack to traverse tree
	*/
	private static $stack;

	/** Start html for generating html presentation of tree
	*/
	public static $html = '
		<ul>
			<li>
				<div class="root" style="display: block;">/</div>
				<span class="vertline_down"></span>
				<span class="horzline"></span>
			<ul>
	';

	/** Keep track of amount of open tags in list
	*/
	private static $opentags = 1;

	/** Wrapper method to start recursin
	*	@param $tree Array representing tree.
	*/
	public static function traverseTreeWrapper(array $tree) 
	{
		self::$stack = new SplStack();

		// Get children
		$children = array_keys($tree);
		$children = array_reverse($children);

		// Put on stack
		for($i = 0; $i < $c = count($children); $i++) {
			if(isset($tree[$children[$i]]['children'])) {
				self::$stack->push(new aNode($tree, count($tree[$children[$i]]['children']), $tree[$children[$i]]));
			} else {
				self::$stack->push(new aNode($tree, 0, $tree[$children[$i]]));
			}
		}

		// Let traverse handle rest
		self::traverseTree(self::$stack->pop());
	}

	/** Go through all nodes in tree
	*	@param $tree Array representing a tree.
	*/
	private static function traverseTree($tree) 
	{
		// Add graphical "connections" between nodes in tree
		$lines_top = '';
		$lines_bottom = '';

		if ($tree->hasParent) {
			$lines_top = '<span class="vertline_up"></span>';
		}
		if ($tree->children > 1) {
			$lines_bottom = '<span class="vertline_down"></span><span class="horzline"></span>';
		}
		if ($tree->children == 1) {
			$lines_bottom = '<span class="vertline_down"></span><span class="horzline"></span>';
		}

		// If has several children
		if ($tree->children > 1) {
			self::$html .= '<li class="'.$tree->data['name'].'">'.$lines_top.'<div style="display: block;">'.$tree->data['name'] . ' (' . $tree->data['pattern'].')</div>'.$lines_bottom.'<ul>';
			self::$opentags++;
		}

		// If has one child
		if ($tree->children == 1) {
			self::$html .= '<li class="'.$tree->data['name'].'">'.$lines_top.'<div style="display: block;">'.$tree->data['name'] . ' (' . $tree->data['pattern'].')</div>'.$lines_bottom.'<ul>';
			self::$opentags++;
		}

		// If has no children
		if ($tree->children == 0) {
			self::$html .= '<li class="'.$tree->data['name'].'">'.$lines_top.'<div>'.$tree->data['name'] . ' (' . $tree->data['pattern'].')</div></li>';

			$elem = null;
			if( ! self::$stack->isEmpty()) {
				$elem = self::$stack->top();
			}

			// Close tags
			if(isset($elem) && $elem->hasParent != $tree->hasParent) {
				for($i = 0; $i < self::$opentags && $elem->hasParent != $tree->hasParent; $i++) {
					$tree = $tree->hasParent;
					self::$html .= '</ul></li>';
					self::$opentags--;
				}
			}
		} else {
 
			// Get children
			$children = array_keys($tree->data['children']);
			$nr_children = count($children);

			// Put on stack if has children
			for($i = 0; $i < $c = count($children); $i++) {
				if (isset($tree->data['children'][$children[$i]]['children'])) {
					self::$stack->push(new aNode($tree,count($tree->data['children'][$children[$i]]['children']),$tree->data['children'][$children[$i]]));

				} else {
					self::$stack->push(new aNode($tree,0,$tree->data['children'][$children[$i]]));
				}
			}
		}

		// Let traverse handle rest
		if( ! self::$stack->isEmpty()) {
			self::traverseTree(self::$stack->pop());

		} else { // We're finsihed
			self::$html .= '</li></ul>';
		}
	}
}

/** Represents a node, used to make it easy to keep track of
*	which nodes has a parent and children.
*/
class aNode 
{
	public $hasParent;
	public $children;
	public $data;

	public function __construct($hasParent, $children, $data) {
		$this->hasParent = $hasParent;
		$this->children = $children;
		$this->data = $data;
	}
}
