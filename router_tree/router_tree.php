<?php
namespace Glenn\Routing;

require 'router.php';
require 'tree_array.php';

class Router_Tree implements Router {

	private $tree;

	public function __construct() {
		
	}

	function findRoute($request_uri) {
		$uri = $this->uriToArray($request_uri);
		$trace = array();

		$arrayRef = &$this->tree;

		// Point to first level node
		if(isset($arrayRef[$uri[0]])) {
			$arrayRef = &$arrayRef[$uri[0]];
			$trace[] = $arrayRef['name'];
		}
		else if(isset($arrayRef['children']['*'])) {
			$arrayRef = &$arrayRef['*'];
			$trace[] = $arrayRef['name'];
		}

		// Point to correct node
		$levels = count($uri);
		for($i = 1; $i < $levels; $i++) {
			if(isset($arrayRef['children'][$uri[$i]])) {
				$arrayRef = &$arrayRef['children'][$uri[$i]];
				$trace[] = $arrayRef['name'];
			}
			else if(isset($arrayRef['children']['*'])) {
				$arrayRef = &$arrayRef['children']['*'];
				$trace[] = $arrayRef['name'];
			}
		}
		
		return $trace;
	}

	private function uriToArray($uri) {
		$uri = mb_substr($uri, 1);
		$uri_array = explode('/', $uri);

		return $uri_array;
	}

	public function addRoutes(array $routes) {
		$this->tree = $routes;
	}

	private function patternToArray($uri) {
		$uri_array = explode('/', $uri);

		return $uri_array;
	}

}

// Test

echo '<pre>';

$router = new Router_Tree;

$tree = new Tree_Array;

$tree
	->addTopNode('Blogg', 'blog')
		->addChild('blog', 'Admin', 'fiska')
		->addChild('blog', 'Kategori', '*')
		->addChild('blog/*', 'Titel', '*')
	
	->addTopNode('About', 'about')
		->addChild('about', 'Secret', 'hemligt')
		->addChild('about', 'Fiskmås', '*');

print_r($tree->toArray());

$router->addRoutes($tree->toArray());

$trace = $router->findRoute('/about/hemligt/fdsa');


print_r($trace);
echo '</pre>';

