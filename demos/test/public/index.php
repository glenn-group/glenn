<?php

define('APP_PATH', realpath('../app') . DIRECTORY_SEPARATOR);
define('BASE_PATH', realpath('../../../') . DIRECTORY_SEPARATOR);

set_include_path(APP_PATH . PATH_SEPARATOR . BASE_PATH);

spl_autoload_register(function($class_name) {
			require_once str_replace("\\", "/", $class_name) . '.php';
		});

use glenn\controller\FrontController,
 glenn\config\Config,
 glenn\http\Request,
 glenn\router\RouterTree,
 glenn\router\datastructures\TreeArray;

$request = new Request();
$router = new RouterTree('/glenn/demos/test/public');

// Build routes with tree-helper (could be done with array directly)
$tree = new TreeArray();
$tree
		->addParent('Blog', 'blog', '/', array('resource' => 'blog'))
		->addParent('Category', '<*>', '/blog', '#category')
		->addChild('Title', '<*>', '#view')
		->addParent('CatchAll', '*', '/', 'index#index');

$router->addRoutes($tree->toArray());

$frontController = new FrontController($router);
$response = $frontController->dispatch($request);
$response->send();