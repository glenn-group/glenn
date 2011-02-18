<?php
namespace Glenn\Routing;

interface Router {

	// Find route matching request_uri
	function resolveRoute($request_uri);

	// Optinal
	function addRoute($route);

	// Add serveral routes, for instance array or tree
	function addRoutes($routes);

	

}
