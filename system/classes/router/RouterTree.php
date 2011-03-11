<?php

namespace glenn\router;

use glenn\http\Request;

/** Router using tree-based routing.
 */
class RouterTree extends Router {

	/** Structure containing routes
	 */
	private $tree;
	/** 	Specified offset for URL
	 */
	private $url_offset;

	/**
	 * Creates an default route
	 * @param string $url_offset Offset for url
	 */
	public function __construct($url_offset = '') {
		$this->url_offset = $url_offset;

		// Default pattern
		$this->tree['*'] = array(
			'pattern' => '*',
			'name' => 'default',
			'get' => array(
				'controller' => 'index',
				'action' => 'index'
			)
		);

		parent::__construct();
	}

	/** Find a matching route
	 * 	@param string $request_uri The URI used to accecss webpage ($_SERVER['REQUEST_URI'])
	 * 	@return array Array with indices 'controller' and 'action'.
	 * 	@throws Exception If no route found.
	 */
	public function resolveRoute(Request $request) {
		// Use offset
		$offset_length = strlen($this->url_offset);
		$request_uri = substr($request->uri(), $offset_length);
		$method = strtolower($request->method());
		
		// Store information retrived while traversing the tree
		$trace = array();

		// Delete ending '/'
		$request_uri = rtrim($request_uri, '/');

		// Split URI into segments
		$uri = $this->uriToArray($request_uri);

		// Point to root node
		$arrayRef = &$this->tree;

		if (isset($arrayRef[$uri[0]])) { // Direct match
			$arrayRef = &$arrayRef[$uri[0]];
		} else if (isset($arrayRef['*'])) { // Wildcard exist
			$arrayRef = &$arrayRef['*'];
		} else { // No found
			throw new \Exception('404');
		}

		$trace['nodes'][] = $arrayRef['name'];

		// Store toplevel controller and action
		if (isset($arrayRef[$method]['controller']) && !empty($arrayRef[$method]['controller'])) {
			$trace['controller'] = \ucfirst($arrayRef[$method]['controller']);
		}
		if (isset($arrayRef[$method]['action']) && !empty($arrayRef[$method]['action'])) {
			$trace['action'] = \lcfirst($arrayRef[$method]['action']);
		}

		// Point to correct node
		$levels = count($uri);

		for ($i = 1; $i < $levels; $i++) {
			if (isset($arrayRef['children'][$uri[$i]])) {
				$arrayRef = &$arrayRef['children'][$uri[$i]];
				$trace['nodes'][] = $arrayRef['name'];
			} else if (isset($arrayRef['children']['*'])) {
				$arrayRef = &$arrayRef['children']['*'];
				$trace['nodes'][] = $arrayRef['name'];
			}

			// Overwrite controller and action if found
			if (isset($arrayRef[$method]['controller']) && !empty($arrayRef[$method]['controller'])) {
				$trace['controller'] = $arrayRef[$method]['controller'];
			}
			if (isset($arrayRef[$method]['action']) && !empty($arrayRef[$method]['action'])) {
				$trace['action'] = $arrayRef[$method]['action'];
			}
		}

		return $trace;
	}

	/** Extract segments from URI
	 * 	@param string $uri URI where each segment is separated with /
	 * 	@return array Array where each element correspond to an segment of URI.
	 */
	private function uriToArray($uri) {
		$uri = mb_substr($uri, 1);
		$uri_array = explode('/', $uri);

		return $uri_array;
	}

	/** Add routes to be used by resolveRoute.
	 * 	@param array $routes Array with routes, compatible with Tree->toArray().
	 */
	public function addRoutes(array $routes) {
		$this->tree = $routes;
	}

	/** Unsupported, add all routes at once instead using addRoutes.
	 * 	@throws Exception Not supported operation, always throwed.
	 */
	public function addRoute($route) {
		throw new Exception("Unsupported operation");
	}

}
