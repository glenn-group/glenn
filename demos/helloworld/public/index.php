<?php
set_include_path('../app/controllers' . PATH_SEPARATOR . '../../../');

spl_autoload_register(function($class_name) {
    require_once str_replace("\\", "/", $class_name) . '.php';
});

use glenn\controller\FrontController,
	glenn\config\Config,
	glenn\event\Event,
	glenn\http\Request;

$request = new Request();
$frontController = new FrontController();

$frontController->events()->bind('mvc.routing.pre', function(Event $e) {
	echo '<pre>';
	printf('I was called by: %s<br />', get_class($e->subject()));
	printf('The event triggered was: %s<br />', $e->name());
	print_r($e->params());
	echo '</pre>';
});

$response = $frontController->dispatch($request);
$response->send();

$config = new glenn\config\Ini('../app/config.ini');
echo '<pre>';
print_r($config->toArray());
echo '</pre>';

/*$config = new Config(include '../app/config.php');
echo '<pre>';
print_r($config->toArray());
echo '</pre>';*/
