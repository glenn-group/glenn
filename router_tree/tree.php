<?php
namespace Glenn\Routing;

interface Tree {

	function addChild($parent, $name, $pattern);
	function removeChild($pattern);
	function toArray();

}
