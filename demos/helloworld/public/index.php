<?php
define('APP_PATH', realpath('../app') . DIRECTORY_SEPARATOR);
define('SYS_PATH', realpath('../../../system') . DIRECTORY_SEPARATOR);

set_include_path(APP_PATH . PATH_SEPARATOR . SYS_PATH);

// Set up the Loader class
require SYS_PATH . 'classes/loader/Loader.php';
glenn\loader\Loader::registerAutoloader();
glenn\loader\Loader::registerModules(array(
	'app'   => APP_PATH,
	'glenn' => SYS_PATH
));

/***********************
 *  Start application  *
 ***********************/
use glenn\config\Config,
	glenn\controller\FrontController,
	glenn\http\Request,
	glenn\error\ErrorHandler;

ErrorHandler::register();

$request = new Request();
$router = new glenn\router\RouterTree();
$frontController = new FrontController($router);
$response = $frontController->dispatch($request);
$response->send();