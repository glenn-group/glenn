<?php
namespace glenn\router;

/** Router using tree-based routing.
*/
class RouterTree extends Router 
{
	/** Structure containing routes
	*/
	private $tree;

	/** Creates an default route
	*/
	public function __construct() 
	{
		// Default pattern
		$this->tree['*'] = array(
			'pattern' => '*',
			'name' => 'default',
			'controller' => 'index',
			'action' => 'index'
		);

		parent::__construct();
	}

	/** Find a matching route
	*	@param string $request_uri The URI used to accecss webpage ($_SERVER['REQUEST_URI'])
	*	@return array Array with indices 'controller' and 'action'.
	*	@throws Exception If no route found.
	*/
	public function resolveRoute($request_uri) 
	{
		// Store information retrived while traversing the tree
		$trace = array();

		// Delete ending '/'
		rtrim($request_uri, '/');

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
		if (isset($arrayRef['controller']) && !empty($arrayRef['controller'])) {
			$trace['controller'] = \ucfirst($arrayRef['controller']);
		}
		if(isset($arrayRef['action']) && !empty($arrayRef['action'])) {
			$trace['action'] = \lcfirst($arrayRef['action']);
		}

		// Point to correct node
		$levels = count($uri);
		for ($i = 1; $i < $levels; $i++) {
			if (isset($arrayRef['children'][$uri[$i]])) {
				$arrayRef = &$arrayRef['children'][$uri[$i]];
				$trace['nodes'][] = $arrayRef['name'];

				// Overwrite controller and action if found
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

	/** Extract segments from URI
	*	@param string $uri URI where each segment is separated with /
	*	@return array Array where each element correspond to an segment of URI.
	*/
	private function uriToArray($uri) 
	{
		$uri = mb_substr($uri, 1);
		$uri_array = explode('/', $uri);

		return $uri_array;
	}

	/** Add routes to be used by resolveRoute.
	*	@param array $routes Array with routes, compatible with Tree->toArray().
	*/
	public function addRoutes(array $routes) 
	{
		$this->tree = $routes;
	}

	/** Unsupported, add all routes at once instead using addRoutes.
	*	@throws Exception Not supported operation, always throwed.
	*/
	public function addRoute($route) 
	{
		throw new Exception("Unsupported operation");
	}

}
