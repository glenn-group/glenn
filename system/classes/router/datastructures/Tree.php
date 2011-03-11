<?php
namespace glenn\router\datastructures;

/** Tree datastructure.
*/
interface Tree {

	/** Add a node in tree according to path. Set pointer
	*	such that all children will be inserted under this node.
	*	@param string $name Name of node
	*	@param string $pattern Pattern used for matching the node
	*	@param string $path Path to insert node at, for instance /blog/ will
	*	insert node under node "blog".
	*	@param string $data Data to store in node, shall be an controller and
	*	action. Syntax to add is "controller#action". Controller or action may
	*	be skipped, meaning "controller#" and "#action" are allowed.
	*/
	function addParent($name, $pattern, $path, $data);

	/** Add a node in tree according to pointer which is set under last
	*	parent.
	*	@param string $name Name of node
	*	@param string $pattern Pattern used for matching the node
	*	@param string $data Data to store in node, shall be an controller and
	*	action. Syntax to add is "controller#action". Controller or action may
	*	be skipped, meaning "controller#" and "#action" are allowed.
	*/
	function addChild($name, $pattern, $data);

	/** Remove node corresponding to a path
	*	@param string $path Path to insert node at, for instance /blog/ will
	*	insert node under node "blog".
	*	@return boolean True if deleted, otherwise false.
	*/
	function removeChild($path);

	/** Return tree as an array
	*	@return array Array representation of tree
	*/
	function toArray();

}
