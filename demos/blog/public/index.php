<?php
define('APP_PATH', realpath('../app') . DIRECTORY_SEPARATOR);
define('BASE_PATH', realpath('../../../') . DIRECTORY_SEPARATOR);

set_include_path(APP_PATH . PATH_SEPARATOR . BASE_PATH);

spl_autoload_register(function($class) {
    require_once str_replace("\\", "/", $class) . '.php';
});

use glenn\controller\FrontController,
	glenn\config\Config,
	glenn\http\Request,
	glenn\router\RouterTree;

$request = new Request();
$router = new RouterTree();

$frontController = new FrontController($router);
$response = $frontController->dispatch($request);
$response->send();