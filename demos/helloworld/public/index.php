<?php
define('APP_PATH', realpath('../app') . DIRECTORY_SEPARATOR);
define('SYS_PATH', realpath('../../../system') . DIRECTORY_SEPARATOR);

set_include_path(APP_PATH . PATH_SEPARATOR . SYS_PATH);

spl_autoload_register(function($class) {
	if (\strpos($class, 'app') === 0) {
		require 'classes' . str_replace('\\', '/', \substr($class, 3)) . '.php';
	}
	if (\strpos($class, 'glenn') === 0) {
		require 'classes' . str_replace('\\', '/', \substr($class, 5)) . '.php';
	}
});

use glenn\config\Config,
	glenn\controller\FrontController,
	glenn\http\Request,
	glenn\loader\Autoloader,
	glenn\loader\Loader;

$request = new Request();
$router = new glenn\router\RouterTree();
$frontController = new FrontController($router);
$response = $frontController->dispatch($request);
$response->send();