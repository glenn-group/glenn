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

// Name, pattern, parent_path
// Name, pattern

$tree
	->addParent('Admin', 'secreturl', '/')
		->addChild('controller', '*')

	->addParent('Blogg', 'blog', '/')
		->addParent('kategori', '*', '/blog')
			->addChild('title', '*')
	
	->addParent('About', 'about', '/')
		->addChild('mail', 'mail')
		->addChild('namn', '*');

/*
$tree ->addNodes(function () {
	$this->addNode('Blogg', 'blog', function() {
		$this->addNode('Kategori', '*', function() {
			$this->addNode('Titel', '*');
		});
		$this->addNode('About', '*', function() {
			$this->addNode('Secret');
			$this->addNode('Fiskmås');
		});
	})
});
*/

$router->addRoutes($tree->toArray());

if(isset($_GET['route'])) {
	$route = $_GET['route'];
	$trace = $router->findRoute($route);
	//print_r($trace);
}
/*$tree = array(
	'Blogg', 'blog', array(
		'Kategori', '*', array(
			'titel', '*'
		)
	),

	'About', 'about', array(
		'Mail', 'mail',
		'namn', '*'
	),

	'Admin', 'admin', array(
		'Controller', '*'
	)
);*/
print_r($tree->toArray());
echo '</pre>';

