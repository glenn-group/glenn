<?php
/* Demo for tree-based routing
*/

use \glenn\router\RouterTree,
	\glenn\router\datastructures\TreeArray,
	\glenn\router\datastructures\TreePrinter;

$router = new RouterTree();
$tree = new TreeArray();

$tree
	->addParent('Admin', 'admin', '/', "admin#index")
		->addParent('Users', 'users', '/admin', "users#index")
			->addChild('Add', 'add', '#add')
			->addChild('Delete', 'delete', '#delete')
			->addChild('Edit', 'edit', '#edit')

		->addParent('Blogposts', 'posts', '/admin', "posts#index")
			->addChild('Add', 'add', '#add')
			->addChild('Delete', 'delete', '#delete')
			->addChild('Edit', 'edit', '#edit')

	->addParent('Blog', 'blog', '/', "blog#index")
		->addParent('Category', '*', '/blog')
			->addChild('Title', '*')
;

$router->addRoutes($tree->toArray());

// Resolve route
if(isset($_GET['route'])) {
	$route = $_GET['route'];
	try {
		$trace = $router->resolveRoute('/'.$route);
	} catch(\Exception $e) {
		$trace = array();
		echo '404 - Route not found';
	}
}

// Create markup for tree
TreePrinter::traverseTreeWrapper($tree->toArray());
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

<?php for($i = 0; $i < $c = count($trace['nodes']); $i++): for($j = 0; $j <= $i; $j++): ?>
	.<?php echo $trace['nodes'][$j]; ?>
<?php endfor; echo "> div { background: #faa; }\n"; endfor; ?>

	</style>
</head>
<body>

<h1>Router</h1>

<h2>Test router with URI</h2>
<form action="" method="get">
	<p>
		<label for="uri">URI</label>
		<input type="text" id="uri" name="route" value="<?php echo isset($_GET['route']) ? $_GET['route'] : ''; ?>" />
		<input type="submit" value="Test" />
	</p>
</form>

<h2>Currently matched route</h2>
<pre>
<?php if(isset($trace)) {print_r($trace);} ?>
</pre>
<h2>Sitemap generated from routes</h2>
<div>
<?php echo TreePrinter::$html; ?>
</div>

</body>
</html>
