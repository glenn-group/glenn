<?php
define('BASE_PATH', realpath('../../../') . DIRECTORY_SEPARATOR);
define('APP_PATH', BASE_PATH . 'demos/blog/app' . DIRECTORY_SEPARATOR);
define('EXTRAS_PATH', BASE_PATH . 'extras' . DIRECTORY_SEPARATOR);
define('SYSTEM_PATH', BASE_PATH . 'system' . DIRECTORY_SEPARATOR);

use glenn\config\Config,
	glenn\controller\FrontController,
	glenn\http\Request,
	glenn\loader\Loader,
	glenn\error\ErrorHandler,
	glenn\router\RouterTree,
	glenn\router\datastructures\TreeArray;

require SYSTEM_PATH . 'classes/loader/Loader.php';
Loader::registerAutoloader();
Loader::registerModules(array(
	'app'   => APP_PATH,
	'glenn' => SYSTEM_PATH
));

ErrorHandler::register();

$tree = new TreeArray();
$tree->addParent('Blog', 'blog', '/', array('get' => 'blog#index', 'post' => 'blog#create'));
$tree->addParent('Category', '<*>', '/blog', '#category');
$tree->addChild('Title', '<*>', '#view');
$tree->addParent('CatchAll', '*', '/', 'blog#index');

$router = new RouterTree('/glenn/demos/blog/public');
$router->addRoutes($tree->toArray());

require_once BASE_PATH . 'vendor/ActiveRecord/ActiveRecord.php';
ActiveRecord\Config::initialize(function($cfg) {
	$cfg->set_model_directory(APP_PATH . 'classes/model');
	$cfg->set_connections(array('development' => 'sqlite://blog.db'));
});

$request = new Request();
$frontController = new FrontController($router);
$response = $frontController->dispatch($request);
$response->send();