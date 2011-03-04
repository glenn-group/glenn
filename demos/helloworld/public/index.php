<?php
define('APP_PATH', realpath('../app') . DIRECTORY_SEPARATOR);
define('SYS_PATH', realpath('../../../system') . DIRECTORY_SEPARATOR);

set_include_path(APP_PATH . PATH_SEPARATOR . SYS_PATH);

use glenn\config\Config,
	glenn\controller\FrontController,
	glenn\http\Request,
	glenn\loader\Loader;

require SYS_PATH . 'classes/loader/Loader.php';
Loader::registerAutoloader();
Loader::registerModules(array(
	'app'   => APP_PATH,
	'glenn' => SYS_PATH
));

$request = new Request();
$router = new glenn\router\RouterTree();
$frontController = new FrontController($router);
$response = $frontController->dispatch($request);
$response->send();