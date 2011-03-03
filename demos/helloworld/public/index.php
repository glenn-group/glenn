<?php
define('APP_PATH', realpath('../app') . DIRECTORY_SEPARATOR);
define('BASE_PATH', realpath('../../../') . DIRECTORY_SEPARATOR);

set_include_path(APP_PATH . PATH_SEPARATOR . BASE_PATH);

spl_autoload_register(function($class_name) {
    require_once str_replace("\\", "/", $class_name) . '.php';
});

use glenn\config\Config,
	glenn\controller\FrontController,
	glenn\http\Request;

$request = new Request();
$router = new glenn\router\RouterTree();
$frontController = new FrontController($router);
$response = $frontController->dispatch($request);
$response->send();

echo '<pre>';
$config1 = new Config(include APP_PATH . '/config1.php');
$config2 = new Config(include APP_PATH . '/config2.php');
print_r($config1);
print_r($config2);
$config1->merge($config2);
print_r($config1);