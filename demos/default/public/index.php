<?php
set_include_path('../application/controllers' . PATH_SEPARATOR . '../../../library/Glenn');

require_once 'Dispatchable.php';
require_once 'FrontController.php';
require_once 'Request.php';
require_once 'Response.php';
require_once 'IndexController.php';

use Glenn\Request,
    Glenn\FrontController;

$request = new Request();
$frontController = new FrontController();
$response = $frontController->dispatch($request);

echo '<pre>' . $request->uri . '</pre>';
echo '<pre>' . $request->method . '</pre>';
echo '<pre>' . $response->send() . '</pre>';