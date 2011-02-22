<?php
namespace Glenn\Routing;

use \mb_strrpos;
use \mb_strlen;
use \mb_substr;

require 'router.php';
require 'tree_array.php';

class Router_Tree implements Router {

	private $tree;

	public function __construct() {
		
	}

	function resolveRoute($request_uri) {

		// Delete last '/' if nothing after it
		$pos = mb_strrpos($request_uri, '/') +1;
		$len = mb_strlen($request_uri);

		if ($pos == $len) {
			$request_uri = mb_substr($request_uri, 0, $len-1);
		}

		// Add / as first char if missing
		if (isset($request_uri[0]) && $request_uri[0] != '/') {
			$request_uri = '/'.$request_uri;
		}

		$uri = $this->uriToArray($request_uri);
		$trace = array();

		$arrayRef = &$this->tree;

		// Point to first level node
		if (isset($arrayRef[$uri[0]])) {
			$arrayRef = &$arrayRef[$uri[0]];
		}
		else if (isset($arrayRef['children']['*'])) {
			$arrayRef = &$arrayRef['*'];
		}
		$trace['nodes'][] = $arrayRef['name'];

		if (isset($arrayRef['controller']) && !empty($arrayRef['controller'])) {
			$trace['controller'] = $arrayRef['controller'];
		}

		if(isset($arrayRef['action']) && !empty($arrayRef['action'])) {
			$trace['action'] = $arrayRef['action'];
		}
		// Point to correct node
		$levels = count($uri);
		for ($i = 1; $i < $levels; $i++) {
			if (isset($arrayRef['children'][$uri[$i]])) {
				$arrayRef = &$arrayRef['children'][$uri[$i]];
				$trace['nodes'][] = $arrayRef['name'];

				if(isset($arrayRef['controller']) && !empty($arrayRef['controller'])) {
					$trace['controller'] = $arrayRef['controller'];
				}

				if(isset($arrayRef['action']) && !empty($arrayRef['action'])) {
					$trace['action'] = $arrayRef['action'];
				}
			}
			else if (isset($arrayRef['children']['*'])) {
				$arrayRef = &$arrayRef['children']['*'];
				$trace['nodes'][] = $arrayRef['name'];
			}
		}
		
		return $trace;
	}

	private function uriToArray($uri) {
		$uri = mb_substr($uri, 1);
		$uri_array = explode('/', $uri);

		return $uri_array;
	}

	public function addRoutes($routes) {
		
		// Check we got an array
		// @todo Investigate if we should take a tree instead
		if ( ! is_array($routes)) {
			throw new Exception('Routes must be an array');
		}

		$this->tree = $routes;
	}

	public function addRoute($route) {
		throw new Exception("Unsupported operation");
	}

	private function patternToArray($uri) {
		$uri_array = explode('/', $uri);

		return $uri_array;
	}

}
