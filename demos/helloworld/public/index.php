<?php
define('APP_PATH', realpath('../app') . DIRECTORY_SEPARATOR);
define('BASE_PATH', realpath('../../../') . DIRECTORY_SEPARATOR);

set_include_path(APP_PATH . PATH_SEPARATOR . BASE_PATH);

spl_autoload_register(function($class_name) {
    require_once str_replace("\\", "/", $class_name) . '.php';
});

use glenn\controller\FrontController,
	glenn\http\Request;

$request = new Request();
$router = new glenn\router\RouterTree();
$frontController = new FrontController($router);
$response = $frontController->dispatch($request);
$response->send();