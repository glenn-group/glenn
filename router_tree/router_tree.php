<?php
namespace Glenn\Routing;

require 'router.php';
require 'tree_array.php';

class Router_Tree {

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

$router = new Router_Tree;

$tree = new Tree_Array;

// Name, pattern, parent_path
// Name, pattern

$tree
	->addParent('Admin', 'secreturl', '/')
		->addParent('Users', 'secreturl', '/secreturl')
			->addChild('user', '*')

	->addParent('Blogg', 'blog', '/')
		->addParent('kategori', '*', '/blog')
			->addChild('title', 'as')
			->addParent('id', '*', '/blog/*')
				->addChild('Gds', '*')
		->addParent('fisken', 'adsa*', '/blog')
			->addChild('isd', 'karl')
	
	->addParent('About', 'about', '/')
		->addChild('email', 'contact')
		->addChild('person', 'a')
		->addChild('person', 'b');

/* Alternative syntax
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

require 'tree_printer.php';
TreePrinter::traverseTreeWrapper($tree->toArray());

//echo '<pre>';
//print_r($tree->toArray());
//echo '</pre>';
?>

<html>
<head>
	<title></title>
	<style>
	body {
		width: 10000px;
	}

	ul {
		list-style: none;
		text-align: center;
		padding: 0;
		margin: 0;
	}

	ul li {
		display: block;
		float: left;
		margin: 20px 20px 0;
	}

	li div {
		border: 1px solid #AAA;
		background: #DDD;
		padding: 2px 5px;
		min-width: 30px;
		margin: 0;
	}
	
	li ul {
		padding: 0;
	}

	body>div {
		float: left;
	}

	.horzline {
		background: #888;
		border: 0;
		height: 3px;
		padding: 0;
		display: block;
		margin: 0 auto;
	}
	.vertline_down {
		background: #888;
		border: 0;
		width: 3px;
		height: 42px;
		padding: 0;
		display: block;
		margin: 0 auto;
	}
	.vertline_up {
		background: #888;
		border: 0;
		width: 3px;
		height: 10px;
		padding: 0;
		display: block;
		margin: -20px auto 0;
	}

	</style>
</head>
<body>
<h1>Sitemap</h1>
<div>
<?= TreePrinter::$html; ?>
</div>
</body>
</html>
