<?php
set_include_path('../application/controllers' . PATH_SEPARATOR . '../../../library/Glenn');

require_once 'Dispatchable.php';
require_once 'Request.php';
require_once 'Response.php';
require_once 'ScriptController.php';

use Application\ScriptController,
    Glenn\Request;

$request = new Request();
$scriptController = new ScriptController();
$response = $scriptController->dispatch($request);

echo '<pre>' . $request->uri . '</pre>';
echo '<pre>' . $request->method . '</pre>';
echo '<pre>' . $response->send() . '</pre>';