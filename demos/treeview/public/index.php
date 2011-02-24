<?php
set_include_path('../app/controllers' . PATH_SEPARATOR . '../../../');

spl_autoload_register(function($class_name) {
    require_once str_replace("\\", "/", $class_name) . '.php';
});

use glenn\http\Request,
	glenn\controller\FrontController;

$request = new Request();
$frontController = new FrontController();
$response = $frontController->dispatch($request);
$response->send();
