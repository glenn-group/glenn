<?php
set_include_path('../application/controllers' . PATH_SEPARATOR . '../../../library/Glenn');

require_once 'Dispatchable.php';
require_once 'Request.php';
require_once 'Response.php';

use Glenn\Request as Request;

$request = new Request();

// syntax?
$sinatra = new Sinatra(array(
    '/index/page' => function() {
        return Response("Index page");
    },
    'about' => function() {
        return Response("About page");
    }
));

// syntax?
$sinatra = new Sinatra();

$sinatra->get('index', function() {
    return Response("Index page");
});

$sinatra->get('about', function() {
    return Response("About page");
});

$response = $sinatra->dispatch($request);

echo '<pre>' . $request->uri . '</pre>';
echo '<pre>' . $request->method . '</pre>';
echo '<pre>' . $response->send() . '</pre>';