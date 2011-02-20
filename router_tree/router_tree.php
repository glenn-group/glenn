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
		if ($request_uri[0] != '/') {
			$request_uri = '/'.$request_uri;
		}

		$uri = $this->uriToArray($request_uri);
		$trace = array();

		$arrayRef = &$this->tree;

		// Point to first level node
		if (isset($arrayRef[$uri[0]])) {
			$arrayRef = &$arrayRef[$uri[0]];
			$trace[] = $arrayRef['name'];
		}
		else if (isset($arrayRef['children']['*'])) {
			$arrayRef = &$arrayRef['*'];
			$trace[] = $arrayRef['name'];
		}

		// Point to correct node
		$levels = count($uri);
		for ($i = 1; $i < $levels; $i++) {
			if (isset($arrayRef['children'][$uri[$i]])) {
				$arrayRef = &$arrayRef['children'][$uri[$i]];
				$trace[] = $arrayRef['name'];
			}
			else if (isset($arrayRef['children']['*'])) {
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

// Test

$router = new Router_Tree;

$tree = new Tree_Array;

// Name, pattern, parent_path
// Name, pattern

$tree
	->addParent('Admin', 'admin', '/')

		->addParent('Users', 'users', '/admin')
			->addChild('Add', 'add')
			->addChild('Delete', 'delete')
			->addChild('Edit', 'edit')

		->addParent('Blogposts', 'posts', '/admin')
			->addChild('Add', 'add')
			->addChild('Delete', 'delete')
			->addChild('Edit', 'edit')

	->addParent('Blog', 'blog', '/')
		->addParent('Category', '*', '/blog')
			->addChild('Title', '*')
;

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
	$trace = $router->resolveRoute($route);
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
		height: 0px;
		padding: 0;
		display: block;
		margin: 0 auto;
	}
	.vertline_up {
		background: #888;
		border: 0;
		width: 3px;
		height: 42px;
		padding: 0;
		display: block;
		margin: -20px auto 0;
	}

	.root {
		background: #faa;
	}

<?php for($i = 0; $i < $c = count($trace); $i++): for($j = 0; $j <= $i; $j++): ?>
	.<?php echo $trace[$j]; ?>
<?php endfor; echo "> div { background: #faa; }\n"; endfor; ?>

	</style>
</head>
<body>

<h1>Router</h1>

<h2>Test router with URI</h2>
<form action="" method="get">
	<p>
		<label for="uri">URI</label>
		<input type="text" id="uri" name="route" value="<?php echo isset($_GET['route']) ? $_GET['route'] : '/'; ?>" />
		<input type="submit" value="Test" />
	</p>
</form>

<h2>Currently matched route</h2>
<pre>
<?php print_r($trace); ?>
</pre>
<h2>Sitemap generated from routes</h2>
<div>
<?php echo TreePrinter::$html; ?>
</div>

</body>
</html>
