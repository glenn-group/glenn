<?php
set_include_path('../application/controllers' . PATH_SEPARATOR . '../../../library');

spl_autoload_register(function($class_name) {
    require_once str_replace("\\", "/", $class_name) . '.php';
});

use Glenn\Request,
    Glenn\FrontController;

$request = new Request();
$frontController = new FrontController();
$response = $frontController->dispatch($request);

//echo '<pre>' . $request->uri . '</pre>';
//echo '<pre>' . $request->method . '</pre>';
//echo '<pre>' . $response->send() . '</pre>';


//Kernel::boot();
//echo new Application();